<?php
include "../../RegisterLayout/conn.php";
date_default_timezone_set('Asia/Kuala_Lumpur');

header('Content-Type: application/json');

// Check if action parameter exists
if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'No action specified']);
    exit;
}

$action = $_POST['action'];
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

// Check if user exists
$check_sql = "SELECT * FROM users WHERE id = ?";
$check_stmt = $_conn->prepare($check_sql);
$check_stmt->bind_param('i', $id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

if ($action === 'suspend') {
    $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 30; // Default to 30 minutes
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
    
    // Calculate suspension end time
    $suspension_end = date('Y-m-d H:i:s', strtotime("+{$duration} minutes"));
    
    $sql = "UPDATE users SET UserStatus = 'Suspended', suspension_end = ?, suspension_reason = ? WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param('ssi', $suspension_end, $reason, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $_conn->error]);
    }
} elseif ($action === 'unsuspend') {
    $sql = "UPDATE users SET UserStatus = 'Active', suspension_end = NULL, suspension_reason = NULL WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $_conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
