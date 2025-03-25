<?php 
header('Content-Type: application/json');
include $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php";

$user_id = $_POST['user_id'] ?? '';

if ($user_id) {
    $stmt = $_conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    echo json_encode(["name" => $result['name'] ?? ""]);
} else {
    echo json_encode(["name" => ""]);
}
