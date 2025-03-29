<?php
include "conn.php";

// Admin credentials
$adminName = "Admin";
$adminEmail = "admin@focusflow.com";
$adminPassword = "admin123"; // You should change this to a secure password
$hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

// Check if admin already exists
$checkSql = "SELECT id FROM users WHERE email = ? OR name = ?";
$checkStmt = $_conn->prepare($checkSql);
$checkStmt->bind_param("ss", $adminEmail, $adminName);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    die("Admin account already exists!");
}

// Insert admin user
$sql = "INSERT INTO users (
    name,
    email,
    password,
    usertype,
    UserStatus,
    created_at,
    last_login
) VALUES (?, ?, ?, 1, 'Active', NOW(), NOW())";

$stmt = $_conn->prepare($sql);
$stmt->bind_param("sss", $adminName, $adminEmail, $hashedPassword);

try {
    if ($stmt->execute()) {
        echo "Admin account created successfully!<br>";
        echo "Email: " . htmlspecialchars($adminEmail) . "<br>";
        echo "Password: " . htmlspecialchars($adminPassword) . "<br>";
        echo "<strong>Please delete this file after use!</strong>";
    } else {
        throw new Exception("Error creating admin account");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Close connections
$stmt->close();
$_conn->close();
?>