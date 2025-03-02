<?php 
    session_start();
    include "conn.php";
    $userId = $_COOKIE['UID'];
    
    // Set default timezone for the entire application
    // Change 'Asia/Bangkok' to your actual timezone if different
    date_default_timezone_set('Asia/Kuala_Lumpur');
    
    // Main function to handle all requests
    function handleRequests() {
        global $_conn, $userId;
        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        header("Content-Type: application/json");
    
        // Start output buffering
        ob_start();
        
        // Handle POST requests with JSON data
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (!$data || !isset($data['type'])) {
                ob_clean();
                echo json_encode(["status" => "error", "error" => "No data received or missing type"]);
                exit;
            }
            
            // Handle different request types
            switch ($data['type']) {
                case "fetch_group_and_task":
                    $response = fetchGroupsAndTasks();
                    break;
                    
                case "create_group":
                    $response = createGroup($data);
                    break;
                    
                case "create_task":
                    $response = createTask($data);
                    break;
                    
                case "update_task_status":
                    $response = updateTaskStatus($data);
                    break;
                    
                case "move_task":
                    $response = moveTask($data);
                    break;
                    
                case "delete_task":
                    $response = deleteTask($data);
                    break;
                    
                default:
                    $response = ["status" => "error", "error" => "Invalid request type"];
            }
            
            ob_clean();
            echo json_encode($response);
            exit;
        }
        // Handle GET requests (if needed)
        else if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['type'])) {
            $type = $_GET['type'];
            
            if (!$type) {
                ob_clean();
                echo json_encode(["status" => "error", "error" => "No type specified"]);
                exit;
            }
            
            $response = [];
            
            // Handle different GET request types
            switch ($type) {
                case "FinalDate":
                    $finalDate = date("Y-m-d");
                    $finalTime = date("H:i:s");
                    $response = ["Date" => [$finalDate], "Time" => [$finalTime]];
                    break;
                    
                default:
                    $response = ["status" => "error", "error" => "Invalid request type"];
            }
            
            ob_clean();
            echo json_encode($response);
            exit;
        }
    }
    
    // Function to fetch all groups and tasks for the current user
    function fetchGroupsAndTasks() {
        global $_conn, $userId;
        
        try {
            // Get distinct categories
            $categoriesQuery = "SELECT DISTINCT category FROM tasks WHERE user_id = ? ORDER BY category";
            $stmt = $_conn->prepare($categoriesQuery);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $categoriesResult = $stmt->get_result();
            
            if (!$categoriesResult) {
                throw new Exception("Database error: " . $_conn->error);
            }
            
            $groups = [];
            
            // If no categories exist
            if ($categoriesResult->num_rows === 0) {
                return ["status" => "success", "data" => []];
            }
            
            // Process each category
            while ($categoryRow = $categoriesResult->fetch_assoc()) {
                $category = $categoryRow['category'];
                
                // Get tasks for this category - exclude placeholder tasks
                $tasksQuery = "SELECT id, task_title, task_desc, start_date, start_time, end_date, end_time, status 
                              FROM tasks 
                              WHERE user_id = ? AND category = ? AND task_title != '_placeholder_' 
                              ORDER BY CASE 
                                WHEN status = 'Complete' THEN 2 
                                WHEN status = 'Timeout' THEN 1 
                                ELSE 0 
                              END, 
                              CASE 
                                WHEN end_date IS NOT NULL THEN end_date
                                ELSE DATE_ADD(start_date, INTERVAL 1 DAY)
                              END";
                
                $stmt = $_conn->prepare($tasksQuery);
                $stmt->bind_param("is", $userId, $category);
                $stmt->execute();
                $tasksResult = $stmt->get_result();
                
                $tasks = [];
                
                while ($taskRow = $tasksResult->fetch_assoc()) {
                    // Normalize status to lowercase for frontend consistency
                    $status = strtolower($taskRow['status']);
                    
                    $tasks[] = [
                        "id" => $taskRow['id'],
                        "title" => $taskRow['task_title'],
                        "description" => $taskRow['task_desc'],
                        "start_date" => $taskRow['start_date'],
                        "start_time" => $taskRow['start_time'],
                        "end_date" => $taskRow['end_date'],
                        "end_time" => $taskRow['end_time'],
                        "status" => $status
                    ];
                }
                
                $groups[] = [
                    "group" => $category,
                    "tasks" => $tasks
                ];
            }
            
            return ["status" => "success", "data" => $groups];
            
        } catch (Exception $e) {
            return ["status" => "error", "error" => $e->getMessage()];
        }
    }
    
    // Function to create a new group (category)
    function createGroup($data) {
        global $_conn, $userId;
        
        try {
            if (!isset($data['group_name']) || empty(trim($data['group_name']))) {
                return ["status" => "error", "error" => "Group name is required"];
            }
            
            $groupName = trim($data['group_name']);
            
            // Check if group already exists
            $checkQuery = "SELECT 1 FROM tasks WHERE user_id = ? AND category = ? LIMIT 1";
            $stmt = $_conn->prepare($checkQuery);
            $stmt->bind_param("is", $userId, $groupName);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return ["status" => "error", "error" => "Group already exists"];
            }
            
            // Create a placeholder task to establish the group
            $now = date("Y-m-d");
            $time = date("H:i:s");
            
            $sql = "INSERT INTO tasks (task_title, task_desc, start_date, start_time, end_time, user_id, category) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $placeholderTitle = "_placeholder_"; // This will be hidden in UI
            $placeholderDesc = "Group placeholder";
            
            $stmt = $_conn->prepare($sql);
            $stmt->bind_param("sssssss", $placeholderTitle, $placeholderDesc, $now, $time, $time, $userId, $groupName);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create group: " . $stmt->error);
            }
            
            // We no longer delete the placeholder - this ensures the category exists even with no user-created tasks
            // This is the key fix - we're keeping the placeholder task so the group persists
            
            return ["status" => "success", "message" => "Group created successfully"];
            
        } catch (Exception $e) {
            return ["status" => "error", "error" => $e->getMessage()];
        }
    }
    
    // Function to create a new task
    function createTask($data) {
        global $_conn, $userId;
        
        try {
            // Set timezone again to ensure consistent date calculations
            date_default_timezone_set('Asia/Kuala_Lumpur'); // Updated to Malaysia timezone
            
            // Validate required fields
            if (!isset($data['category'], $data['title'], $data['content']) || 
                empty(trim($data['category'])) || 
                empty(trim($data['title'])) || 
                empty(trim($data['content']))) {
                return ["status" => "error", "error" => "Missing required task data"];
            }
            
            $category = trim($data['category']);
            $title = trim($data['title']);
            $content = trim($data['content']);
            $status = isset($data['status']) ? $data['status'] : 'incomplete';
            
            // Normalize status capitalization for database
            $dbStatus = ucfirst(strtolower($status));
            
            // Handle timer or explicit dates
            $startDate = date("Y-m-d");
            $startTime = date("H:i:s");
            $endDate = null;
            $endTime = null;
            
            if (isset($data['end_date']) && isset($data['end_time'])) {
                // Explicit end date and time
                $endDate = $data['end_date'];
                $endTime = $data['end_time'];
            } 
            else if (isset($data['timer'])) {
                // Calculate from timer
                $timer = $data['timer'];
                $days = isset($timer['days']) ? intval($timer['days']) : 0;
                $hours = isset($timer['hours']) ? intval($timer['hours']) : 0;
                $minutes = isset($timer['minutes']) ? intval($timer['minutes']) : 0;
                $seconds = isset($timer['seconds']) ? intval($timer['seconds']) : 0;
                
                // Calculate total seconds
                $totalSeconds = $seconds + ($minutes * 60) + ($hours * 3600) + ($days * 86400);
                
                if ($totalSeconds > 0) {
                    $endDateTime = new DateTime($startDate . ' ' . $startTime);
                    $endDateTime->modify("+{$totalSeconds} seconds");
                    
                    $endDate = $endDateTime->format('Y-m-d');
                    $endTime = $endDateTime->format('H:i:s');
                } else {
                    // Default to 1 day if timer is 0
                    $endDate = date('Y-m-d', strtotime('+1 day'));
                    $endTime = $startTime;
                }
            } else {
                // Default to 1 day if no timer or explicit date/time
                $endDate = date('Y-m-d', strtotime('+1 day'));
                $endTime = $startTime;
            }
            
            // Insert the task
            $sql = "INSERT INTO tasks (task_title, task_desc, start_date, start_time, end_date, end_time, user_id, status, category) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                   
            $stmt = $_conn->prepare($sql);
            $stmt->bind_param("sssssssss", $title, $content, $startDate, $startTime, $endDate, $endTime, $userId, $dbStatus, $category);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create task: " . $stmt->error);
            }
            
            // Get the newly inserted task ID
            $newTaskId = $stmt->insert_id;
            
            return [
                "status" => "success", 
                "message" => "Task created successfully",
                "insertId" => $newTaskId, // Return the task ID
                "data" => [
                    "id" => $newTaskId, // Also include ID in the data object
                    "title" => $title,
                    "description" => $content,
                    "start_date" => $startDate,
                    "start_time" => $startTime,
                    "end_date" => $endDate,
                    "end_time" => $endTime,
                    "status" => $status,
                    "category" => $category
                ]
            ];
            
        } catch (Exception $e) {
            return ["status" => "error", "error" => $e->getMessage()];
        }
    }
    
    // Function to update a task's status
    function updateTaskStatus($data) {
        global $_conn, $userId;
        
        try {
            // Check if task ID is provided - preferred method
            if (isset($data['task_id']) && !empty($data['task_id'])) {
                $taskId = intval($data['task_id']);
                $status = ucfirst(strtolower($data['status'])); // Normalize status
                
                // Update by ID - most specific and reliable
                $sql = "UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?";
                $stmt = $_conn->prepare($sql);
                $stmt->bind_param("sii", $status, $taskId, $userId);
                
            // Fallback to title/category method (for backward compatibility)
            } else if (isset($data['title'], $data['category'], $data['status']) && 
                      !empty($data['title']) && 
                      !empty($data['category']) && 
                      !empty($data['status'])) {
                
                $title = trim($data['title']);
                $category = trim($data['category']);
                $status = ucfirst(strtolower($data['status'])); // Normalize status
                
                // If task_desc is provided, use it to further narrow down the task
                if (isset($data['description']) && !empty($data['description'])) {
                    $description = trim($data['description']);
                    $sql = "UPDATE tasks 
                           SET status = ? 
                           WHERE user_id = ? AND task_title = ? AND category = ? AND task_desc = ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("sisss", $status, $userId, $title, $category, $description);
                } else {
                    // Legacy method - may update multiple tasks with same title in same category
                    $sql = "UPDATE tasks 
                           SET status = ? 
                           WHERE user_id = ? AND task_title = ? AND category = ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("siss", $status, $userId, $title, $category);
                }
            } else {
                return ["status" => "error", "error" => "Missing required data for status update"];
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update task status: " . $stmt->error);
            }
            
            if ($stmt->affected_rows === 0) {
                return ["status" => "error", "error" => "Task not found or no changes were made"];
            }
            
            return ["status" => "success", "message" => "Task status updated successfully"];
            
        } catch (Exception $e) {
            return ["status" => "error", "error" => $e->getMessage()];
        }
    }
    
    // Function to move a task to a different category
    function moveTask($data) {
        global $_conn, $userId;
        
        try {
            // Check if we have a task ID (preferred method)
            if (isset($data['task_id']) && !empty($data['task_id'])) {
                $taskId = intval($data['task_id']);
                $newCategory = $data['newCategory'];
                
                // First get the current category of the task
                $checkSql = "SELECT category FROM tasks WHERE id = ? AND user_id = ?";
                $checkStmt = $_conn->prepare($checkSql);
                $checkStmt->bind_param("ii", $taskId, $userId);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                
                if ($result->num_rows === 0) {
                    return ["status" => "error", "error" => "Task not found"];
                }
                
                $row = $result->fetch_assoc();
                $oldCategory = $row['category'];
                
                // Update the task's category using ID
                $sql = "UPDATE tasks SET category = ? WHERE id = ? AND user_id = ?";
                $stmt = $_conn->prepare($sql);
                $stmt->bind_param("sii", $newCategory, $taskId, $userId);
                
            // Fallback to the traditional title+category method with description for more precision    
            } else if (isset($data['oldCategory'], $data['newCategory'], $data['title'])) {
                $oldCategory = $data['oldCategory'];
                $newCategory = $data['newCategory'];
                $title = $data['title'];
                
                // Debug information
                error_log("Moving task: Title={$title}, From={$oldCategory}, To={$newCategory}, User={$userId}");
                
                // Check if a description is provided for more precise targeting
                $description = isset($data['description']) ? $data['description'] : null;
                
                // First check if the task exists with these parameters
                if ($description) {
                    $checkSql = "SELECT id FROM tasks WHERE user_id = ? AND task_title = ? AND category = ? AND task_desc = ?";
                    $checkStmt = $_conn->prepare($checkSql);
                    $checkStmt->bind_param("isss", $userId, $title, $oldCategory, $description);
                } else {
                    $checkSql = "SELECT id FROM tasks WHERE user_id = ? AND task_title = ? AND category = ?";
                    $checkStmt = $_conn->prepare($checkSql);
                    $checkStmt->bind_param("iss", $userId, $title, $oldCategory);
                }
                
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                
                if ($result->num_rows === 0) {
                    error_log("Task not found: Title={$title}, Category={$oldCategory}, User={$userId}");
                    return ["status" => "error", "error" => "Task not found in original category"];
                }
                
                // If we have description, use it for more precision
                if ($description) {
                    $sql = "UPDATE tasks 
                           SET category = ? 
                           WHERE user_id = ? AND task_title = ? AND category = ? AND task_desc = ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("sisss", $newCategory, $userId, $title, $oldCategory, $description);
                } else {
                    $sql = "UPDATE tasks 
                           SET category = ? 
                           WHERE user_id = ? AND task_title = ? AND category = ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("siss", $newCategory, $userId, $title, $oldCategory);
                }
            } else {
                return ["status" => "error", "error" => "Missing required data for moving task"];
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to move task: " . $stmt->error);
            }
            
            if ($stmt->affected_rows === 0) {
                return ["status" => "error", "error" => "Task not found or no changes were made (affected rows=0)"];
            }
            
            // Check if old category is now empty of regular tasks (ignoring placeholder tasks)
            $checkSql = "SELECT 1 FROM tasks WHERE user_id = ? AND category = ? AND task_title != '_placeholder_' LIMIT 1";
            $checkStmt = $_conn->prepare($checkSql);
            $checkStmt->bind_param("is", $userId, $oldCategory);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            
            $categoryNowEmpty = $result->num_rows === 0;
            
            return [
                "status" => "success", 
                "message" => "Task moved successfully",
                "data" => [
                    "categoryNowEmpty" => $categoryNowEmpty,
                    "taskTitle" => isset($title) ? $title : "Task ID: " . $taskId,
                    "oldCategory" => $oldCategory,
                    "newCategory" => $newCategory
                ]
            ];
            
        } catch (Exception $e) {
            error_log("Error moving task: " . $e->getMessage());
            return ["status" => "error", "error" => $e->getMessage()];
        }
    }
    
    // Function to delete a task
    function deleteTask($data) {
        global $_conn, $userId;
        
        try {
            // Check if task ID is provided (preferred method)
            if (isset($data['task_id']) && !empty($data['task_id'])) {
                $taskId = intval($data['task_id']);
                
                // Delete task by ID - most specific and reliable
                $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
                $stmt = $_conn->prepare($sql);
                $stmt->bind_param("ii", $taskId, $userId);
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to delete task: " . $stmt->error);
                }
                
                if ($stmt->affected_rows === 0) {
                    return ["status" => "error", "error" => "Task not found"];
                }
                
                // Get the category from the data if available
                $category = isset($data['category']) ? $data['category'] : null;
                
                // If category wasn't provided, we can't check if it's empty
                if (!$category) {
                    return ["status" => "success", "message" => "Task deleted successfully"];
                }
                
            } else if (isset($data['title'], $data['category'])) {
                // Fallback to the legacy title+category method
                $title = $data['title'];
                $category = $data['category'];
                
                // If description is provided, use it for more precise identification
                if (isset($data['description']) && !empty($data['description'])) {
                    $description = $data['description'];
                    $sql = "DELETE FROM tasks 
                           WHERE user_id = ? AND task_title = ? AND category = ? AND task_desc = ?";
                           
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("isss", $userId, $title, $category, $description);
                } else {
                    // Basic identification by title and category only
                    $sql = "DELETE FROM tasks 
                           WHERE user_id = ? AND task_title = ? AND category = ?";
                           
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("iss", $userId, $title, $category);
                }
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to delete task: " . $stmt->error);
                }
                
                if ($stmt->affected_rows === 0) {
                    return ["status" => "error", "error" => "Task not found"];
                }
            } else {
                return ["status" => "error", "error" => "Missing task identification data"];
            }
            
            // Check if category is now empty of regular tasks (ignoring placeholder tasks)
            $checkSql = "SELECT 1 FROM tasks WHERE user_id = ? AND category = ? AND task_title != '_placeholder_' LIMIT 1";
            $checkStmt = $_conn->prepare($checkSql);
            $checkStmt->bind_param("is", $userId, $category);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            
            $categoryNowEmpty = $result->num_rows === 0;
            
            return [
                "status" => "success", 
                "message" => "Task deleted successfully",
                "data" => [
                    "categoryNowEmpty" => $categoryNowEmpty
                ]
            ];
            
        } catch (Exception $e) {
            return ["status" => "error", "error" => $e->getMessage()];
        }
    }
    
    // Call the main function to handle the request
    handleRequests();
?>
