<?php
include "conn.php";

if ($_conn->connect_error) {
    die("Connection failed: " . $_conn->connect_error);
}

// Function to check if notification already exists
function notificationExists($_conn, $user_id, $message)
{
    $sql = "SELECT id FROM notifications WHERE user_id = ? AND notification_message = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0; // Check if any rows exist
    $stmt->close();
    return $exists;
}

// Function to store notification only if it does not exist
function storeNotification($_conn, $user_id, $message)
{
    if (!notificationExists($_conn, $user_id, $message)) {
        $sql = "INSERT INTO notifications (type, user_id, notification_message) VALUES (?, ?, ?)";
        $stmt = $_conn->prepare($sql);
        $type = "system"; // Ensure correct data type for bind_param
        $stmt->bind_param("sis", $type, $user_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

date_default_timezone_set("Asia/Kuala_Lumpur");
$current_time = date("Y-m-d H:i:s");
$one_hour_later = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Fetch goals with reminders within the next hour
$sql = "SELECT g.*, u.email FROM goals g 
        JOIN users u ON g.user_id = u.id 
        WHERE g.reminder_time BETWEEN ? AND ?";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("ss", $current_time, $one_hour_later);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $user_email = $row['email'];
    $user_id = $row['user_id'];
    $goal_title = $row['goal_title'];

    // Notification message
    $message = "Reminder: Your goal '$goal_title' is due!";

    // Store in notifications table only if it does not exist
    storeNotification($_conn, $user_id, $message);
}


echo json_encode("IM still running");

$stmt->close();
$_conn->close();
