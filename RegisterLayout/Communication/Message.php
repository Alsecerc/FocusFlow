<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include "../conn.php";

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
                break;

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
                $FriendID = $data['CurrentUserID'];
                $status = $data['status'];

            case "CreateDMContactListForUser":
                CreateDMContactListForUser($user_id);
                    
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

                echo json_encode($response);
                break;

            case "GetGroupContactList":
                getGroupData($user_id);

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

function GetContactListForDM(){
    global $user_id;
    $ContactID = getContactListIdOfUser();

    if (!$ContactID) {
        echo json_encode(['status' => 'error', 'message' => 'User has no contact list']);
        exit;
    }
        
    $GetUserRelationShip = Query("SELECT * FROM contact WHERE ContactListID = ?", "i", $ContactID, "No Data Found", "array", "SELECT");

    if(empty($GetUserRelationShip)){
        echo json_encode([
            'status' => 'error',
            'message' => 'No data found'
        ]);
        exit;
    }

    $finalData = [];
    foreach ($GetUserRelationShip as $relation) {
        $friendData = [
            'ID' => $relation['id'],
            'friend_id' => $relation['FriendID'],
            'friendName' => "None",
            'created_at' => $relation['created_at'],
            'MessageText' => "Enter Something Here"
        ];

        $FriendID = Query("SELECT * FROM friends WHERE id = ?", "i", $relation['FriendID'], "Friend ID not found", "single", "SELECT", true);

        // Check if $FriendID is actually an array before accessing array keys
        if (!$FriendID || !is_array($FriendID)) {
            // Skip this iteration if friend record not found
            continue;
        }

        $NewFriendID = null;
        
        if ((int)$FriendID['user_id'] === (int)$user_id) {
            $NewFriendID = $FriendID['friend_id'];
        } else {
            $NewFriendID = $FriendID['user_id'];
        }

        // Get friend's name
        $FriendName = Query("SELECT name FROM users WHERE id = ?", "i", $NewFriendID, "Username Not found", "single", "SELECT", true);
        
        if($FriendName && is_array($FriendName)){
            $friendData['friendName'] = $FriendName['name'];
        }

        // Fix: The issue is here - we need to check for messages in both directions
        // First check messages where this user is the sender
        $GetUserChatLog1 = Query("SELECT * FROM directmessage WHERE FriendID = ? AND SenderID = ? ORDER BY DirectMessageID DESC LIMIT 1", "ii", [$relation['FriendID'], $user_id], "No Data Found", "single", "SELECT", null);
        
        // Then check messages where this user is the receiver
        $GetUserChatLog2 = Query("SELECT * FROM directmessage WHERE FriendID = ? AND SenderID = ? ORDER BY DirectMessageID DESC LIMIT 1", 
            "ii", [$relation['FriendID'], $NewFriendID], "No Data Found", "single", "SELECT", null);
        
        // Use the most recent message from either query
        $latestMessage = null;
        $latestTime = null;
        
        if($GetUserChatLog1 && is_array($GetUserChatLog1)) {
            $latestMessage = $GetUserChatLog1['MessageText'];
            $latestTime = $GetUserChatLog1['CreatedTime'];
        }
        
        if($GetUserChatLog2 && is_array($GetUserChatLog2)) {
            if(!$latestTime || $GetUserChatLog2['CreatedTime'] > $latestTime) {
                $latestMessage = $GetUserChatLog2['MessageText'];
                $latestTime = $GetUserChatLog2['CreatedTime'];
            }
        }
        
        if($latestMessage) {
            $friendData['MessageText'] = $latestMessage;
            $friendData['created_at'] = $latestTime;
        }

        $finalData[] = $friendData;
    }

    // ...rest of the function remains the same...
    if(empty($finalData)){
        echo json_encode([
            'status' => 'error',
            'message' => 'No data found'
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'success',
            'message' => $finalData
        ]);
        exit;
    }
}

/**
 * Retrieves the friendship record ID between two users
 * 
 * This function checks if a friendship exists between two users by querying the
 * friends table in both directions (as either user could have initiated the friendship).
 * It first checks if friendID is the user and userID is the friend, and if not found,
 * checks the reverse relationship.
 * 
 * @param int $userID The ID of the first user
 * @param int $friendID The ID of the second user
 * @return int|null Returns the ID of the friendship record if found, null otherwise
 */
function GetFriendID($friendID){
    global $user_id;
    $FriendID = Query("SELECT * FROM friends WHERE id = ?", "i", $friendID, "Friend ID not found", "single", "SELECT", true);

    if (!$FriendID) {
        return null; // Return null if no data is found
    }

    // If the current user is the 'user_id' in the friendship record, 
    // then the friend is the 'friend_id'
    return match ((int)$FriendID['user_id'] === (int)$user_id) {
        true => $FriendID['friend_id'],
        default => $FriendID['user_id'],
    };
}

function sendUserMessageToServer($message, $ContactID, $MessageType){
    global $user_id;
    // create contact for the friend also if they dont have contact
    $GetFriendID = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $ContactID, "Friend ID not found", "single", "SELECT", true);

    $GetUserFriendID = Query("SELECT * FROM friends WHERE id = ? AND user_id = ?", "ii", [$GetFriendID['FriendID'], $user_id], "Friend ID not found", "single", "SELECT", null);

    $FriendUserID = null;

    // get the friend user id
    if (!$GetUserFriendID) {
        $GetUserFriendID1 = Query("SELECT * FROM friends WHERE id = ? AND friend_id = ?", "ii", [$GetFriendID['FriendID'], $user_id], "Friend ID not found", "single", "SELECT", true);
        $FriendUserID = $GetUserFriendID1['user_id'];
    }else{
        $FriendUserID = $GetUserFriendID['friend_id'];
    }

    $CheckIfUserhaveContactlist = Query("SELECT * FROM contactlist WHERE UserID = ?", "i", $FriendUserID, "Contact ID not found", "bool", "SELECT", null);

    if(!$CheckIfUserhaveContactlist){
        $CreateContactlist = Query("INSERT INTO contactlist (UserID) VALUES (?)", "i", $FriendUserID, "Failed to create contact list", "none", "INSERT");
    }

    $UserContactlist = Query("SELECT * FROM contactlist WHERE UserID = ?", "i", $FriendUserID, "Contact ID not found", "single", "SELECT", true);
    $NewContactID = $UserContactlist['ContactID'];

    //for the friend
    $CheckContactIDExistForContactID = Query("SELECT * FROM contact WHERE ContactListID = ? AND FriendID = ?", "ii", [$NewContactID, $GetFriendID['FriendID']], "Contact ID not found", "bool", "SELECT", null);

    $GetContactListIDofFriend = Query("SELECT ContactID FROM contactlist WHERE UserID = ?", "i", $FriendUserID, "Contact list not found", "single", "SELECT", true);

    if (!$CheckContactIDExistForContactID) {
        $CheckIFFriendExistInContactList = Query("SELECT * FROM contact WHERE ContactListID = ? AND FriendID = ?", "ii", [$GetContactListIDofFriend['ContactID'], $user_id], "Contact does not exist", "bool", "SELECT", null);


        if (!$CheckIFFriendExistInContactList) {
            $AddUserToContact = Query("INSERT INTO contact (ContactListID, FriendID) VALUES (?, ?)", "ii", [$GetContactListIDofFriend['ContactID'], $GetFriendID['FriendID']], "Failed to add user to contact list", "none", "INSERT");
        }
    }

    $SendUserMessage = Query("INSERT INTO directmessage (FriendID, SenderID, ReceiverID, MessageText, MessageType) VALUES (?, ?, ?, ?, ?)", "iiiss", [$GetFriendID['FriendID'], $user_id, $FriendUserID, $message, $MessageType], "Failed to send message", "none", "INSERT");
    if($SendUserMessage){
        echo json_encode([
            'status' => 'success',
            'message' => 'Message sent successfully'
        ]);
        exit;
    }
    
}
function GetMessageInfoForDM($ContactID){
    global $user_id;

    $FriendID = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $ContactID, "Friend ID not found", "single", "SELECT", true);

    $GetUserChatLog = Query("SELECT * FROM directmessage WHERE FriendID = ? ORDER BY DirectMessageID DESC", "i", $FriendID['FriendID'], "No Data Found", "array", "SELECT");
    $finalData = [];
    foreach ($GetUserChatLog as $value) {

        $Friend_ID = Query("SELECT * FROM friends WHERE id = ? AND user_id = ?", "ii", [$value['FriendID'], $user_id], "Friend ID not found", "single", "SELECT", null);

        if (!$Friend_ID) {
            $Friend_ID = Query("SELECT * FROM friends WHERE id = ? AND friend_id = ?", "ii", [$value['FriendID'], $user_id], "Friend ID not found", "single", "SELECT", true);
        }

        $finalData[] = [
            'ID' => $value['DirectMessageID'],
            'FriendID' => $value['FriendID'],
            'SenderID' => $value['SenderID'],
            'sender'=> UsernameFromID($value['SenderID']),
            'ReceiverID' => $value['ReceiverID'],
            'Receiver' => UsernameFromID($value['ReceiverID']),
            'content' => $value['MessageText'],
            'time' => $value['CreatedTime'],
            'type' => $value['MessageType'],
            'name' => UsernameFromID($Friend_ID['friend_id']),
            'status'=> $Friend_ID['status'] ? $Friend_ID['status'] : 'error'
        ];
    }
    
    if(empty($finalData)){
        echo json_encode([
            'status' => 'error',
            'message' => 'No data found'
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'success',
            'message' => $finalData
        ]);
        exit;
    }
}

function GetOnlyOneContactListForDM($FriendEmail){
    global $user_id;
    $ContactID = getContactListIdOfUser();
    $FriendID = getuserIdFromEmail($FriendEmail);
    $GetOneUserInfoFromFriend = Query("SELECT * FROM friends WHERE friend_id = ? AND user_id = ?", "ii", [$FriendID, $user_id], "No Data Found", "single", "SELECT");
    $finalData = [];

    $friendData = [
        'ID' => $GetOneUserInfoFromFriend['id'],
        'friend_id' => $GetOneUserInfoFromFriend['friend_id'],
        'friendName' => "None",
        'created_at' => $GetOneUserInfoFromFriend['created_at'],
        'MessageText' => "Enter Something Here"
    ];
    $FriendName = Query("SELECT * FROM users WHERE id = ?", "i", $GetOneUserInfoFromFriend['friend_id'], "Username Not found", "single", "SELECT", true);
    if($FriendName){
        $friendData['friendName'] = $FriendName['name'];
    }
    $param = $GetOneUserInfoFromFriend['friend_id'];
    $GetUserChatLog = Query("SELECT * FROM directmessage WHERE FriendID = ? ORDER BY DirectMessageID DESC LIMIT 1", "i", $param, "No Data Found", "single", "SELECT", null);
    if(!empty($GetUserChatLog)){
        $friendData['MessageText'] = $GetUserChatLog['MessageText'];
        $friendData['created_at'] = $GetUserChatLog['CreatedTime'];
    }
    $finalData[] = $friendData;
    
    if(!empty($finalData)){
        echo json_encode([
            'status' => 'success',
            'message' => $finalData
        ]);
        exit;
    }
}

function checkIfFriendInFriendContactList($Email){
    global $user_id;
    // get contactID
    $sqlGetContactID = "SELECT ContactID FROM contactlist WHERE UserID = ?";
    $contactID = Query($sqlGetContactID, "i", $user_id, "Contact list not found", "single", "SELECT", true);

    $sqlGetUserIdFromEmail = "SELECT id FROM users WHERE email = ?";
    $userIdFromEmail = Query($sqlGetUserIdFromEmail, "s", $Email, "User not found", "single", "SELECT");

    $CheckIfFriendExistInFriendContactList = "SELECT * FROM friends WHERE user_id = ? AND friend_id = ?";
    $ArrayFriendExist = [$user_id, $userIdFromEmail['id']];

    $FriendExist = Query($CheckIfFriendExistInFriendContactList, "ii", $ArrayFriendExist, "Friend does not exist", "bool", "SELECT");

    if (!$FriendExist) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Friend does not exist in friend table'
        ]);
        exit;
    }

    $GetFriendID = Query("SELECT id FROM friends WHERE user_id = ? AND friend_id = ?", "ii", [$user_id, $userIdFromEmail['id']], "Friend ID not found", "single", "SELECT", null);

    if(!$GetFriendID){
        $GetFriendID = Query("SELECT id FROM friends WHERE user_id = ? AND friend_id = ?", "ii", [$user_id, $userIdFromEmail['id']], "Friend ID not found", "single", "SELECT", true);
    }

    $sqlContactList = "SELECT * FROM contact WHERE ContactListID = ? AND FriendID = ?";
    $ArrayContactList = [$contactID, $GetFriendID['id']];
    
    $ContactListExist = Query($sqlContactList, "iii", $ArrayContactList, "Contact does not exist", "bool", "SELECT");

    if ($ContactListExist) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Friend already exists in Contact List'
        ]);
        exit;
    }
}

function CreateDMContactListForUser($user_id){
    global $_conn;
    $CreateContactList = Query("INSERT INTO contactlist (UserID) VALUES (?)", "i", $user_id, "Failed to create contact list", "none", "INSERT");
    if($CreateContactList){
        echo json_encode([
            'status' => 'success',
            'message' => 'Contact list created successfully'
        ]);
        exit;
    }
}

function SendFriendRequest($FriendID, $status){
    global $_conn, $user_id;
    $SendFriendrequest = Query("UPDATE friendrequest SET Status = ? WHERE friend_id = ? AND user_id = ?", "is", [$FriendID, $user_id, $status], "Failed to send friend request", "none", "INSERT");

    if (!$SendFriendrequest) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to send friend request'
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'success',
            'message' => 'Friend request sent successfully'
        ]);
        exit;
    }
}

function VerifyDMContactListExist($user_id){
    global $_conn;
    $CheckIfContactlistExist = Query("SELECT * FROM contactlist WHERE UserID = ?", "i", $user_id, "Contact list not found", "bool", "SELECT");
    if($CheckIfContactlistExist){
        echo json_encode([
            'status' => 'success',
            'message' => 'Contact list exists'
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Contact list does not exist'
        ]);
        exit;
    }
}
function GetTheLastMessageAndTime($user_id){
    
}

function AddUserNewToContactList($Email){
    global $user_id, $_conn;

    $GetFriendID = "SELECT id FROM users WHERE email = ?";
    $UserFriendID = Query($GetFriendID, "s", $Email, "Friend id not found", "single", "SELECT", true);

    $sqlGetContactID = "SELECT ContactID FROM contactlist WHERE UserID = ?";
    $contactListID = Query($sqlGetContactID, "i", $user_id, "Contact list not found", "single", "SELECT", true);

    $CheckFriendsExist = "SELECT * FROM friends WHERE user_id = ? AND friend_id = ?";
    $CheckFriendsExistParams = [$user_id, $UserFriendID['id']];
    $FriendExist = Query($CheckFriendsExist, "ii", $CheckFriendsExistParams, "Friend does not exist", "bool", "SELECT", null);

    if (!$FriendExist) {
        $FriendExist = Query($CheckFriendsExist, "ii", [$UserFriendID['id'], $user_id], "Friend does not exist", "bool", "SELECT", true);

        if (!$FriendExist){// create a new friends relationship
            $AddUserIntoFriends = "INSERT INTO friends (user_id, friend_id) VALUES (?, ?)";
            $AddUserIntoFriendsParams = [$user_id, $UserFriendID['id']];
            $AddUserIntoFriendsResult = Query($AddUserIntoFriends, "ii", $AddUserIntoFriendsParams, "Failed to add user to contact list", "none", "INSERT");
            if(!$AddUserIntoFriendsResult){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to add user to friend list'
                ]);
                exit;
            }

        }
    }

    $FriendID = Query("SELECT id FROM friends WHERE user_id = ? AND friend_id = ?", "ii", [$user_id, $UserFriendID['id']], "Friend ID not found", "single", "SELECT", null);

    if (!$FriendID){
        $FriendID = Query("SELECT id FROM friends WHERE user_id = ? AND friend_id = ?", "ii", [$UserFriendID['id'], $user_id], "Friend ID not found", "single", "SELECT", true);
    }

    $ContactIDExist = Query("SELECT * FROM contact WHERE ContactListID = ? AND FriendID = ?", "ii", [$contactListID['ContactID'], $FriendID['id']], "Contact ID not found", "bool", "SELECT", null);

    $AddUserResult = false;

    if(!$ContactIDExist){// check if the user contactID exist in the contact table
        $AddUserToContactList = "INSERT INTO contact (ContactListID, FriendID) VALUES (?, ?)";
        $AddUserToContactListParams = [$contactListID['ContactID'], $FriendID['id']];
        $AddUserToContactListResult = Query($AddUserToContactList, "ii", $AddUserToContactListParams, "Failed to add user to contact list", "none", "INSERT");
        $AddUserResult = true;
    }

    if($AddUserResult){
        echo json_encode([
            'status' => 'success',
            'message' => 'User added to contact list successfully'
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add user to contact list already exist'
        ]);
        exit;
    }
}
function getMessageInfoForGroup($groupId){
    global $_conn;
    $sql = "SELECT * FROM groupchat WHERE GroupID = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $groupId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => $row['GroupID'],
            'username' => UsernameFromID($row['user_id']),
            'message' => $row['GroupMessage'],
            'messageType' => $row['GroupMessageType'],
            'timestamp' => $row['CreatedTime']
        ];
    }
    $stmt->close();
    return $messages;
}

function UsernameFromID($userId){
    global $_conn;
    $row = Query("SELECT name FROM users WHERE id = ?", "i", $userId, "Username not found", "single", "SELECT", true);
    return $row['name'];
}

function getGroupData($userId) {
    $GetGroupInfoID = Query("SELECT * FROM groupusers WHERE UserID = ?", "i", $userId, "No Group found", "array", "SELECT");
    
    if(empty($GetGroupInfoID)){
        echo json_encode([
            'status' => 'error',
            'message' => 'No data found'
        ]);
        exit;
    }
    $finalData = [];
    foreach ($GetGroupInfoID as $value) {
        $GroupID = isset($value['GroupInfoID']) ? $value['GroupInfoID'] : null;

        $GroupInfo = Query("SELECT * FROM groupinfo WHERE id = ?", "i", $GroupID, "No Group found", "single", "SELECT", true);

        $GroupMessages = Query("SELECT * FROM groupchat WHERE GroupID = ? ORDER BY CreatedTime DESC LIMIT 1", "i", $GroupID, "No Data Found", "single", "SELECT", null);

        $finalData[] = [
            'GroupID' => $GroupID,
            'GroupName' => $value['GroupName'] ?? 'Unknown',
            'GroupRole' => $value['GroupRole'] ?? 'Member',
            'GroupDesc' => $GroupInfo['GroupDesc'] ?? 'No Description',
            'GroupMessageSender' => $GroupMessages && isset($GroupMessages['user_id']) ? UsernameFromID($GroupMessages['user_id']) : "No Sender",
            'GroupMessages' => $GroupMessages ? $GroupMessages['GroupMessage'] : "Enter Something Here",
            'GroupMessageType' => $GroupMessages['GroupMessageType'] ?? "text",
            'GroupMessageTime' => $GroupMessages ? $GroupMessages['CreatedTime'] : $GroupInfo['GroupCreatedTime']
        ];
    }
    if(empty($finalData)){
        echo json_encode([
            'status' => 'error',
            'message' => 'No data found'
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'success',
            'message' => $finalData
        ]);
        exit;
    }
}

function sendMessageToServer($messageData) {
    global $_conn, $user_id;
    try {
        // Use the UserID from the request, or fallback to the cookie
        $userId = !empty($messageData['UserID']) ? $messageData['UserID'] : $user_id;
        
        if (empty($userId)) {
            throw new Exception('User ID is required');
        }
        if (empty($messageData['GroupID'])) {
            throw new Exception('Group ID is required');
        }
        if (empty($messageData['message'])) {
            throw new Exception('Message content is required');
        }

        $groupId = $messageData['GroupID'];
        $message = $messageData['message'];
        $messageType = $messageData['messageType'] ?? 'TEXT';

        // Debug output
        error_log("Sending message. GroupID: $groupId, UserID: $userId, Message: $message");

        // Fix the SQL query - add the correct number of placeholders
        $stmt = $_conn->prepare("INSERT INTO groupchat (GroupID, user_id, GroupMessage, GroupMessageType) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $_conn->error);
        }

        $stmt->bind_param("iiss", $groupId, $userId, $message, $messageType);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Failed to send message");
        }
        $stmt->close();

        $response = [
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => [
                'groupId' => $groupId,
                'userId' => $userId,
                'message' => $message,
                'messageType' => $messageType,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ];
        
        echo json_encode($response);
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}

function getListOfemail($email){
    global $_conn;
    $email = "%" . $email . "%";
    $sql = "SELECT * FROM users WHERE email LIKE ? LIMIT 5";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    return $rows;
}

function getuserIdFromEmail($email){
    global $_conn;
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['id'];
}

/**
 * Checks if the current user has any contacts in their contact list
 *
 * This function verifies whether the user identified by the 'UID' cookie
 * has any entries in the contactlist table in the database.
 *
 * @global resource $_conn Database connection object
 * @uses $_COOKIE['UID'] User ID stored in cookie
 * 
 * @return bool Returns true if the user has contacts, false otherwise
 */
function CheckIfPersonContactListExist($_conn){
    $userID = $_COOKIE['UID'];
    $sql = "SELECT * FROM contactlist WHERE UserID = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    if ($stmt->affected_rows === 0) {
        return false;
    }
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

/**
 * Retrieves the contact list ID for the currently logged-in user.
 * 
 * This function queries the database to find the ContactID associated
 * with the current user's ID (retrieved from the UID cookie).
 * 
 * @return int The ContactID from the contactlist table
 * @throws Exception If no contact list ID is found or the query fails
 * @global object $_conn Database connection object
 * @uses $_COOKIE['UID'] The current user's ID from cookie
 */
function getContactListIdOfUser (){
    global $_conn;
    $userID = $_COOKIE['UID'];
    $GetContactListIDOfUser = Query("SELECT ContactID FROM contactlist WHERE UserID = ?", "i", $userID, "Contact list not found", "single", "SELECT", null);

    if (!$GetContactListIDOfUser) {
        CreateContactListForUser($userID);
        $GetContactListIDOfUser = Query("SELECT ContactID FROM contactlist WHERE UserID = ?", "i", $userID, "Contact list not found", "single", "SELECT", true);
    }
    return $GetContactListIDOfUser['ContactID'];
}
function createGroup($groupname, $groupDesc, $groupmembers, $ROLE){
    global $user_id;
    $CreatorEmail = $_COOKIE['EMAIL'];
    if (!is_array($groupmembers)) {
        $groupmembers = explode(',', $groupmembers); // Convert "email1,email2" to an array
    }
    
    $timeStamp = time();
    $uniqueGroupName = $groupname . "_" . $timeStamp;
    
    $InsertGroupInfo = Query("INSERT INTO groupinfo (GroupName, GroupDesc, GroupMemberNo) VALUES (?, ?, ?)", "ssi", [$uniqueGroupName, $groupDesc, 0], "Failed to create group", "none", "INSERT");
    if (!$InsertGroupInfo){
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to create group.'
        ]);
        exit;
    }

    // Get the ID of the newly created group
    $NewCreateGroupInfoID = Query("SELECT id FROM groupinfo WHERE GroupName = ? ORDER BY GroupCreatedTime DESC LIMIT 1", "s", $uniqueGroupName, "Group not found", "single", "SELECT", true);

    // Update the group member count
    $GetGroupMemberNo = Query("SELECT GroupMemberNo FROM groupinfo WHERE id = ?", "i", $NewCreateGroupInfoID['id'], "Group not found", "single", "SELECT", true);

    if (!$GetGroupMemberNo){
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to get group member number.'
        ]);
        exit;
    }

    $GroupInfoID = Query("SELECT id FROM groupinfo WHERE GroupName = ?", "s", $uniqueGroupName, "Group not found", "single", "SELECT", true);

    if (!$GroupInfoID){
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to get group ID.'
        ]);
        exit;
    }
    $addedMembers = 0;
    if (is_array($groupmembers)) {
        foreach ($groupmembers as $member) {

            $GetGroupMemberId = Query("SELECT id FROM users WHERE email = ?", "s", $member, "User not found", "single", "SELECT", true);
        
            $ROLE = ($member === $CreatorEmail) ? 'ADMIN' : 'MEMBER';
            $InsertGroupUser = Query("INSERT INTO groupusers (GroupInfoID, GroupName, GroupRole, UserID) VALUES (?, ?, ?, ?)", "issi", [$GroupInfoID['id'], $uniqueGroupName, $ROLE, $GetGroupMemberId['id']], "Failed to add user to group", "none","INSERT");
            if(!$InsertGroupUser){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to add user to group.'
                ]);
                exit;
            }else{
                $addedMembers++;
            }
        }
    }

    if ($addedMembers > 0) {
        Query("UPDATE groupinfo SET GroupMemberNo = (SELECT COUNT(*) FROM groupusers WHERE GroupInfoID = ?) WHERE id = ?", "ii", [$GroupInfoID['id'], $GroupInfoID['id']], "Cannot Update Group number", "None", "UPDATE");

    }
    echo json_encode([
        'status' => 'success',
        'message' => 'Group created successfully.'
    ]);
    exit;
}
/**
 * Executes a prepared SQL statement with parameter binding and customizable error handling.
 * 
 * This function serves as a wrapper for database operations, handling SQL preparation, 
 * parameter binding, execution, and result processing.
 *
 * @param string $sql The SQL query to execute
 * @param string $type The types of parameters to bind (e.g., "s" for string, "i" for integer, etc.)
 * @param mixed $params Either a single value or an array of values to bind to the prepared statement
 * @param string $errorMessage Error message to display when no results found (defaults to "No data found")
 * @param string $returnType Controls how results are returned:
 *                          "array" - returns all rows as associative array
 *                          "single" - returns a single row as associative array
 *                          "none" - returns true on success
 *                          "bool" - returns true if any rows found, false otherwise
 * @param string $action SQL operation type: "SELECT", "INSERT", or "UPDATE"
 * @param bool|null $Exit Controls error behavior:
 *                          true - outputs JSON error response and exits
 *                          false - outputs JSON error response and returns false
 *                          null - returns false without JSON response
 * 
 * @return mixed Returns data based on $returnType parameter, or false on failure
 * @throws Exception If database preparation fails
 */
function Query($sql, $type, $params, $errorMessage = "No data found", $returnType = "none", $action = "SELECT", $Exit = true){
    global $_conn;
    $stmt = $_conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Database error: " . $_conn->error);
    }
    
    // Handle both array and individual parameters
    if (is_array($params) && count($params) > 1) {
        // This is a single array of parameters - extract values
        $bindParams = array_values($params);
        $stmt->bind_param($type, ...$bindParams);
    } else {
        // This is either a single value or already the right format
        $stmt->bind_param($type, $params);
    }

    $stmt->execute();
    
    if($action === "SELECT"){
        $result = $stmt->get_result();
        // Only throw exceptions for no results if we're not doing a boolean check
        if($result->num_rows === 0 && $returnType !== "bool" && $returnType !== "none"){
            if (!empty($errorMessage)) {
                if ($Exit) {
                    $response = [
                        'status' => 'warning',
                        'message' => $errorMessage
                    ]; 
                    echo json_encode($response);
                    exit;
                }else if($Exit === null){
                    return false;
                } else {
                    $response = [
                        'status' => 'warning',
                        'message' => $errorMessage
                    ]; 
                    echo json_encode($response);
                    return false;
                }
            }
        }
        if ($returnType === "array") {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $stmt->close();
            return $rows;
        } else if($returnType === "single"){
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row;
        } else if ($returnType === "none") {
            $stmt->close();
            return true;
        } else if ($returnType === "bool"){
            $stmt->close();
            return $result->num_rows > 0;
        } else {
            // Default case if an invalid return type is provided
            $stmt->close();
            return false;
        }
    }
    if($action === "INSERT" || $action === "UPDATE"){
        if ($stmt->affected_rows === 0) {
            if (!empty($errorMessage)) {
                if ($Exit) {
                    $response = [
                        'status' => 'error',
                        'message' => $errorMessage
                    ]; 
                    echo json_encode($response);
                    exit;
                }else if($Exit === null){
                    return false;
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => $errorMessage
                    ]; 
                    echo json_encode($response);
                    return false;
                }
            }
        }
        $stmt->close();
        return true;
    }
}