<?php
include "../conn.php";

header("Content-Type: application/json");

$json = file_get_contents("php://input");
$data = json_decode($json, true);
// Test the Query function
// This should return a single row with the value of 1
$user_id = $_COOKIE['UID'];
$friend_id = 20;
switch ($data["case"]) {
    case "checkIftheEmailValidAddtoContact":
        $email = $data['Email'];
        if(empty($email)){
            throw new Exception("Email is required");
        }
        try {
            // get contactID
            $sqlGetContactID = "SELECT ContactID FROM contactlist WHERE UserID = ?";
            $contactID = Query($sqlGetContactID, "i", $user_id, "Contact list not found", "single", "SELECT", true);

            $sqlGetUserIdFromEmail = "SELECT id FROM users WHERE email = ?";
            $userIdFromEmail = Query($sqlGetUserIdFromEmail, "s", $email, "User not found", "single", "SELECT");

            $sqlContactList = "SELECT * FROM friends WHERE user_id = ? AND friend_id = ? AND ContactId = ?";
            $ArrayContactList = [$user_id, $userIdFromEmail, $contactID];
            
            $ContactListExist = Query($sqlContactList, "iii", $ArrayContactList, "Contact does not exist", "bool", "SELECT");

            if ($ContactListExist === true) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Contact already exists'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Contact does not exist'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        break;
    case "TEST":
        echo json_encode([
            'status' => 'success',
            'message' => 'Test successful'
        ]);
        break;

    case "GetTheLastMessageAndTime":
        $ContactListID = Query(
        "SELECT ContactID FROM contactlist WHERE UserID = ?", 
        "i", 
        $user_id, 
        "Contact list not found", 
        "single", 
        "SELECT");

        $GetUserRelationShip = Query(
        "SELECT * FROM friends WHERE ContactId = ?", 
        "i", 
        $ContactListID['ContactID'], 
        "No Data Found", 
        "array", 
        "SELECT");
        $finalData = [];
        foreach ($GetUserRelationShip as $relation) {
            $friendData = [
                'ID' => $relation['id'],
                'friend_id' => $relation['friend_id'],
                'friendName' => "None",
                'created_at' => $relation['created_at'],
                'MessageText' => "Enter Something Here"
            ];
            $FriendName = Query(
            "SELECT name FROM users WHERE id = ?", 
            "i", 
            $relation['friend_id'], 
            "Username Not found", 
            "single", 
            "SELECT", 
            true);

            if($FriendName){
                $friendData['friendName'] = $FriendName['name'];
            }

            $param = [$ContactListID['ContactID'], $relation['friend_id']];
            $GetUserChatLog = Query(
            "SELECT * FROM directmessage WHERE ContactListID = ? AND FriendID = ? ORDER BY DirectMessageID DESC LIMIT 1", 
            "ii", 
            $param, 
            "No Data Found", 
            "single", 
            "SELECT", 
            null);

            if(!empty($GetUserChatLog)){
                // $chatLog = is_array($GetUserChatLog) && isset($GetUserChatLog[0])? $GetUserChatLog[0]: $GetUserChatLog;
                $friendData['MessageText'] = $GetUserChatLog['MessageText'];
                $friendData['created_at'] = $GetUserChatLog['CreatedTime'];
            }

            $finalData[] = $friendData;
        }

        echo json_encode([
            'status' => 'success',
            'message' => $finalData
        ]);
        exit;
    
    case "createGroup":
        $groupname = $data['name'];
        $groupDesc = $data['description'];
        $groupmembers = $data['members'];
        $ROLE = $data['role'];
        createGroup($groupname, $groupDesc, $groupmembers, $ROLE);
    case "getGroupData":
        getGroupData($user_id);
        break;

    case "sendAddContact":
        $email = $data['Email'];
        if(empty($email)){
            throw new Exception("Email is required");
        }
        AddUserNewToContactList($email);
        break;

    case "VerifyDMContactListExist":
        VerifyDMContactListExist($user_id);
        break;
    case "checkIfFriendInFriendContactList":
        $Email = $data['Email'];
        checkIfFriendInFriendContactList($Email);
        break;

    case "GetOnlyOneContactListForDM":
        $FriendEmail = $data['Email'];
        GetOnlyOneContactListForDM($FriendEmail);
        break;

    case "GetMessageInfoForDM":
        $FriendID = $data['FriendID'];
        GetMessageInfoForDM($FriendID);
        break;
    
    case "sendUserMessageToServer":
        $message = $data['message'];
        $FriendID = $data['FriendID'];
        $MessageType = $data['messageType'];

        sendUserMessageToServer($message, $FriendID, $MessageType);
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid case'
        ]);
        break;
}

function sendUserMessageToServer($message, $FriendID, $MessageType){
    global $user_id;
    $ContactID = getContactListIdOfUser();
    $FriendUserID = Query("SELECT friend_id FROM friends WHERE id = ? AND user_id = ? AND ContactId = ?", "iii", [$FriendID, $user_id,$ContactID], "Friend ID not found", "single", "SELECT", true);

    // echo json_encode([
    //     'status' => 'success',
    //     'message' => $FriendUserID
    // ]);
    // exit;
    $SendUserMessage = Query("INSERT INTO directmessage (ContactListID, SenderID, ReceiverID, MessageText, MessageType, FriendID) VALUES (?, ?, ?, ?, ?, ?)", "iiissi", [$ContactID, $user_id, $FriendUserID['friend_id'], $message, $MessageType, $FriendID], "Failed to send message", "none", "INSERT");
    if($SendUserMessage){
        echo json_encode([
            'status' => 'success',
            'message' => 'Message sent successfully'
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to send message'
        ]);
        exit;
    }
}

function GetMessageInfoForDM($FriendID){
    global $user_id;
    $ContactID = getContactListIdOfUser();
    $param = [$ContactID, $FriendID];
    $GetUserChatLog = Query("SELECT * FROM directmessage WHERE ContactListID = ? AND FriendID = ? ORDER BY DirectMessageID DESC", "ii", $param, "No Data Found", "array", "SELECT");
    $finalData = [];
    foreach ($GetUserChatLog as $value) {
        $finalData[] = [
            'ID' => $value['DirectMessageID'],
            'FriendID' => $value['FriendID'],
            'SenderID' => $value['SenderID'],
            'ReceiverID' => $value['ReceiverID'],
            'MessageText' => $value['MessageText'],
            'CreatedTime' => $value['CreatedTime'],
            'CurrentUserName'=> UsernameFromID($value['SenderID']),
            'type' => $value['MessageType']
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
    $GetOneUserInfoFromFriend = Query("SELECT * FROM friends WHERE ContactId = ? AND friend_id = ? AND user_id = ?", "iii", [$ContactID, $FriendID, $user_id], "No Data Found", "single", "SELECT");
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
    $param = [$ContactID, $GetOneUserInfoFromFriend['friend_id']];
    $GetUserChatLog = Query("SELECT * FROM directmessage WHERE ContactListID = ? AND FriendID = ? ORDER BY DirectMessageID DESC LIMIT 1", "ii", $param, "No Data Found", "single", "SELECT", null);
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


function getContactListIdOfUser (){
    global $_conn;
    $userID = $_COOKIE['UID'];
    $sql = "SELECT ContactID FROM contactlist WHERE UserID = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    if ($stmt->affected_rows === 0) {
        $response = [
            'status' => 'error',
            'message' => 'Contact list not found'
        ];
        echo json_encode($response);
        exit;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['ContactID'];
}

function checkIfFriendInFriendContactList($Email){
    global $user_id;
    // get contactID
    $sqlGetContactID = "SELECT ContactID FROM contactlist WHERE UserID = ?";
    $contactID = Query($sqlGetContactID, "i", $user_id, "Contact list not found", "single", "SELECT", true);

    $sqlGetUserIdFromEmail = "SELECT id FROM users WHERE email = ?";
    $userIdFromEmail = Query($sqlGetUserIdFromEmail, "s", $Email, "User not found", "single", "SELECT");

    $CheckIfFriendExistInFriendContactList = "SELECT * FROM friends WHERE user_id = ? AND friend_id = ? AND ContactId = ?";
    $ArrayFriendExist = [$user_id, $userIdFromEmail['id'], $contactID['ContactID']];

    $FriendExist = Query($CheckIfFriendExistInFriendContactList, "iii", $ArrayFriendExist, "Friend does not exist", "bool", "SELECT");

    if (!$FriendExist) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Friend does not exist in friend table'
        ]);
        exit;
    }

    $sqlContactList = "SELECT * FROM contact WHERE user_id = ? AND contact_id = ? AND ContactListID = ?";
    $ArrayContactList = [$user_id, $userIdFromEmail, $contactID];
    
    $ContactListExist = Query($sqlContactList, "iii", $ArrayContactList, "Contact does not exist", "bool", "SELECT");

    if ($ContactListExist) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Friend already exists in Contact'
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

function AddUserNewToContactList($Email){
    global $user_id, $_conn;

    $GetFriendID = "SELECT id FROM users WHERE email = ?";
    $UserFriendID = Query($GetFriendID, "s", $Email, "Friend id not found", "single", "SELECT", true);

    $sqlGetContactID = "SELECT ContactID FROM contactlist WHERE UserID = ?";
    $contactListID = Query($sqlGetContactID, "i", $user_id, "Contact list not found", "single", "SELECT", true);

    if(!$contactListID){
        echo json_encode([
            'status' => 'error',
            'message' => 'Contact list not found'
        ]);
        exit;
    }

    $AddUserIntoFriends = "INSERT INTO friends (user_id, friend_id, ContactId) VALUES (?, ?, ?)";
    $AddUserIntoFriendsParams = [$user_id, $UserFriendID['id'], $contactListID['ContactID']];
    $AddUserIntoFriendsResult = Query($AddUserIntoFriends, "iii", $AddUserIntoFriendsParams, "Failed to add user to contact list", "none", "INSERT");
    if(!$AddUserIntoFriendsResult){
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add user to friend list'
        ]);
        exit;
    }

    $SelectFriendID = "SELECT id FROM friends WHERE user_id = ? AND friend_id = ? AND ContactId = ?";
    $SelectFriendIDParams = [$user_id, $UserFriendID['id'], $contactListID['ContactID']];
    $FriendID = Query($SelectFriendID, "iii", $SelectFriendIDParams, "Friend ID not found", "single", "SELECT", true);

    $AddUserToContactList = "INSERT INTO contact (user_id, contact_id, ContactListID, FriendID) VALUES (?, ?, ?, ?)";
    $AddUserToContactListParams = [$user_id, $UserFriendID['id'], $contactListID['ContactID'], $FriendID['id']];
    $AddUserToContactListResult = Query($AddUserToContactList, "iiii", $AddUserToContactListParams, "Failed to add user to contact list", "none", "INSERT");
    if($AddUserToContactListResult){
        echo json_encode([
            'status' => 'success',
            'message' => 'User added to contact list successfully'
        ]);
        exit;
    }
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

function getGroupData($userId) {
    $GetGroupInfoID = Query("SELECT * FROM groupusers WHERE UserID = ?", "i", $userId, "No Group found?", "array", "SELECT");
    
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