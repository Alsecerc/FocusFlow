<?php
include "../../RegisterLayout/conn.php";

// Count active moderators and administrators
$activeModsQuery = "SELECT COUNT(*) as active_count 
                   FROM users 
                   WHERE (usertype = 1 OR usertype = 2) 
                   AND UserStatus = 'Active'";

$activeModsResult = $_conn->query($activeModsQuery);
$activeMods = $activeModsResult->fetch_assoc()['active_count'];

// Return the count as JSON
header('Content-Type: application/json');
echo json_encode(['active_count' => $activeMods]);