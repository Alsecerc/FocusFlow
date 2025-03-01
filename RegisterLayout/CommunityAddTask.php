<?php
include "conn.php"; // Database connection file

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure UID cookie and team name exist
    if (!isset($_COOKIE['UID']) || !isset($_GET['team'])) {
        echo json_encode(["status" => "error", "message" => "Unauthorized request."]);
        exit;
    }

    // Retrieve and sanitize inputs
    $assign_by = (int) $_COOKIE['UID']; // Ensure it is an integer
    $teamName = htmlspecialchars(urldecode($_GET['team']));
    $task_name = htmlspecialchars(trim($_POST["task_name"]));
    $assigned_to = (int) trim($_POST["assigned_to"]); // Ensure integer
    $task_description = htmlspecialchars(trim($_POST["task_desc"])); // Fixed field name
    $due_date = trim($_POST["due_date"]);

    // Prepare SQL statement (ensure `task_description` is used)
    $sql = "INSERT INTO group_tasks (team_name, task_name, assigned_by, assigned_to, task_description, due_date, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')";

    $stmt = $_conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $_conn->error]);
        exit;
    }

    // Bind parameters correctly
    $stmt->bind_param("ssiiss", $teamName, $task_name, $assign_by, $assigned_to, $task_description, $due_date);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Task added successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
    $_conn->close();
}
?>
