<?php
include '../conn.php';
$user_id = $_COOKIE['UID'];

// Complete block feature
// add friend feature
// remove friend feature
// user able to add member though the user contact list
// add security for website

// want to know who is blocking who

function UnblockUser($ContactID){
    global $user_id;

    // Get the FriendID from the contact
    $GetFriendID = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $ContactID, "Friend ID not found", "single", "SELECT", true);

    if (!$GetFriendID) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Contact not found'
        ]);
        exit;
    }

    // Get the other user's ID from the friendship record
    $friendRecord = Query("SELECT * FROM friends WHERE id = ?", "i", $GetFriendID['FriendID'], "Friend record not found", "single", "SELECT", null);

    $FriendID = null;
    if ($friendRecord) {
        if((int)$friendRecord['user_id'] === (int)$user_id) {
            $FriendID = $friendRecord['friend_id'];
        } else {
            $FriendID = $friendRecord['user_id'];
        }
    }

    // Remove the block record - fix the parameter reference
    $UnblockUser = Query("DELETE FROM userblocks WHERE blocker_id = ? AND blocked_id = ?", "ii", [$user_id, $FriendID], "Failed to unblock user", "none", "DELETE");
    
    if($UnblockUser){
        echo json_encode([
            'status' => 'success',
            'message' => 'User unblocked successfully'
        ]);
        exit;
    }
    // Update the friendship status - fix the parameter reference
    $ChangeStatus = Query("UPDATE friends SET Status = 'None' WHERE id = ?", "i", [$GetFriendID['FriendID']], "Failed to change status", "none", "UPDATE");

    if(!$UnblockUser && !$ChangeStatus){
        echo json_encode([
            'status' => 'failed',
            'message' => 'Failed to unblock user'
        ]);
    }
    exit;
}

function CheckIfStatusisFriend($ContactID){
    global $user_id;

    $GetFriendID = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $ContactID, "Friend ID not found", "single", "SELECT", true);

    $GetFriendStatus = Query("SELECT Status FROM friends WHERE id = ?", "i", $GetFriendID, "Friend ID not found", "single", "SELECT", null);

    if($GetFriendStatus['Status'] == 'Accepted' || $GetFriendStatus['Status'] == 'Blocked'){
        return true;
    }else{
        return false;
    }
}
function CheckIfUserIsBlock($ContactID){
    global $user_id;

    // Get FriendID based on ContactID
    $GetFriendID = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $ContactID, "Friend ID not found", "single", "SELECT", null);

    // if ($GetFriendID === false) {
    //     return false; // If no FriendID is found, assume no block
    // }

    // Get the user ID of the friend
    $GetOtherUserID = Query(    
        "SELECT 
        CASE 
            WHEN user_id = ? THEN friend_id
            WHEN friend_id = ? THEN user_id
            ELSE NULL
        END AS other_user
     FROM friends 
     WHERE id = ?",
    "iii",
    [$user_id, $user_id, $GetFriendID['FriendID']],
    "Friend not found",
    "single",
    "SELECT",
    null
    );

    // Check if the current user has blocked the other user
    $CheckIFUserAlreadyBlocked = Query(
    "SELECT * FROM userblocks 
    WHERE blocker_id = ? AND blocked_id = ?", 
    "ii", 
    [$user_id, $GetOtherUserID['other_user']], 
    "User not found", "bool", 
    "SELECT", 
    null);

    // Return a boolean result instead of a response object
    return $CheckIFUserAlreadyBlocked;
}

function BlockUser($ContactID){
    global $user_id;

    $GetUserFriendID = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $ContactID, "Friend ID not found", "single", "SELECT", null);

    $UserFriendID = Query("SELECT * FROM friends WHERE id = ?", "i", $GetUserFriendID, "Friend ID not found", "single", "SELECT", null);

    if ($UserFriendID) {
        if((int)$UserFriendID['user_id'] === (int)$user_id) {
            $ContactID = $UserFriendID['friend_id'];
        } else {
            $ContactID = $UserFriendID['user_id'];
        }
    }

    $BlockUser = Query("INSERT INTO userblocks (blocker_id, blocked_id) VALUES (?, ?)", "ii", [$user_id, $UserFriendID], "Failed to block user", "none", "INSERT");

    $CheckIFUserAlreadyBlocked = Query("SELECT * FROM friends WHERE id = ? AND status = 'Blocked'", "i", $ContactID, "User not found", "bool", "SELECT", null);
    if($CheckIFUserAlreadyBlocked){
        echo json_encode([
            'status' => 'error',
            'message' => 'User already blocked'
        ]);
        exit;
    }

    $GetFriendIDFromContact = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $ContactID, "Friend ID not found", "single", "SELECT", true);

    $BlockUser = Query("UPDATE friends SET status = 'Blocked' WHERE id = ?", "i", $GetFriendIDFromContact, "Failed to block user", "none", "UPDATE");

    if($BlockUser){
        echo json_encode([
            'status' => 'success',
            'message' => 'User blocked successfully'
        ]);
        exit;
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to block user'
        ]);
        exit;
    }
}
function LeaveGroup($GroupID){
    global $user_id;

    try {
        // First check if user is in the group
        $userInGroup = Query("SELECT * FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", "ii", [$GroupID, $user_id], "User not in group", "bool", "SELECT", null);
        
        if (!$userInGroup) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'You are not a member of this group'
            ]);
            exit;
        }

        // Check if user is an admin
        $isAdmin = Query("SELECT GroupRole FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", "ii", [$GroupID, $user_id], "Role not found", "single", "SELECT", null);

        $NumberOfPeopleInGroup = Query("SELECT COUNT(*) as count FROM groupusers WHERE GroupInfoID = ?", "i", $GroupID, "Count failed", "single", "SELECT", null);

        if ($NumberOfPeopleInGroup['count'] == 1) {

            // Clear messages and delete the group
            Query("DELETE FROM groupchat WHERE GroupID = ?", "i", [$GroupID], "Failed to delete group messages", "none", "DELETE");
            Query("DELETE FROM groupusers WHERE GroupInfoID = ?", "i", [$GroupID], "Failed to delete group members", "none", "DELETE");
            Query("DELETE FROM groupinfo WHERE id = ?", "i", [$GroupID], "Failed to delete group", "none", "DELETE");
            echo json_encode([
                'status' => 'success',
                'message' => 'Group has been deleted because only one people in the group leave'
            ]);
            exit;
        }
                       
        if ($isAdmin && $isAdmin['GroupRole'] === 'ADMIN') {
            // Count other admins
            $otherAdmins = Query("SELECT COUNT(*) as count FROM groupusers WHERE GroupInfoID = ? AND UserID != ? AND GroupRole = 'ADMIN'", "ii", [$GroupID, $user_id], "Count failed", "single", "SELECT", null);
            
            if ($otherAdmins && $otherAdmins['count'] == 0) {
                // Get the longest-staying member
                $UserStayLongestTime = Query("SELECT UserID FROM groupusers WHERE GroupInfoID = ? ORDER BY CreatedTime ASC LIMIT 1", "i", $GroupID, "User not found", "single", "SELECT", null);

                // Get the next eligible admin
                $newAdmin = Query("SELECT UserID FROM groupusers WHERE GroupInfoID = ? AND UserID != ? ORDER BY CreatedTime ASC LIMIT 1;", "ii", [$GroupID, $user_id], "User not found", "single", "SELECT", null);

                if ($newAdmin && isset($newAdmin['UserID'])) {
                    // Promote the new admin
                    Query("UPDATE groupusers SET GroupRole = 'ADMIN' WHERE UserID = ? AND GroupInfoID = ?", 
                        "ii", [$newAdmin['UserID'], $GroupID], "Failed to promote new admin", "none", "UPDATE");
                    // user leave group after the admin been set
                    Query("DELETE FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", "ii", [$GroupID, $user_id], "Failed to leave group", "none", "DELETE");
                } 
                else {
                    // Clear messages and delete the group
                    Query("DELETE FROM groupmchat WHERE GroupInfoID = ?", "i", [$GroupID], "Failed to delete group messages", "none", "DELETE");
                    Query("DELETE FROM groupusers WHERE GroupInfoID = ?", "i", [$GroupID], "Failed to delete group members", "none", "DELETE");
                    Query("DELETE FROM groupinfo WHERE id = ?", "i", [$GroupID], "Failed to delete group", "none", "DELETE");
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Group has been deleted'
                    ]);
                    exit;
                }
            }
            // more than 1 admin user leave group
            Query("DELETE FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", "ii", [$GroupID, $user_id], "Failed to leave group", "none", "DELETE");
        } 
        else {
            // Remove user from group
            Query("DELETE FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", "ii", [$GroupID, $user_id], "Failed to leave group", "none", "DELETE");

            // Update member count
            Query("UPDATE groupinfo SET GroupMemberNo = (SELECT COUNT(*) FROM groupusers WHERE GroupInfoID = ?) WHERE id = ?", 
                "ii", [$GroupID, $GroupID], "Failed to update member count", "none", "UPDATE");

            // Check if the group is empty after removal
            $remainingMembers = Query("SELECT COUNT(*) as count FROM groupusers WHERE GroupInfoID = ?", 
                "i", [$GroupID], "Count failed", "single", "SELECT", null);

            if ($remainingMembers['count'] == 0) {
                Query("DELETE FROM groupinfo WHERE id = ?", "i", [$GroupID], "Failed to delete group", "none", "DELETE");
            }
        }

        // Always send a proper JSON response
        echo json_encode([
            'status' => 'success',
            'message' => 'User left group successfully'
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error leaving group: ' . $e->getMessage()
        ]);
    }
    exit;
}

function AddGroupMembers($GroupEmail, $GroupID){
    $AddGroupMembers = $GroupEmail;
    $GroupMembers = [];
    $existingMembers = [];
    
    // First, collect all valid user IDs from emails without exiting
    foreach ($AddGroupMembers as $value) {
        $emailTrimmed = trim($value);
        // Get user ID from email
        $userResult = Query("SELECT id FROM users WHERE email = ?", "s", $emailTrimmed, "Email not found", "single", "SELECT", null);
        
        if (!$userResult || !isset($userResult['id'])) {
            continue; // Skip invalid emails
        }
        
        $userID = $userResult['id'];
        
        // Check if user exists in group
        $existsInGroup = Query("SELECT * FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", 
                              "ii", [$GroupID, $userID], "User not in group", "bool", "SELECT", null);
        
        if ($existsInGroup) {
            $existingMembers[] = $emailTrimmed;
        } else {
            $GroupMembers[] = $userID;
        }
    }
    
    // If all users are already in the group
    if (empty($GroupMembers) && !empty($existingMembers)) {
        echo json_encode([
            'status' => 'info',
            'message' => 'All specified users are already in the group',
            'existingMembers' => $existingMembers
        ]);
        exit;
    }
    
    $finalData = [];
    foreach ($GroupMembers as $userID) {
        $AddUserToGroup = Query("INSERT INTO groupusers (GroupInfoID, UserID, GroupRole) VALUES (?, ?, ?)", 
                              "iis", [$GroupID, $userID, 'MEMBER'], "Failed to add user to group", "none", "INSERT");
        if($AddUserToGroup){
            $finalData[] = [
                'UserID' => $userID,
                'status' => 'success',
                'message' => 'User added to group successfully'
            ];
        } else {
            $finalData[] = [
                'UserID' => $userID,
                'status' => 'error',
                'message' => 'Failed to add user to group'
            ];
        }
    }
    
    // Update the group member count
    Query("UPDATE groupinfo SET GroupMemberNo = (SELECT COUNT(*) FROM groupusers WHERE GroupInfoID = ?) WHERE id = ?", 
         "ii", [$GroupID, $GroupID], "Failed to update group member count", "none", "UPDATE");
    
    echo json_encode([
        'status' => 'success',
        'message' => $finalData,
        'existingMembers' => $existingMembers
    ]);
    exit;
}

function ToggleMuteGroup($GroupID, $Status){
    global $user_id;
    if(empty($GroupID) || empty($Status)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Group ID and Status are required'
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
            'message' => 'You must be an admin to change group status'
        ]);
        exit;
    }
    
    // Update group status
    $updateResult = Query("UPDATE groupinfo SET GroupStatus = ? WHERE id = ?", 
                          "si", [$Status, $GroupID], "Failed to update group status", "none", "UPDATE");
    
    if($updateResult) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Group status updated successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update group status'
        ]);
        
    }
    exit;
    
}

function updateGroupInfo($GroupID, $GroupName, $GroupDesc){
    // $NewGroupName = ()
    $UpdateGroup = Query("UPDATE groupinfo SET GroupName = ?, GroupDesc = ? WHERE id = ?", "ssi", [$GroupName, $GroupDesc, $GroupID], "Failed to update group info", "none", "UPDATE");
    if($UpdateGroup){
        echo json_encode([
            'status' => 'success',
            'message' => 'Group info updated successfully'
        ]);
        exit;
    }
}

function getGroupInfo($groupId) {
    $GroupInfo = Query("SELECT * FROM groupinfo WHERE id = ?", "i", $groupId, "No Group found", "single", "SELECT", true);
    // Ensure GroupStatus is included in the return value
    if ($GroupInfo && !isset($GroupInfo['GroupStatus'])) {
        $GroupInfo['GroupStatus'] = 'Active'; // Default status if not set
    }
    return $GroupInfo;
}

function GetGroupMembers($groupId) {
    global $user_id;
    
    // Get all active members in the group
    $activeMembers = Query(
        "SELECT gu.*, u.name, u.UserStatus, u.email FROM groupusers gu 
         JOIN users u ON gu.UserID = u.id 
         WHERE gu.GroupInfoID = ?", 
        "i", 
        $groupId, 
        "No members found", 
        "array", 
        "SELECT"
    );
    
    // Get the banned members (who aren't in the group anymore)
    $bannedMembers = Query(
        "SELECT gb.UserID, u.name 
         FROM groupbannedusers gb 
         JOIN users u ON gb.UserID = u.id 
         WHERE gb.GroupID = ?",
        "i",
        $groupId,
        "No banned members",
        "array",
        "SELECT",
        null
    );
    
    // Combine active and banned members into a single array
    $allMembers = [];
    
    // Process active members
    if (!empty($activeMembers)) {
        foreach ($activeMembers as $member) {
            $allMembers[] = [
                'id' => $member['UserID'],
                'name' => $member['name'],
                'role' => $member['GroupRole'],
                'status' => $member['UserStatus'] ?: 'Active',
                'email' => $member['email']
            ];
        }
    }
    
    // Add banned members
    if (!empty($bannedMembers)) {
        foreach ($bannedMembers as $member) {
            $allMembers[] = [
                'id' => $member['UserID'],
                'name' => $member['name'],
                'role' => 'MEMBER', // Banned members default to MEMBER role
                'status' => 'Banned'
            ];
        }
    }
    
    return $allMembers;
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
    $GetOneUserInfoFromFriend = Query("SELECT * FROM friends WHERE friend_id = ? AND user_id = ?", "ii", [$FriendID, $user_id], "No Data Found", "single", "SELECT", null);

    if (!$GetOneUserInfoFromFriend) {
        $GetOneUserInfoFromFriend = Query("SELECT * FROM friends WHERE user_id = ? AND friend_id = ?", "ii", [$FriendID, $user_id], "No Data Found", "single", "SELECT", true);
    }

    $finalData = [];

    $friendData = [
        'ID' => $GetOneUserInfoFromFriend['id'],
        'friend_id' => $FriendID,
        'friendName' => "None",
        'created_at' => $GetOneUserInfoFromFriend['created_at'],
        'MessageText' => "Enter Something Here"
    ];
    $FriendName = Query("SELECT * FROM users WHERE id = ?", "i", $FriendID, "Username Not found", "single", "SELECT", true);
    if($FriendName){
        $friendData['friendName'] = $FriendName['name'];
    }
    $param = $FriendID;
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

    $message = Query("SELECT * FROM groupchat WHERE GroupID = ? ORDER BY CreatedTime ASC", "i", $groupId, "No Data Found", "array", "SELECT");

    // If no messages found, return an empty array with success status
    if (empty($message)) {
        echo json_encode([
            'status' => 'success',
            'message' => []
        ]);
        exit;
    }

    $GroupMemberID = Query("SELECT * FROM groupusers WHERE GroupInfoID = ?", "i", $groupId, "No Data Found", "array", "SELECT");

    $messages = [];
    foreach ($message as $row) {
        $messages[] = [
            'id' => $GroupMemberID['GroupID']?? "No Group ID",
            'username' => UsernameFromID($row['user_id']),
            'message' => $row['GroupMessage'],
            'messageType' => $row['GroupMessageType'],
            'timestamp' => $row['CreatedTime']
        ];
    }

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
            'GroupID' => $GroupInfo['id'] ?? 'No Group ID',
            'GroupName' => $GroupInfo['GroupName'] ?? 'Unknown',
            'GroupRole' => $value['GroupRole'] ?? 'Member',
            'GroupDesc' => $GroupInfo['GroupDesc'] ?? 'No Description',
            'GroupMessageSender' => $GroupMessages && isset($GroupMessages['user_id']) ? UsernameFromID($GroupMessages['user_id']) : "No Sender",
            'GroupMessages' => $GroupMessages ? $GroupMessages['GroupMessage'] : "Enter Something Here",
            'GroupMessageType' => $GroupMessages['GroupMessageType'] ?? "text",
            'GroupMessageTime' => $GroupMessages ? $GroupMessages['CreatedTime'] : $GroupInfo['GroupCreatedTime'],
            'GroupStatus' => $GroupInfo['GroupStatus'] ?? 'Active' // Add group status
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
    global $user_id;
        // Use the UserID from the request, or fallback to the cookie
        $userId = $user_id;

        $groupId = $messageData['GroupID'];
        $message = $messageData['message'];
        $messageType = $messageData['messageType'] ?? 'TEXT';

        $CheckUserStatusInGroup = Query("SELECT GroupUserStatus FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", "ii", [$groupId, $userId], "User status not found", "single", "SELECT", null);

        if ($CheckUserStatusInGroup && isset($CheckUserStatusInGroup['GroupUserStatus']) && $CheckUserStatusInGroup['GroupUserStatus'] === 'Muted') {
            throw new Exception('You are muted in this group');
        }

        // Check if user is in the group
        $userInGroup = Query("SELECT * FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", 
                           "ii", [$groupId, $userId], "User not in group", "bool", "SELECT", null);
        
        if (!$userInGroup) {
            throw new Exception('You are not a member of this group');
        }

        // Check user status in group first
        $userStatusInGroup = Query("SELECT GroupUserStatus FROM groupusers WHERE GroupInfoID = ? AND UserID = ?", 
                                  "ii", [$groupId, $userId], "User status not found", "single", "SELECT", null);

        if ($userStatusInGroup && isset($userStatusInGroup['GroupUserStatus']) && $userStatusInGroup['GroupUserStatus'] === 'Muted') {
            throw new Exception('You are muted in this group');
        }

        // Check if the group is muted before sending message
        $groupInfo = Query("SELECT GroupStatus FROM groupinfo WHERE id = ?", 
                         "i", $groupId, "Group not found", "single", "SELECT", null);
        
        if ($groupInfo && isset($groupInfo['GroupStatus']) && $groupInfo['GroupStatus'] === 'Muted') {
            throw new Exception('This group is muted. Messages cannot be sent');
        }
                               
        $actualGroupId = $groupId; // Default to the provided GroupID
        
        // Insert the message
        $insertQuery = "INSERT INTO groupchat (GroupID, user_id, GroupMessage, GroupMessageType) VALUES (?, ?, ?, ?)";
        Query($insertQuery, "iiss", [$actualGroupId, $userId, $message, $messageType], "Failed to send message", "none", "INSERT");

        echo json_encode([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => [
                'groupId' => $groupId,
                'userId' => $userId,
                'message' => $message,
                'messageType' => $messageType,
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);

    exit;
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

            $InsertGroupUser = Query("INSERT INTO groupusers (GroupInfoID, GroupRole, UserID) VALUES (?, ?, ?)", "isi", [$GroupInfoID['id'], $ROLE, $GetGroupMemberId['id']], "Failed to add user to group", "none","INSERT");
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

    // Get Group contact information
    $GetGroupContactID = Query("SELECT * FROM groupinfo WHERE GroupName = ?", 
    "s", 
    $uniqueGroupName, 
    "Contact list not found", 
    "single", 
    "SELECT", 
    true);

    $StripedGroupName = preg_replace('/_\d+$/', '', $uniqueGroupName);
    $NewData = [
        "id" => $GetGroupContactID['id'] ?? 'No Group ID',
        "name" => $StripedGroupName,
        "GroupDesc" => $groupDesc,
        "time" => $GetGroupContactID['GroupCreatedTime'] ?? 'No Created Time',
        "GroupMemberNo" => $GetGroupMemberNo['GroupMemberNo'] ?? 'No Member Number',
        "message" => "Enter Something Here",
    ];

    echo json_encode([
        'status' => 'success',
        'message' => $NewData
    ]);
    exit;
}

/**
 * Executes a prepared SQL statement with parameter binding and error handling.
 *
 * @param string $sql SQL query to execute
 * @param string $type Parameter types (e.g., "s" for string, "i" for integer)
 * @param mixed $params Single value or array of values to bind
 * @param string $errorMessage Error message if no results (default: "No data found")
 * @param string $returnType Return format: "array", "single", "none", or "bool"
 * @param string $action SQL operation type: "SELECT", "INSERT", "UPDATE"
 * @param bool|null $Exit Error behavior: true (exit on error), false (return JSON error), null (return false)
 * 
 * @return mixed Query result based on $returnType, or false on failure
 * @throws Exception If query preparation fails
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
    if($action === "INSERT" || $action === "UPDATE" || $action === "DELETE"){
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

/**
 * Gets the online/offline status of a user
 * 
 * @param int $contactID The contact ID to check status for
 * @return void This function sends a JSON response
 */
function getUserStatus($contactID) {
    global $_conn, $user_id;
    
    // First get the friend ID from the contact
    $contactInfo = Query("SELECT FriendID FROM contact WHERE id = ?", "i", $contactID, "Contact not found", "single", "SELECT", null);
    
    if (!$contactInfo) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Contact not found'
        ]);
        exit;
    }
    
    // Get the friend record
    $friendRecord = Query("SELECT * FROM friends WHERE id = ?", "i", $contactInfo['FriendID'], "Friend record not found", "single", "SELECT", null);
    
    if (!$friendRecord) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Friend record not found'
        ]);
        exit;
    }
    
    // Figure out which ID is the friend (not the current user)
    $friendUserID = ($friendRecord['user_id'] == $user_id) ? $friendRecord['friend_id'] : $friendRecord['user_id'];
    
    // Check if user is online - this depends on your implementation
    // For example, you might have a last_seen timestamp column or an is_online flag
    // Here's a simple implementation that considers a user online if they've been active in the last 5 minutes
    $userStatus = Query("SELECT last_login FROM users WHERE id = ?", "i", $friendUserID, "User not found", "single", "SELECT", null);
    
    if ($userStatus && isset($userStatus['last_login'])) {
        $lastLoginTime = strtotime($userStatus['last_login']);
        $currentTime = time();
        $isOnline = ($currentTime - $lastLoginTime) < 300; // Online if active in last 5 minutes
        
        echo json_encode([
            'status' => 'success',
            'message' => $isOnline ? 'Online' : 'Offline'
        ]);
    } else {
        // If no last_login data, default to offline
        echo json_encode([
            'status' => 'success',
            'message' => 'Offline'
        ]);
    }
    exit;
}

function DeleteGroup($groupId) {
    global $_conn, $user_id;
    
    try {
        // Check if user is admin of this group
        $isAdmin = Query("SELECT * FROM groupusers WHERE GroupInfoID = ? AND UserID = ? AND GroupRole = 'ADMIN'", 
                        "ii", [$groupId, $user_id], "Not an admin", "bool", "SELECT");
        
        if(!$isAdmin) {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'You must be an admin to delete this group'
            ]);
            exit;
        }
        
        // Start a transaction to ensure all operations succeed or fail together
        $_conn->begin_transaction();
        
        // 1. Get the foreign key constraints
        $checkForeignKeys = "SHOW CREATE TABLE groupchat";
        $stmt = $_conn->prepare($checkForeignKeys);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $createTable = $row['Create Table'];
        $stmt->close();
        
        // 1. First delete all messages in the group
        $deleteMessages = "DELETE FROM groupchat WHERE GroupID = ?";
        $stmt = $_conn->prepare($deleteMessages);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $stmt->close();
        
        // 2. Delete all members from the group
        $deleteMembers = "DELETE FROM groupusers WHERE GroupInfoID = ?";
        $stmt = $_conn->prepare($deleteMembers);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $stmt->close();
        
        // 3. Delete any banned users entries for this group
        $deleteBanned = "DELETE FROM groupbannedusers WHERE GroupID = ?";
        $stmt = $_conn->prepare($deleteBanned);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $stmt->close();
        
        // 4. Finally delete the group itself
        $deleteGroup = "DELETE FROM groupinfo WHERE id = ?";
        $stmt = $_conn->prepare($deleteGroup);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $stmt->close();
        
        // If everything succeeded, commit the transaction
        $_conn->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Group deleted successfully'
        ]);
    } catch (Exception $e) {
        // If anything fails, roll back all changes
        $_conn->rollback();
        
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Error deleting group: ' . $e->getMessage()
        ]);
    }
    
    exit;
}

/**
 * Check if the current user is blocked by the contact owner
 * @param int $contactID The contact ID to check
 * @return bool True if the current user is blocked by the contact owner, false otherwise
 */
function CheckIfUserIsBlockedBy($contactID) {
    global $user_id;
    
    // Check if user_id is available
    if (empty($user_id)) {
        return false;
    }
    
    // Get the FriendID from this contact
    $friendRecord = Query(
        "SELECT FriendID FROM contact WHERE id = ?","i",$contactID,"Contact not found","single","SELECT",null);
    $UserFriendID = null;
        if ($friendRecord){
            $GetFriendUserID = Query("SELECT * FROM friends WHERE id = ?", "i", $friendRecord, "Friend ID not found", "single", "SELECT", null);

            if ($GetFriendUserID) {
                // Check if the current user is blocked by the contact owner
                $UserFriendID = ($GetFriendUserID['user_id'] == $user_id) ? $GetFriendUserID['friend_id'] : $GetFriendUserID['user_id'];
            }
        }

    if ($UserFriendID) {
        // Check if the current user is blocked by the contact owner
        $blockedRecord = Query(
            "SELECT * FROM userblocks WHERE blocked_id = ? AND blocker_id = ?",
            "ii", [$user_id, $UserFriendID], "Blocked record not found", "bool", "SELECT", null);
        
        if ($blockedRecord) {
            return true; // User is blocked
        }else{
            return false; // User is not blocked
        }
    }
    
    // Check if the current user is blocked by the contact owner
    
    return false;
}