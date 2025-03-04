<?php
// When creating a user session:
function createSecureSession($userId, $_conn) {
    // Generate a session identifier that doesn't expose the actual user ID
    $sessionId = bin2hex(random_bytes(24)); // 48 character hex string
    
    // Store the mapping in your database
    $sql = "INSERT INTO user_sessions (session_id, user_id, created_at, expires_at) 
            VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY))";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("si", $sessionId, $userId);
    $stmt->execute();
    
    // Set the opaque session ID instead of the actual user ID
    setcookie("SESSION_ID", $sessionId, [
        'expires' => time() + (86400 * 30),
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    // Still set the auth token as a second factor
    // ...existing token creation code...
}

// When verifying a user:
function getAuthenticatedUser($_conn) {
    if (!isset($_COOKIE['SESSION_ID'])) {
        return false;
    }
    
    $sessionId = $_COOKIE['SESSION_ID'];
    
    // Retrieve user ID from the session store
    $sql = "SELECT user_id FROM user_sessions 
            WHERE session_id = ? AND expires_at > NOW()";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("s", $sessionId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return false;
    }
    
    $row = $result->fetch_assoc();
    return $row['user_id']; // Return the actual user ID for internal use
}
?>
