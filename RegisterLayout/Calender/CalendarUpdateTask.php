<?php
include $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['task_id']) && isset($_POST['status'])) {
        // Update a specific task's status
        $taskID = $_POST['task_id'];
        $newStatus = $_POST['status'];

        $sql = "UPDATE tasks SET status = ? WHERE id = ?";
        $stmt = $_conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $taskID);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }

        $stmt->close();
    } else {
        // Update overdue tasks
        $currentDate = date('Y-m-d');
        $sql = "UPDATE tasks SET status = 'Timeout' WHERE end_date < ? AND (status != 'Timeout' AND status != 'Complete')";
        $stmt = $_conn->prepare($sql);
        $stmt->bind_param("s", $currentDate);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }

        $stmt->close();
    }
    $_conn->close();
}
