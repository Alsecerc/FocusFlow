<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "conn.php";
include "AccountVerify.php";

echo "<h1>Authentication Debug Page</h1>";

// Check session
echo "<h2>Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check cookies
echo "<h2>Cookie Data:</h2>";
echo "<pre>";
print_r($_COOKIE);
echo "</pre>";

// Check database structure
echo "<h2>Database Structure:</h2>";
$result = mysqli_query($_conn, "SHOW COLUMNS FROM users");
echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    foreach ($row as $value) {
        echo "<td>" . htmlspecialchars($value) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

// Check if user is authenticated
echo "<h2>Authentication Status:</h2>";
if (isset($_COOKIE['UID'])) {
    $userId = $_COOKIE['UID'];
    echo "User ID: " . htmlspecialchars($userId) . "<br>";
    
    $user = mysqli_query($_conn, "SELECT * FROM users WHERE id = " . intval($userId));
    if ($userData = mysqli_fetch_assoc($user)) {
        echo "User exists in database<br>";
        echo "Username: " . htmlspecialchars($userData['name']) . "<br>";
        echo "Auth Token in DB: " . (isset($userData['auth_token']) ? "Yes (" . substr(htmlspecialchars($userData['auth_token']), 0, 6) . "...)" : "No") . "<br>";
        echo "Token Expiry: " . (isset($userData['token_expires']) ? htmlspecialchars($userData['token_expires']) : "Not set") . "<br>";
        
        // Check if token matches
        if (isset($_COOKIE['AUTH_TOKEN'])) {
            $tokenMatch = ($_COOKIE['AUTH_TOKEN'] === $userData['auth_token']);
            echo "Token Match: " . ($tokenMatch ? "Yes" : "No") . "<br>";
        } else {
            echo "No AUTH_TOKEN cookie found<br>";
        }
    } else {
        echo "User ID not found in database<br>";
    }
} else {
    echo "No UID cookie found<br>";
}

echo "<p><a href='Login.php'>Go to Login Page</a></p>";
?>
