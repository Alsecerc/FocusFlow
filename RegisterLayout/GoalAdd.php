<?php
session_start();
include "conn.php"; // Database connection

if (!isset($_COOKIE['id'])) {
    die("User not logged in");
}

$user_id = $_COOKIE['id'];
$goal_title = $_POST['goal_title'];
$goal_description = $_POST['goal_description'];
$goal_type = $_POST['goal_type'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$reminder_time = $_POST['reminder_time'];

$sql = "INSERT INTO goals (user_id, goal_title, goal_description, goal_type, start_date, end_date, reminder_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $_conn->prepare($sql);
$stmt->bind_param("issssss", $user_id, $goal_title, $goal_description, $goal_type, $start_date, $end_date, $reminder_time);

if ($stmt->execute()) {
    echo "Goal added successfully!";
} else {
    echo "Error: " . $_conn->error;
}

$stmt->close();
$_conn->close();
?>
