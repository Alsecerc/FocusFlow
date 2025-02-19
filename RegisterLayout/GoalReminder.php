<?php
include "conn.php";

$now = date('Y-m-d H:i:s');
$sql = "SELECT users.email, goals.goal_title, goals.reminder_time 
        FROM goals 
        JOIN users ON goals.user_id = users.id 
        WHERE goals.reminder_time <= ? AND goals.status = 'in-progress'";

$stmt = $_conn->prepare($sql);
$stmt->bind_param("s", $now);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $to = $row['email'];
    $subject = "Goal Reminder: " . $row['goal_title'];
    $message = "You set a reminder for your goal: " . $row['goal_title'] . ". Stay productive!";
    $headers = "From: no-reply@yourwebsite.com";

    mail($to, $subject, $message, $headers);
}

$stmt->close();
$_conn->close();
?>
