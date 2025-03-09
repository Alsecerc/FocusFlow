<?php
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_COOKIE['UID'])) {
        echo "<script>alert('User not authenticated. Please log in.'); window.location.href='Account.php';</script>";
        exit();
    }

    $userID = $_COOKIE['UID'];

    // Ensure userID is a valid number
    if (!is_numeric($userID)) {
        echo "<script>alert('Invalid user ID.'); window.location.href='Account.php';</script>";
        exit();
    }

    // Fetch user details from the database
    $sql = "SELECT name, email, password FROM users WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();

    if (!$userData) {
        echo "<script>alert('User not found.'); window.location.href='Account.php';</script>";
        exit();
    }

    $currentPasswordHash = $userData['password'];

    $enteredCurrentPassword = isset($_POST['current_password']) ? trim($_POST['current_password']) : null;
    $newPassword = isset($_POST['password']) ? trim($_POST['password']) : null;
    $newName = isset($_POST['username']) ? trim($_POST['username']) : null;
    $newEmail = isset($_POST['email']) ? trim($_POST['email']) : null;

    if ($enteredCurrentPassword === null) {
        echo "<script>alert('Please enter your current password.'); window.location.href='Account.php';</script>";
        exit();
    }

    // Validate that the current password matches the stored password
    if (!password_verify($enteredCurrentPassword, $currentPasswordHash)) {
        echo "<script>alert('Incorrect current password! Please enter the correct password.'); window.location.href='Account.php';</script>";
        exit();
    }

    // Check if new name and email were entered, else keep old values
    $newName = !empty($newName) ? mysqli_real_escape_string($_conn, $newName) : $userData['name'];
    $newEmail = !empty($newEmail) ? mysqli_real_escape_string($_conn, $newEmail) : $userData['email'];

    // If a new password is provided, hash it; otherwise, keep the old one
    $hashedPassword = (!empty($newPassword)) ? password_hash($newPassword, PASSWORD_DEFAULT) : $currentPasswordHash;

    // Prepare the SQL statement to update the database
    $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("sssi", $newName, $newEmail, $hashedPassword, $userID);

    if ($stmt->execute()) {
        // Update cookies with the new name and email
        setcookie("USERNAME", $newName, time() + (86400 * 30), "/");
        setcookie("EMAIL", $newEmail, time() + (86400 * 30), "/");

        echo "<script>alert('Profile updated successfully!'); window.location.href='Account.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating profile. Please try again.'); window.location.href='Account.php';</script>";
        exit();
    }

    $stmt->close();
}
