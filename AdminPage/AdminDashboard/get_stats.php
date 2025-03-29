<?php
include "../../RegisterLayout/conn.php";

// Update active users calculation - consider users active if they logged in within last 5 minutes
// and their UserStatus is 'Active'
$sql = "SELECT 
    (SELECT COUNT(*) FROM users) as totalUsers,
    (SELECT COUNT(*) FROM users 
     WHERE UserStatus = 'Active' 
     AND last_login >= NOW() - INTERVAL 5 MINUTE) as activeUsers,
    (SELECT COUNT(DISTINCT team_name) FROM team) as totalTeams";

$result = $_conn->query($sql);
$stats = $result->fetch_assoc();

// Recent logins section
$sql = "SELECT name, last_login, UserStatus 
        FROM users 
        WHERE last_login IS NOT NULL
        ORDER BY last_login DESC 
        LIMIT 5";
$result = $_conn->query($sql);
$recentLogins = [];
while ($row = $result->fetch_assoc()) {
    $lastLoginTime = strtotime($row['last_login']);
    // Remove the 5-minute check, just use UserStatus
    $isActive = $row['UserStatus'] === 'Active';
    
    $recentLogins[] = [
        'name' => $row['name'],
        'last_login' => date('Y-m-d H:i:s', $lastLoginTime),
        'status' => $row['UserStatus']
    ];
}

$sql = "SELECT 
        COUNT(CASE WHEN status = 'Incomplete' THEN 1 END) as Incomplete,
        COUNT(CASE WHEN status = 'Complete' THEN 1 END) as Complete,
        COUNT(CASE WHEN start_time < end_time AND status != 'Complete' THEN 1 END) as overdue
        FROM tasks";
$result = $_conn->query($sql);
$taskStats = $result->fetch_assoc();

// Fetch combined recent messages from both groupchat and directmessage
$sql = "
    (SELECT 
        gc.GroupMessage as message_text,
        u.name as sender,
        gc.CreatedTime as sent_at,
        'Group' as message_type,
        gi.GroupName as context
    FROM groupchat gc
    JOIN users u ON gc.user_id = u.id
    JOIN groupinfo gi ON gc.GroupID = gi.id
    ORDER BY gc.CreatedTime DESC
    LIMIT 5)

    UNION ALL
    
    (SELECT 
        dm.MessageText as message_text,
        u.name as sender,
        dm.CreatedTime as sent_at,
        'Direct' as message_type,
        r.name as context
    FROM directmessage dm
    JOIN users u ON dm.SenderID = u.id
    JOIN users r ON dm.ReceiverID = r.id
    ORDER BY dm.CreatedTime DESC
    LIMIT 5)
    
    ORDER BY sent_at DESC
    LIMIT 10";

$recentMessages = $_conn->query($sql);
$messages = [];
while ($row = $recentMessages->fetch_assoc()) {
    $messages[] = $row;
}

// Return all stats as JSON
echo json_encode([
    'totalUsers' => $stats['totalUsers'],
    'activeUsers' => $stats['activeUsers'],
    'totalTeams' => $stats['totalTeams'],
    'taskStats' => $taskStats,
    'recentLogins' => $recentLogins,
    'recentMessages' => $messages
]);
?>