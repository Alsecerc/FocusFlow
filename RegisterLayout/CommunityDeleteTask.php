<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task_id"])) {
    $task_id = intval($_POST["task_id"]);

    $sql = "DELETE FROM group_tasks WHERE id = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Task deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete task"]);
    }

    $stmt->close();
    $_conn->close();
}
?>
