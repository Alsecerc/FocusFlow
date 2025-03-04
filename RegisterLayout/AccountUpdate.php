<?php
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_COOKIE['UID'];

    // Fetch user details from the database
    $sql = "SELECT name, email, password FROM users WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();

    if ($userData) {
        $currentPassword = $userData['password'];
        $newName = !empty(trim($_POST['username'])) ? mysqli_real_escape_string($_conn, trim($_POST['username'])) : $userData['name'];
        $newEmail = !empty(trim($_POST['email'])) ? mysqli_real_escape_string($_conn, trim($_POST['email'])) : $userData['email'];
        $newPassword = !empty(trim($_POST['password'])) ? mysqli_real_escape_string($_conn, trim($_POST['password'])) : $currentPassword; // Use DB password if no new password is provided
    }

    // Ensure userID is a number
    if (!is_numeric($userID)) {
        die("Invalid user ID.");
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Fix SQL statement
    $sql = "UPDATE users SET 
            name = '$newName',
            email = '$newEmail',
            password = '$hashedPassword'
            WHERE id = $userID";

    // Execute the query and check for errors
    if (mysqli_query($_conn, $sql)) {
        // Set cookies BEFORE any output
        setcookie("USERNAME", $newName, time() + (86400 * 30), "/");
        setcookie("EMAIL", $newEmail, time() + (86400 * 30), "/");

        // Now it's safe to output
        echo "Profile updated successfully. Refreshing...";
        echo "<script>window.location.href='Account.php';</script>";

        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($_conn);
    }
}
