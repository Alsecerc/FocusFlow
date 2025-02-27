<?php
include "conn.php";

if ($_conn->connect_error) {
    die("Connection failed: " . $_conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $required_fields = ['task_title', 'task_desc', 'task_group', 'start_date', 'start_time', 'end_time'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("<script>alert('All fields are required!');window.location.href='Calendar.php'</script>");
        }
    }

    // Retrieve inputs
    $task_title = trim($_POST['task_title']);
    $task_desc = trim($_POST['task_desc']);
    $task_group = trim($_POST['task_group']);
    $start_date = $_POST['start_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $user_id = $_COOKIE['UID'];
    $created_at = date("Y-m-d H:i:s");

    // Check for time conflict
    $stmt = $_conn->prepare("SELECT COUNT(*) FROM tasks WHERE start_date = ? 
        AND (start_time < ? AND end_time > ? OR start_time >= ? AND end_time <= ?)");
    $stmt->bind_param("sssss", $start_date, $end_time, $start_time, $start_time, $end_time);
    $stmt->execute();
    $stmt->bind_result($task_count);
    $stmt->fetch();
    $stmt->close();

    if ($task_count > 0) {
        die("<script>alert('Task time conflicts with an existing task!');window.location.href='Calendar.php'</script>");
    }

    // Insert task
    $stmt = $_conn->prepare("INSERT INTO tasks (task_title, task_desc, start_date, start_time, end_time, created_at, user_id, category) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $task_title, $task_desc, $start_date, $start_time, $end_time, $created_at, $user_id, $task_group);

    $message = $stmt->execute() ? 'Task added successfully' : 'Task failed to upload';
    $stmt->close();
    $_conn->close();

    die("<script>alert('$message');window.location.href='Calendar.php'</script>");
}
