<?php
include 'conn.php'; // Your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskID = $_POST['task_id'];
    $newStatus = $_POST['status'];



    $sql = "UPDATE group_tasks SET status = ? WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("si", $newStatus, $taskID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $_conn->close();
}
?>
