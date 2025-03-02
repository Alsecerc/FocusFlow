<?php
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input
    $newName = !empty(trim($_POST['username'])) ? mysqli_real_escape_string($_conn, trim($_POST['username'])) : $_COOKIE['USERNAME'];
    $newEmail = !empty(trim($_POST['email'])) ? mysqli_real_escape_string($_conn, trim($_POST['email'])) : $_COOKIE['EMAIL'];
    $newPassword = !empty(trim($_POST['password'])) ? mysqli_real_escape_string($_conn, trim($_POST['password'])) : $_COOKIE['PASSWORD'];
    
    $userID = $_COOKIE['UID'];
    
    // Ensure userID is a number
    if (!is_numeric($userID)) {
        die("Invalid user ID.");
    }
    
    // Fix SQL statement
    $sql = "UPDATE users SET 
            name = '$newName',
            email = '$newEmail',
            password = '$newPassword'
            WHERE id = $userID";
    
    // Execute the query and check for errors
    if (mysqli_query($_conn, $sql)) {
        // Set cookies BEFORE any output
        setcookie("USERNAME", $newName, time() + (86400 * 30), "/");
        setcookie("EMAIL", $newEmail, time() + (86400 * 30), "/");
        setcookie("PASSWORD", $newPassword, time() + (86400 * 30), "/");
        
        // Now it's safe to output
        echo "Profile updated successfully. Refreshing...";
        echo "<script>window.location.href='Account.php';</script>";
        
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($_conn);
    }
}
