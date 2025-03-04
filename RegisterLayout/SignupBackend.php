<?php
include "conn.php";
date_default_timezone_set('Asia/Kuala_Lumpur');

$username = mysqli_real_escape_string($_conn, $_POST['username']);
$email = mysqli_real_escape_string($_conn, $_POST['email']);
$password = mysqli_real_escape_string($_conn, $_POST['password']);
$confirmPassword = mysqli_real_escape_string($_conn, $_POST['confirmPassword']);
$age = mysqli_real_escape_string($_conn, $_POST['age']);
$gender = mysqli_real_escape_string($_conn, $_POST['gender']);
$productivity_goals = mysqli_real_escape_string($_conn, $_POST['productivity_goals']);
$preferred_hours = mysqli_real_escape_string($_conn, $_POST['preferred_hours']);
$purpose = mysqli_real_escape_string($_conn, $_POST['purpose']);

if ($password === $confirmPassword) {
    // $hashPassword = md5($password);
    $lastLogin = date('Y-m-d H:i:s');

    $sqlCheckUser = "SELECT * FROM users WHERE name='$username' OR email='$email'";
    $resultCheck = mysqli_query($_conn, $sqlCheckUser);

    if (mysqli_num_rows($resultCheck) > 0) {
        // User exists, return to signup page with a message
        die("<script>alert('Username or Email already exists. Please choose a different one.');window.location.href='Signup.php';</script>");
    }

    // Proceed with inserting the new user if they don’t exist
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);// Hash the password
    $sql = "INSERT INTO users (name, email, password, last_Login) VALUES ('$username', '$email', '$hashedPassword', '$lastLogin')";
    if (mysqli_query($_conn, $sql)) {
        // Account successfully registered
        die("<script>alert('Account has been registered successfully.');window.location.href='Login.php';</script>");
    } else {
        // Query failed
        die("<script>alert('Unable to register. Please try again later.');window.location.href='Signup.php';</script>");
    }
} else {
    die("<script>alert('Confirm Password is not identical as password.');window.location.href='Signup.php';</script>");
}
