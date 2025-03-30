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
        $ContactID = $data['ContactID'];
        $MessageType = $data['MessageType'];

        sendUserMessageToServer($message, $ContactID, $MessageType);
        break;

    case "AddUserNewToContactList":
        $Email = $data['Email'];
        AddUserNewToContactList($Email);
        break;

    case "GetContactListForDM":
        GetContactListForDM();
        break;

    case "getMessageInfoForGroup":
        getMessageInfoForGroup($data['GroupID']);
        break;

    case "CheckIfUserIsBlock":
        $ContactID = $data['ContactID'];
        $CheckIfUserIsBlock = CheckIfUserIsBlock($ContactID);
        if($CheckIfUserIsBlock){
            echo json_encode([
                'status' => 'success',
                'message' => $CheckIfUserIsBlock
            ]);
        }else{
            echo json_encode([
                'status' => 'error',
                'message' => 'User is not blocked'
            ]);
        }
        break;

    case 'returnAllMessage':
        returnAllMessage(1);
        break;
    default:
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid case'
        ]);
        break;
}

function returnAllMessage($UserID) {    
    try {
        // Create a simple array to store message counts for the last 7 days
        $weeklyCounts = array_fill(0, 7, 0); // Initialize with zeros for 7 days
        
        // Get dates for the last 7 days (from oldest to newest)
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-$i days"));
        }
        
        // Query for direct message counts by day (last 7 days)
        $directMessageQuery = "
            SELECT 
                DATE(CreatedTime) as message_date,
                COUNT(*) as message_count
            FROM directmessage 
            WHERE (SenderID = ? OR ReceiverID = ?) 
            AND CreatedTime >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(CreatedTime)
        ";
        
        $directMessageCounts = Query($directMessageQuery, "ii", [$UserID, $UserID], "No direct messages found", "array", "SELECT", null);
        
        // Add direct message counts to the weekly counts array
        if (is_array($directMessageCounts) && !empty($directMessageCounts)) {
            foreach ($directMessageCounts as $dayCount) {
                $messageDate = $dayCount['message_date'];
                $dateIndex = array_search($messageDate, $dates);
                if ($dateIndex !== false) {
                    $weeklyCounts[$dateIndex] += (int)$dayCount['message_count'];
                }
            }
        }
        
        // Get all user's groups
        $groupsQuery = "SELECT GroupInfoID FROM groupusers WHERE UserID = ?";
        $userGroups = Query($groupsQuery, "i", $UserID, "No groups found", "array", "SELECT", null);
        
        // Process group messages if any groups exist
        if (is_array($userGroups) && !empty($userGroups)) {
            $groupIds = [];
            
            // Extract all group IDs
            foreach ($userGroups as $group) {
                if (isset($group['GroupInfoID'])) {
                    $groupIds[] = $group['GroupInfoID'];
                }
            }
            
            // If we have any group IDs, query group message counts
            if (!empty($groupIds)) {
                // Build query directly with the group IDs instead of using IN with placeholders
                $groupIdList = implode(',', array_map('intval', $groupIds)); // Ensure IDs are integers for security
                
                $groupMessageQuery = "
                    SELECT 
                        DATE(CreatedTime) as message_date,
                        COUNT(*) as message_count
                    FROM groupchat 
                    WHERE GroupID IN ($groupIdList)
                    AND CreatedTime >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    GROUP BY DATE(CreatedTime)
                ";
                
                // Execute without parameters since we built the query directly
                $groupMessageCounts = Query($groupMessageQuery, "", null, "No group messages found", "array", "SELECT", null);
                
                // Add group message counts to the weekly counts array
                if (is_array($groupMessageCounts) && !empty($groupMessageCounts)) {
                    foreach ($groupMessageCounts as $dayCount) {
                        $messageDate = $dayCount['message_date'];
                        $dateIndex = array_search($messageDate, $dates);
                        if ($dateIndex !== false) {
                            $weeklyCounts[$dateIndex] += (int)$dayCount['message_count'];
                        }
                    }
                }
            }
        }
        
        // Return the simplified array of just the counts
        echo json_encode([
            'status' => 'success',
            'message' => 'Weekly message counts retrieved successfully',
            'data' => $weeklyCounts
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error retrieving message counts: ' . $e->getMessage(),
            'data' => [0, 0, 0, 0, 0, 0, 0]
        ]);
    }
    
    exit;
}

function CheckIfUserIsBlock($ContactID){
    global $user_id;

    // Get FriendID based on ContactID
    $GetFriendID = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $ContactID, "Friend ID not found", "single", "SELECT", null);

    if ($GetFriendID === false) {
        return false; // If no FriendID is found, assume no block
    }

    // Get the user ID of the friend
    $GetOtherUserID = Query("SELECT user_id FROM friends WHERE id = ? AND friend_id = ?", "ii", [$GetFriendID, $user_id], "Friend ID not found", "single", "SELECT", null);

    if ($GetOtherUserID === false) {
        $GetOtherUserID = Query("SELECT friend_id FROM friends WHERE id = ? AND user_id = ?", "ii", [$GetFriendID, $user_id], "Friend ID not found", "single", "SELECT", null);
    }

    // Check if the current user has blocked the other user
    $CheckIFUserAlreadyBlocked = Query("SELECT 1 FROM userblocks WHERE blocker_id = ? AND blocked_id = ?", "ii", [$user_id, $GetOtherUserID], "User not found", "bool", "SELECT", null);

    echo json_encode([
        'status' => 'success',
        'message' => $CheckIFUserAlreadyBlocked
        
    ]);
    exit;
    return $CheckIFUserAlreadyBlocked ? true : false;
}

function getMessageInfoForGroup($groupId){
    global $_conn;

    $message = Query("SELECT * FROM groupchat WHERE GroupID = ?", "i", $groupId, "No Data Found", "array", "SELECT");

    $messages = [];
    foreach ($message as $row) {
        $messages[] = [
            'id' => $row['GroupID'],
            'username' => UsernameFromID($row['user_id']),
            'message' => $row['GroupMessage'],
            'messageType' => $row['GroupMessageType'],
            'timestamp' => $row['CreatedTime']
        ];
    }

    echo json_encode([
        'status' => 'success',
        'message' => $messages
    ]);
    exit;
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
function GetContactListForDM(){
    global $user_id;
    $ContactID = getContactListIdOfUser();
        
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

        $NewFriendID = null;
        
        if ((int)$FriendID['user_id'] === (int)$user_id) {

            $NewFriendID = $FriendID['friend_id'];
        } else {
            $NewFriendID = $FriendID['user_id'];
        }

        // Query user name directly with the SQL query visible in logs
        $sql = "SELECT name FROM users WHERE id = $NewFriendID";
        
        $FriendName = Query("SELECT name FROM users WHERE id = ?", "i", $NewFriendID, "Username Not found", "single", "SELECT", true);
        
        // Log the result of the name query
        if($FriendName){
            $friendData['friendName'] = $FriendName['name'];
        }
        $NewData =[];

        $GetUserChatLog = Query("SELECT * FROM directmessage WHERE FriendID = ? AND ReceiverID = ? ORDER BY DirectMessageID DESC LIMIT 1", "ii", [$relation['FriendID'], $NewFriendID], "No Data Found", "single", "SELECT", null);

        if($GetUserChatLog){
            $friendData['MessageText'] = $GetUserChatLog['MessageText'];
            $friendData['created_at'] = $GetUserChatLog['CreatedTime'];   
        }

        $GetUserChatLog2 = Query("SELECT * FROM directmessage WHERE FriendID = ? AND SenderID = ? ORDER BY DirectMessageID DESC LIMIT 1", "ii", [$relation['FriendID'], $NewFriendID], "No Data Found", "single", "SELECT", null);

        if ($GetUserChatLog2) {
            if($GetUserChatLog['CreatedTime'] < $GetUserChatLog2['CreatedTime']){
                $friendData['MessageText'] = $GetUserChatLog2['MessageText'];
                $friendData['created_at'] = $GetUserChatLog2['CreatedTime'];
            }
        }
        $finalData[] = $friendData;
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

function GetMessageInfoForDM($FriendID){
    global $user_id;
    $ContactID = getContactListIdOfUser();
    $GetUserChatLog = Query("SELECT * FROM directmessage WHERE FriendID = ? ORDER BY DirectMessageID DESC", "i", $FriendID, "No Data Found", "array", "SELECT");
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

function UsernameFromID($userId){
    $row = Query("SELECT name FROM users WHERE id = ?", "i", $userId, "Username not found", "single", "SELECT", true);
    return $row['name'];
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

function CreateContactListForUser($userID){
    global $_conn;
    $ContactListIDForUser = Query("INSERT INTO contactlist (UserID) VALUES (?)", "i", $userID, "Failed to create contact list", "none", "INSERT");
}

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
    if ($type !== "" && $params !== null) {
        if (is_array($params) && count($params) > 1) {
            // This is a single array of parameters - extract values
            $bindParams = array_values($params);
            $stmt->bind_param($type, ...$bindParams);
        } else if ($params !== null) {
            // This is either a single value or already the right format
            $stmt->bind_param($type, $params);
        }
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
?>