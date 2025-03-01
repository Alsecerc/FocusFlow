<?php 
    session_start();
    include "conn.php";

    // Initialize response function for early errors
    function sendJsonResponse($data) {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // First check database connection
    if (!isset($_conn) || $_conn->connect_error) {
        sendJsonResponse(["error" => "Database connection failed"]);
        exit;
    }

    // Then check authentication
    if (!isset($_COOKIE['UID'])) {
        sendJsonResponse(["error" => "User not authenticated"]);
        exit;
    }

    // After validation, set up global variables
    $GLOBALS['userId'] = $_COOKIE['UID'];
    $GLOBALS['conn'] = $_conn;

    // Now call the main function
    SendfinalDate();

    function SendfinalDate() {
        // Access globals properly
        global $conn, $userId;

        // Set error handling
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        header("Content-Type: application/json");
        
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            
            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                handleGetRequest();
            } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
                handlePostRequest($data);
            }
        } catch (Exception $e) {
            ob_clean();
            echo json_encode(["error" => $e->getMessage()]);
            exit;
        }
    }

    function handleGetRequest() {
        if (!isset($_GET['type'])) {
            sendJsonResponse(["error" => "No type specified"]);
        }

        $type = $_GET['type'];
        $response = [];

        switch ($type) {
            case "FinalDate":
                $response = [
                    "Date" => ["Date"],
                    "Time" => ["Time"]
                ];
                break;
            case "users":
                $response = ["users" => ["Alice", "Bob", "Charlie"]];
                break;
            case "products":
                $response = ["products" => ["Laptop", "Phone", "Tablet"]];
                break;
            default:
                $response = ["error" => "Invalid request type"];
        }
        sendJsonResponse($response);
    }

    function handlePostRequest($data) {
        // Access globals properly
        global $conn, $userId;

        if (!$data || !isset($data['type'])) {
            sendJsonResponse(["error" => "No data received"]);
        }

        switch ($data['type']) {
            case "fetch_task":
                handleFetchTask($data, $conn, $userId);
                break;
            case "update_task":
                handleUpdateTask($data);
                break;
            default:
                sendJsonResponse(["error" => "Invalid request type"]);
        }
    }

    function handleFetchTask($data, $conn, $userId) {
        if (!isset($data['Category'], $data['title'], $data['Content'])) {
            sendJsonResponse(["error" => "Missing required parameters"]);
        }

        try {
            // Sanitize inputs
            $category = filter_var($data['Category'], FILTER_SANITIZE_STRING);
            $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
            $content = filter_var($data['Content'], FILTER_SANITIZE_STRING);

            $sql = "SELECT end_date, end_time FROM tasks 
                   WHERE task_title = ? AND task_desc = ? AND category = ? AND user_id = ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation failed");
            }

            $stmt->bind_param('sssi', $title, $content, $category, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                sendJsonResponse(["error" => "Task not found"]);
            }

            $row = $result->fetch_assoc();
            sendJsonResponse([
                "status" => "success",
                "data" => [
                    "date" => $row['end_date'],
                    "time" => $row['end_time'],
                    "category" => $category,
                    "title" => $title,
                    "content" => $content
                ]
            ]);
        } catch (Exception $e) {
            sendJsonResponse(["error" => $e->getMessage()]);
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    function handleUpdateTask($data) {
        if (!validateTaskData($data)) {
            sendJsonResponse(["error" => "Invalid task data"]);
        }

        try {
            // Add your database update logic here
            $response = [
                "status" => "success",
                "message" => "Task updated successfully",
                "data" => $data
            ];
            sendJsonResponse($response);
        } catch (Exception $e) {
            sendJsonResponse(["error" => $e->getMessage()]);
        }
    }

    function validateTaskData($data) {
        $required = ['cate', 'title', 'content', 'time', 'date'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Inserts data into the database using a prepared statement.
     *
     * @param mysqli $conn The MySQLi connection object.
     * @param string $sql The SQL query with placeholders.
     * @param string $types A string of types corresponding to the placeholders (e.g., "sssi").
     * @param mixed ...$params The values to bind to the placeholders.
     *
     * @return void
     */
    function sqlInsertion($DataBaseConn, $sql, $types, ...$params): void{
        $stmt = $DataBaseConn->prepare($sql);
    
        if(!$stmt){
            die("prepare failed: ". $DataBaseConn->error);
        }

        $bindParams = [];
        $bindParams[] = $types;
        // You need to bind by reference, so loop through params

        if (empty($params)) {
            echo "No parameters provided.";
            return;
        }
        
        foreach ($params as $key => $value) {
            $bindParams[] = &$params[$key];
        }
    
        // Bind parameters using call_user_func_array
        call_user_func_array([$stmt, 'bind_param'], $bindParams);

        if($stmt->execute()){
            echo "<alert>'executed'</alert>";
        }else{
            echo " failed to execute";
        }
        $stmt->close();
        $DataBaseConn->close();
    }

    function rowExistance ($user_id, $GroupName, $DataBaseConn): bool{
        $sql = "SELECT 1 FROM tasks WHERE user_id = ? AND task_title = ?";

        $STMT = $DataBaseConn->prepare($sql);
        if(!$STMT){
            die("prepare failed: ". $DataBaseConn->error);
        }
        $STMT->bind_param('is', $user_id, $GroupName);
        $STMT->execute();
        $STMT->store_result();

        if($STMT->num_rows > 0){
            $STMT->close();
            return true;
        }else{
            $STMT->close();
            return false;
        }
    }
?>