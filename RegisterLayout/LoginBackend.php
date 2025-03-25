<?php
session_start();
error_reporting(E_ALL); // Enable full error reporting for debugging
ini_set('display_errors', 1);

include 'conn.php';
include 'AccountVerify.php'; // Include the verification system

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($_conn, $_POST['username'] ?? "");
    $password = mysqli_real_escape_string($_conn, $_POST['password'] ?? "");

    // First check if the username exists
    $sqlName = "SELECT * FROM users WHERE name = ?";
    $stmt = mysqli_prepare($_conn, $sqlName);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $resultName = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultName) >= 1) {
        // Username exists, fetch the user data
        $user = mysqli_fetch_assoc($resultName);
        $storedPassword = $user['password'];

        // Check if password matches
        if (verifyPassword($password, $storedPassword)) {
            // Store user data in session variables
            $_SESSION['userID'] = $user['id'];
            $_SESSION['userName'] = $user['name'];
            $_SESSION['userEmail'] = $user['email'];
            $_SESSION['usertype'] = $user['usertype'];


            $authResult = createAuthSession($user['id'], $_conn);

            if ($authResult) {
                logLogin($_conn, $user['id'], true);
                echo "<script>alert('Welcome back, {$user['name']}!'); window.location.href='Homepage.php';</script>";
                exit();
            } else {
                logLogin($_conn, $user['id'], false);
                die("<script>alert('Failed to create authentication session. Please try again.');window.location.href='Login.php';</script>");
            }
        } else {
            logLogin($_conn, $user['id'], false);
            die("<script>alert('Password is incorrect');window.location.href='Login.php';</script>");
        }
    } else {
        logLogin($_conn, null, false);
        die("<script>alert('Username not found');window.location.href='Login.php';</script>");
    }
}


