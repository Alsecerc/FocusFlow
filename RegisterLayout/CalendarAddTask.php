<?php
include "conn.php";

if ($_conn->connect_error) {
    die("Connection failed: " . $_conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $required_fields = ['task_title', 'task_desc', 'task_group', 'start_date', 'end_date', 'start_time', 'end_time'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            die("<script>alert('$_POST[$field], $field is Empty...');window.location.href='Calendar.php'</script>");
        }
    }

    //     // Retrieve inputs
    $task_title = trim($_POST['task_title']);
    $task_desc = trim($_POST['task_desc']);
    $task_group = trim($_POST['task_group']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $user_id = $_COOKIE['UID'];
    $created_at = date("Y-m-d H:i:s");

    // Check for time conflict
    $stmt = $_conn->prepare("
    SELECT COUNT(*) FROM tasks 
    WHERE 
        (start_date <= ? AND end_date >= ?)  -- Task spans multiple days OR within range
        AND (
            -- Overlapping within the same day
            (start_date = ? AND end_date = ? AND (
                (start_time < ? AND end_time > ?)  -- New task inside existing task
                OR (start_time >= ? AND end_time <= ?) -- New task fully contains an existing task
                OR (start_time <= ? AND end_time >= ?) -- Overlapping start or end
            ))
            OR
            -- Overlapping a multi-day task
            (start_date < ? AND end_date > ?)  -- New task is inside an existing multi-day task
            OR
            -- New task spans multiple days and starts/ends inside an existing task
            (start_date >= ? AND start_date <= ? AND end_date >= ? AND end_date <= ?)
        )
");

    $stmt->bind_param(
        "ssssssssssssssss",
        $end_date,
        $start_date,  // Check if task is within range
        $start_date,
        $end_date,   // Check if it's a single-day task
        $end_time,
        $start_time,
        $start_time,
        $end_time,
        $start_time,
        $end_time, // Single-day time checks
        $end_date,
        $start_date,   // Multi-day task fully containing new task
        $start_date,
        $end_date,
        $start_date,
        $end_date // New task inside an existing multi-day task
    );

    $stmt->execute();
    $stmt->bind_result($task_count);
    $stmt->fetch();
    $stmt->close();


    if ($task_count > 0) {
        die("<script>alert('Task time conflicts with an existing task!');window.location.href='Calendar.php'</script>");
    }

    // Insert task
    $stmt = $_conn->prepare("INSERT INTO tasks (task_title, task_desc, start_date, start_time, end_time, created_at, user_id, category, end_date) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $task_title, $task_desc, $start_date, $start_time, $end_time, $created_at, $user_id, $task_group, $end_date);

    $message = $stmt->execute() ? 'Task added successfully' : 'Task failed to upload';
    $stmt->close();
    $_conn->close();

    die("<script>alert('$message');window.location.href='Calendar.php'</script>");
}
