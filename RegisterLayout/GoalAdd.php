<?php
include "conn.php";
session_start();

if (!isset($_COOKIE['UID'])) {
    echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
    exit();
}

$user_id = $_COOKIE['UID'];
$goal_title = $_POST['goal_title'];
$goal_description = $_POST['goal_description'];
$goal_type = $_POST['goal_type'];
$start_date = $_POST['start_time'];
$end_date = $_POST['end_time'];
$reminder_time = $_POST['reminder_time'];

$sql = "INSERT INTO goals (user_id, goal_title, goal_description, goal_type, start_date, end_date, reminder_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $_conn->prepare($sql);
$stmt->bind_param("issssss", $user_id, $goal_title, $goal_description, $goal_type, $start_date, $end_date, $reminder_time);

if ($stmt->execute()) {
    echo "<script>
    alert('Goal added successfully!');
    window.location.href='Goal.php'; // Closes popup after success
  </script>";
} else {
    echo "Error: " . $_conn->error;
}

$stmt->close();
$_conn->close();
