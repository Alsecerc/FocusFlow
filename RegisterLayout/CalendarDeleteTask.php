<?php
header("Content-Type: application/json"); // Return JSON response
include "conn.php";


if ($_conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Database connection failed"]));
}

// 3️⃣ Get data from POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $task_id = isset($_POST["task_id"]) ? $_POST["task_id"] : null;
    $status = isset($_POST["status"]) ? $_POST["status"] : null;

    if ($status === "Delete") {

        $stmt = $_conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $task_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Task deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to delete task"]);
        }
        $stmt->close();

    } else if ($status === "Completed") {

        $stmt = $_conn->prepare("UPDATE tasks SET status = 'complete' WHERE id = ?");
        $stmt->bind_param("i", $task_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Task marked as Incomplete"]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to update task status"]);
        }
        $stmt->close();

    } else {
        echo json_encode(["success" => false, "error" => "Invalid request"]);
    }
}

$_conn->close();
