<?php
include $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php";

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

    // Insert task
    $stmt = $_conn->prepare("INSERT INTO tasks (task_title, task_desc, start_date, start_time, end_time, created_at, user_id, category, end_date) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $task_title, $task_desc, $start_date, $start_time, $end_time, $created_at, $user_id, $task_group, $end_date);

    $message = $stmt->execute() ? 'Task added successfully' : 'Task failed to upload';
    $stmt->close();
    $_conn->close();

    die("<script>alert('$message');window.location.href='Calendar.php'</script>");
}
