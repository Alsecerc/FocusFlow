<?php
session_start();
include "conn.php";

if (!isset($_COOKIE['UID'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

if (!isset($_POST['task_id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit();
}

$taskId = $_POST['task_id'];
$status = $_POST['status'];
$userId = $_COOKIE['UID'];

$sql = "SELECT * FROM group_tasks WHERE id = ? AND (assigned_by = ? OR assigned_to = ?)";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("iii", $taskId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$sql = "UPDATE group_tasks SET status = ? WHERE id = ?";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("si", $status, $taskId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}