<?php
// When registering a new user
$plainPassword = $_POST['password']; // From user input
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Store $hashedPassword in your database
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashedPassword);
$stmt->execute();

// When verifying a login
function verifyPassword($inputPassword, $storedHash) {
    return password_verify($inputPassword, $storedHash);
}
?>
