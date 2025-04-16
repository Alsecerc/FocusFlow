<?php
include "conn.php";

$user_id = $_COOKIE['UID'];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? "";

    switch ($action) {
        case "fetch":
            $suggestedTasks = [];

            // Fetch user's recent tasks
            $query = $_conn->prepare("SELECT category FROM tasks WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
            $query->execute([$user_id]);
            $result = $query->get_result();
            $userTasks = $result->fetch_all(MYSQLI_ASSOC);

            if (!empty($userTasks)) {
                // Get most used category
                $categories = array_column($userTasks, 'category');
                $mostFrequentCategory = array_count_values($categories);
                arsort($mostFrequentCategory);
                $topCategory = key($mostFrequentCategory);

                // Fetch 3 suggested tasks from that category
                $suggestQuery = $_conn->prepare("SELECT task_title FROM tasks WHERE category = ? ORDER BY RAND() LIMIT 3");
                $suggestQuery->execute([$topCategory]);
                $result = $suggestQuery->get_result();
                $suggestedTasks = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                // Default random suggestions if no history
                $suggestedTasks = [
                    ['task_title' => 'Read a Book'],
                    ['task_title' => 'Plan Next Week/’s Schedule'],
                    ['task_title' => 'Workout for 30 Minutes']
                ];
            }


            echo json_encode($suggestedTasks);
            exit;
            break;
        case "add":
            $task_title = $_POST['task_title'] ?? "";
            $category = $_POST['category'] ?? "General";
            $start_date = $_POST['start_date'] ?? NULL;
            $start_time = $_POST['start_time'] ?? NULL;
            $end_date = $_POST['end_date'] ?? NULL;
            $end_time = $_POST['end_time'] ?? NULL;

            if (!empty($task_title)) {
                $suggestQuery = $_conn->prepare("SELECT task_title, task_desc, category FROM tasks WHERE task_title = ?");
                $suggestQuery->bind_param("s", $task_title);
                $suggestQuery->execute();
                $result = $suggestQuery->get_result();
                $suggestedTask = $result->fetch_assoc(); 

                if ($suggestedTask) {
                    $task_title = $suggestedTask['task_title']; 
                    $description = $suggestedTask['task_desc'];
                    $category = $suggestedTask['category'];  
                } else {
                    $description = "No description available"; 
                }

                // ✅ Insert new task with retrieved details
                $insertQuery = $_conn->prepare("INSERT INTO tasks (user_id, task_title, task_desc, category, start_date, start_time, end_date, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $insertQuery->execute([$user_id, $task_title, $description, $category, $start_date, $start_time, $end_date, $end_time]);

                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Task title is empty"]);
            }
            exit;
    }
}
