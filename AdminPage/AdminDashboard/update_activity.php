<?php
session_start();
include "conn.php";

if (isset($_SESSION['userID'])) {
    $userId = $_SESSION['userID'];
    
    // Update user's last activity
    $sql = "UPDATE users 
            SET is_online = TRUE, 
                last_activity = NOW() 
            WHERE id = ?";
    
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

// Clean up inactive users (offline after 5 minutes of inactivity)
$sql = "UPDATE users 
        SET is_online = FALSE 
        WHERE last_activity < NOW() - INTERVAL 5 MINUTE";
$_conn->query($sql);
?>