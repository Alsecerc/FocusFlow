<?php
session_start();
include "../../RegisterLayout/conn.php";  // Fix connection path

if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    
    // Only update last_login time without changing status
    $sql = "UPDATE users 
            SET last_login = NOW() 
            WHERE id = ?";
    
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

// Update inactive users first
$updateSql = "UPDATE users 
              SET UserStatus = 'Inactive' 
              WHERE last_login < NOW() - INTERVAL 5 MINUTE 
              AND UserStatus = 'Active'
              AND suspension_end IS NULL";
$_conn->query($updateSql);

// Only check for expired suspensions
$updateSql = "UPDATE users 
              SET UserStatus = 'Active',
                  suspension_end = NULL,
                  suspension_reason = NULL 
              WHERE UserStatus = 'Suspended' 
              AND suspension_end <= NOW()";
$_conn->query($updateSql);

// Get all user statuses
$sql = "SELECT id, name, UserStatus, last_login, suspension_end 
        FROM users 
        WHERE usertype = 0";
$result = $_conn->query($sql);
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($users);
?>