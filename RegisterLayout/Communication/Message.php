<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include "../conn.php";
require "utils.php";
$user_id = isset($_COOKIE['UID']) ? $_COOKIE['UID'] : null;
// Set proper headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the raw POST data
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        // Check if JSON was valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON: ' . json_last_error_msg());
        }
        
        $Type = isset($data['Type']) ? $data['Type'] : '';
        
        switch ($Type) {
            case "createGroup":
                $GroupName = $data['name'];
                $GroupDesc = $data['description'];
                $GroupMembers = $data['members'];
                $ROLE = $data['role'] ?? 'MEMBER';

                createGroup($GroupName, $GroupDesc, $GroupMembers, $ROLE);
                break;
            case "sendMessageToServer":

                sendMessageToServer($data);
                exit;

            case "sendAddContact":
                $email = $data['Email'];
                if(empty($email)){
                    throw new Exception("Email is required");
                }
                
                AddUserNewToContactList($email);
                break;
            case "checkIfFriendInFriendContactList":
                $email = $data['Email'];
                if(empty($email)){
                    throw new Exception("Email is required");
                }
                checkIfFriendInFriendContactList($email);
                break;

            case "sendUserMessageToServer":
                $message = $data['message'];
                $ContactID = $data['FriendID'];
                $MessageType = $data['messageType'];

                sendUserMessageToServer($message, $ContactID, $MessageType);

            case "SendFriendRequest":

                $ContactID = $data['CurrentUserID'];
                $Status = $data['status'];

                if (empty($ContactID) || empty($Status)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Contact ID and Status are required'
                    ]);
                    exit;
                }

                SendFriendRequest($ContactID, $Status);

            case "CreateDMContactListForUser":
                CreateDMContactListForUser($user_id);
                break;

            case "UpdateGroupInfo":
                $GroupID = $data['GroupID'];
                $timestamp = time();
                $GroupName = $data['GroupName'] . "_" . $timestamp;
                $GroupDesc = $data['GroupDesc'];

                updateGroupInfo($GroupID, $GroupName, $GroupDesc);

                
                break;
            case "RemoveGroupMember":
                $GroupID = $data['GroupID'];
                $MemberID = $data['UserID'];
                
                if(empty($GroupID) || empty($MemberID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID and Member ID are required'
                    ]);
                    exit;
                }
                // Check if the current user is an admin of this group
                $isAdmin = Query("SELECT * FROM groupusers WHERE GroupInfoID = ? AND UserID = ? AND GroupRole = 'ADMIN'", "ii", [$GroupID, $user_id], "Not an admin", "bool", "SELECT");
                
                if(!$isAdmin) {
                    http_response_code(403);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'You must be an admin to remove members'
                    ]);
                    exit;
                }
                
                // Remove the member from the group
                $removeResult = Query("DELETE FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", "ii", [$GroupID, $MemberID], "Failed to remove member", "none", "DELETE");
                
                if($removeResult) {
                    // Update member count in the group info
                    Query("UPDATE groupinfo SET GroupMemberNo = (SELECT COUNT(*) FROM groupusers WHERE GroupInfoID = ?) WHERE id = ?", 
                         "ii", [$GroupID, $GroupID], "Failed to update member count", "none", "UPDATE");
                    
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Member removed successfully'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to remove member'
                    ]);
                }
                break;
                
            case "ToggleMuteMember":
                $GroupID = $data['GroupID'];
                $MemberID = $data['MemberID'];
                $Status = $data['Status']; // 'Muted' or 'Active'
                
                if(empty($GroupID) || empty($MemberID) || empty($Status)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID, Member ID and Status are required'
                    ]);
                    exit;
                }
                
                // Check if the current user is an admin of this group
                $isAdmin = Query("SELECT * FROM groupusers WHERE GroupInfoID = ? AND UserID = ? AND GroupRole = 'ADMIN'", 
                                "ii", [$GroupID, $user_id], "Not an admin", "bool", "SELECT");
                
                if(!$isAdmin) {
                    http_response_code(403);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'You must be an admin to change member status'
                    ]);
                    exit;
                }
                
                // Update member status
                $updateResult = Query("UPDATE groupusers SET GroupUserStatus = ? WHERE GroupInfoID = ? AND UserID = ?", "sii", [$Status, $GroupID, $MemberID], "Failed to update member status", "none", "UPDATE");
                
                if($updateResult) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Member status updated successfully'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update member status'
                    ]);
                }
                break;
                
            case "ToggleMuteGroup":
                $GroupID = $data['GroupID'];
                $Status = $data['Status']; // 'Muted' or 'Active'
                if (empty($GroupID) || empty($Status)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID and Status are required'
                    ]);
                    exit;
                }
                ToggleMuteGroup($GroupID, $Status);

                break;
            case "ToggleBanMember":
                $GroupID = $data['GroupID'];
                $MemberID = $data['MemberID'];
                $Status = $data['Status']; // 'Banned' or 'Active'
                
                if(empty($GroupID) || empty($MemberID) || empty($Status)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID, Member ID and Status are required'
                    ]);
                    exit;
                }
                
                // Check if the current user is an admin of this group
                $isAdmin = Query("SELECT * FROM groupusers WHERE GroupInfoID = ? AND UserID = ? AND GroupRole = 'ADMIN'", 
                                "ii", [$GroupID, $user_id], "Not an admin", "bool", "SELECT");
                
                if(!$isAdmin) {
                    http_response_code(403);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'You must be an admin to ban or unban members'
                    ]);
                    exit;
                }
                
                if($Status === 'Banned') {
                    // Ban the member - first update their status
                    $updateStatus = Query("UPDATE groupusers SET Status = ? WHERE GroupInfoID = ? AND UserID = ?", 
                                         "sii", [$Status, $GroupID, $MemberID], "Failed to update member status", "none", "UPDATE");
                    
                    // Then, remove them from the group (banned users are not in the group)
                    $removeResult = Query("DELETE FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", 
                                         "ii", [$GroupID, $MemberID], "Failed to remove banned member", "none", "DELETE");
                    
                    // Add entry to banned users table
                    $addToBanList = Query("INSERT INTO groupbannedusers (GroupID, UserID) VALUES (?, ?)", 
                                          "ii", [$GroupID, $MemberID], "Failed to add to ban list", "none", "INSERT");
                    
                    if($updateStatus && $removeResult && $addToBanList) {
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Member banned successfully'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Failed to ban member'
                        ]);
                    }
                } else {
                    // Unban the member - remove them from the banned users table
                    $removeBan = Query("DELETE FROM groupbannedusers WHERE GroupID = ? AND UserID = ?", 
                                      "ii", [$GroupID, $MemberID], "Failed to remove from ban list", "none", "DELETE");
                    
                    if($removeBan) {
                        echo json_encode([
                            'status' => 'success',
                            'message' => 'Member unbanned successfully'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Failed to unban member'
                        ]);
                    }
                }
                break;

            case "AddGroupMembers":
                $GroupEmail = $data['Emails'];
                $GroupID = $data['GroupID'];

                AddGroupMembers($GroupEmail, $GroupID);

            case 'DeleteGroup':
                $GroupID = isset($data['GroupID']) ? $data['GroupID'] : null;
                if (empty($GroupID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID is required'
                    ]);
                    exit;
                }
                DeleteGroup($GroupID);
                break;
            case 'LeaveGroup':
                $GroupID = isset($data['GroupID']) ? $data['GroupID'] : null;
                if (empty($GroupID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID is required'
                    ]);
                    exit;
                }

                LeaveGroup(GroupID: $GroupID);
                break;

            case "BlockUser":
                $ContactID = $data['ContactID'];
                if (empty($ContactID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Contact ID is required'
                    ]);
                    exit;
                }
                BlockUser($ContactID);
                break;
            
            case "CheckIfStatusisFriend":
                $ContactID = $data['ContactID'];
                if (empty($ContactID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Contact ID is required'
                    ]);
                    exit;
                }
                echo json_encode([
                    'status' => 'success',
                    'message' => CheckIfStatusisFriend($ContactID)

                ]);
                break;

            case "UnblockUser":
                $ContactID = $data['ContactID'];
                if (empty($ContactID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Contact ID is required'
                    ]);
                    exit;
                }
                UnblockUser($ContactID);
                break;

            default:
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Unknown action type'
                ]);
                break;
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if Type parameter exists
    if (isset($_GET['Type'])) {
        $Type = $_GET['Type'];

        switch ($Type) {
            case "GetMessageInfo":
                $GroupID = isset($_GET['GroupID']) ? $_GET['GroupID'] : null;
                if (empty($GroupID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID is required'
                    ]);
                    exit;
                }
                $response = getMessageInfoForGroup($GroupID);

                echo json_encode([
                    'status' => 'success',
                    'message' => $response
                ]);
                exit;

            case "GetGroupContactList":
                getGroupData($user_id);

            case "GetUserStatus":
                $ContactID = isset($_GET['Contact_ID']) ? $_GET['Contact_ID'] : null;
                if (empty($ContactID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Contact ID is required'
                    ]);
                    exit;
                }
                getUserStatus($ContactID);
                break;

            case "VerifyContactListExist":
                if (CheckIfPersonContactListExist($_conn)) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Contact list exists'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Contact list does not exist'
                    ]);
                }
                break;
            case "GetSuggestions":
                $Email = isset($_GET['Email']) ? $_GET['Email'] : null;
                if (empty($Email)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Email is required'
                    ]);
                    exit;
                }
                $response = getListOfemail($Email);
                if (empty($response)) {
                    http_response_code(404);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'No user found with that email'
                    ]);
                    exit;
                }
                echo json_encode($response);
                break;
            case "GetContactListForDM":
                GetContactListForDM();
                break;

            case "CheckEmail":
                $email = isset($_GET['Email']) ? $_GET['Email'] : null;

                if (!is_array($email)) {
                    $email = explode(',', $email); // Convert "email1,email2" to an array
                }

                if (empty($email)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Email is required'
                    ]);
                    exit;
                }

                foreach ($email as $key => $value) {
                    $CheckEmailValid = Query("SELECT * FROM users WHERE email = ?", "s", trim($value), "Email not found", "bool", "SELECT");
                
                    $response[] = [
                        'email' => $value,
                        'status' => $CheckEmailValid ? 'success' : 'error',
                        'message' => $CheckEmailValid ? 'Email found' : 'Email not found'
                    ];
                }
                echo json_encode($response);
                exit;

            case "GetTheLastMessageAndTime":
                GetTheLastMessageAndTime($user_id);

            case "GetOnlyOneContactListForDM":
                $FriendEmail = isset($_GET['Email']) ? $_GET['Email'] : null;
                if (empty($FriendEmail)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Email is required'
                    ]);
                    exit;
                }
                GetOnlyOneContactListForDM($FriendEmail);
                break;

            case "VerifyDMContactListExist":
                VerifyDMContactListExist($user_id);
            
            case "GetMessageInfoForDM":

                $ContactID = isset($_GET['Contact_ID']) ? $_GET['Contact_ID'] : null;
                if (empty($ContactID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Contact ID is required'
                    ]);
                    exit;
                }
                GetMessageInfoForDM($ContactID);
                break;

            case 'GetGroupMembers':
                $GroupID = isset($_GET['GroupID']) ? $_GET['GroupID'] : null;
                if (empty($GroupID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID is required'
                    ]);
                    exit;
                }
                $GroupMembers = GetGroupMembers($GroupID);
                // Get members with their status

                
                echo json_encode([
                    'status' => 'success',
                    'message' => $GroupMembers
                ]);
                exit;

            case "GetGroupInfo":
                $GroupID = isset($_GET['GroupID']) ? $_GET['GroupID'] : null;
                if (empty($GroupID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID is required'
                    ]);
                    exit;
                }
                $response = getGroupInfo($GroupID);
                echo json_encode([
                    'status' => 'success',
                    'message' => $response
                ]);
                break;

            case "GetCurrentUserRole":
                $GroupID = isset($_GET['GroupID']) ? $_GET['GroupID'] : null;
                if (empty($GroupID) || empty($user_id)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID and user ID are required'
                    ]);
                    exit;
                }
                
                // Get the user's role in this group
                $userRole = Query(
                    "SELECT GroupRole FROM groupusers WHERE GroupInfoID = ? AND UserID = ?",
                    "ii", 
                    [$GroupID, $user_id],
                    "User role not found",
                    "single",
                    "SELECT",
                    null
                );
                
                if ($userRole && isset($userRole['GroupRole'])) {
                    echo json_encode([
                        'status' => 'success',
                        'role' => $userRole['GroupRole']
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'User role not found'
                    ]);
                }
                exit;

            case "CheckIfBlockedBy":
                $ContactID = isset($_GET['ContactID']) ? $_GET['ContactID'] : null;
                if (empty($ContactID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Contact ID is required'
                    ]);
                    exit;
                }
                $isBlockedBy = CheckIfUserIsBlockedBy($ContactID);
                echo json_encode([
                    'status' => 'success',
                    'isBlockedBy' => $isBlockedBy
                ]);
                break;

            case 'CheckUserBlock':
                $ContactID = $_GET['Contact_ID'];
                if (empty($ContactID)) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Group ID and User ID are required'
                    ]);
                    exit;
                }

                $Result = CheckIfUserIsBlock($ContactID);

                echo json_encode([
                    'status' => 'success',
                    'message' => $Result
                ]);
                exit;

            default:
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Unknown Type parameter value'
                ]);
                break;
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing Type parameter'
        ]);
    }
} else {
    // ...existing code for handling other request methods...
}

