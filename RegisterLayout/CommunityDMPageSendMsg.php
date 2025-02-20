<?php
session_start();
include "conn.php";

// Ensure only JSON response
header("Content-Type: application/json");

// Stop execution if connection fails
if (!$_conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['userID'] ?? "";
    $receiver_id = $_POST['receiver_id'] ?? "";
    $message_text = trim($_POST['message'] ?? "");

    // Validate inputs
    if (empty($sender_id) || empty($receiver_id) || empty($message_text)) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit();
    }

    // Sanitize input
    $sender_id = mysqli_real_escape_string($_conn, $sender_id);
    $receiver_id = mysqli_real_escape_string($_conn, $receiver_id);
    $message_text = mysqli_real_escape_string($_conn, $message_text);

    // Insert into database
    $sql = "INSERT INTO message (sender_id, receiver_id, message_text) VALUES ('$sender_id', '$receiver_id', '$message_text')";

    if (mysqli_query($_conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Message sent"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($_conn)]);
    }
    exit(); // Prevents unwanted output
}

mysqli_close($_conn);
?>
