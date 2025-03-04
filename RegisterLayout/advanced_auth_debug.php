<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "conn.php";
include "AccountVerify.php";

echo "<h1>Advanced Authentication Debug</h1>";

// Check session
echo "<h2>Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check cookies
echo "<h2>Cookie Data:</h2>";
echo "<pre>";
foreach ($_COOKIE as $key => $value) {
    echo htmlspecialchars($key) . ": ";
    if ($key == 'AUTH_TOKEN') {
        // Show token structure but not full value
        $parts = explode('.', $value);
        if (count($parts) == 2) {
            echo "Token: " . substr($parts[0], 0, 10) . "... Signature: " . substr($parts[1], 0, 10) . "...";
        } else {
            echo "Legacy format: " . substr($value, 0, 10) . "...";
        }
    } else {
        echo htmlspecialchars($value);
    }
    echo "<br>";
}
echo "</pre>";

// Manually verify token
echo "<h2>Token Verification:</h2>";
if (isset($_COOKIE['UID']) && isset($_COOKIE['AUTH_TOKEN'])) {
    $userId = $_COOKIE['UID'];
    $token = $_COOKIE['AUTH_TOKEN'];
    
    // Get user data
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        echo "User found in database<br>";
        echo "User ID: " . htmlspecialchars($userId) . "<br>";
        echo "Username: " . htmlspecialchars($user['name']) . "<br>";
        echo "Auth Token in DB: " . substr(htmlspecialchars($user['auth_token'] ?? 'None'), 0, 10) . "...<br>";
        echo "Token Expiry: " . htmlspecialchars($user['token_expires'] ?? 'None') . "<br>";
        
        // Parse token
        $parts = explode('.', $token, 2);
        if (count($parts) == 2) {
            $baseToken = $parts[0];
            $signature = $parts[1];
            
            echo "Token Format: HMAC Signed<br>";
            echo "Base Token: " . substr($baseToken, 0, 10) . "...<br>";
            echo "Signature: " . substr($signature, 0, 10) . "...<br>";
            
            // Check if base token matches database
            $tokenMatch = ($baseToken === $user['auth_token']);
            echo "Base Token Match: " . ($tokenMatch ? "✓ Yes" : "✗ No") . "<br>";
            
            // Verify signature
            $secret = getAppSecret();
            $expectedSignature = hash_hmac('sha256', $userId . $baseToken, $secret);
            $signatureMatch = hash_equals($signature, $expectedSignature);
            
            echo "Secret Key: " . substr($secret, 0, 5) . "...<br>";
            echo "Signature Match: " . ($signatureMatch ? "✓ Yes" : "✗ No") . "<br>";
            
            // Overall verification
            echo "Overall Verification: " . (($tokenMatch && $signatureMatch) ? "✓ VALID" : "✗ INVALID") . "<br>";
        } else {
            echo "Token Format: Legacy (No Signature)<br>";
            echo "Token Match: " . ($token === $user['auth_token'] ? "✓ Yes" : "✗ No") . "<br>";
        }
        
        // Overall verification with library function
        echo "Library Verification: " . (verifyUser($_conn) ? "✓ VALID" : "✗ INVALID") . "<br>";
    } else {
        echo "User not found in database<br>";
    }
} else {
    echo "No authentication cookies found<br>";
}

echo "<p><a href='auth_debug.php'>Basic Auth Debug</a> | <a href='Login.php'>Login Page</a></p>";

// Provide a button to upgrade token
if (isset($_COOKIE['UID']) && isset($_COOKIE['AUTH_TOKEN'])) {
    echo "<form method='post'>";
    echo "<input type='submit' name='upgrade_token' value='Upgrade Authentication Token'>";
    echo "</form>";
    
    if (isset($_POST['upgrade_token'])) {
        createAuthSession($_COOKIE['UID'], $_conn);
        echo "<p style='color:green'>Token upgraded! Refresh page to see changes.</p>";
    }
}
?>
