<?php 
    session_start();
    include "conn.php";
    // update data to database
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $userId = $_COOKIE['UID'];
        if (isset($_POST['GROUPNAMECHOICE']) && isset($_POST['USERTASK'])){ // update when submit task form
            $groupchoice = $_POST['GROUPNAMECHOICE'][0];
            $taskContent = $_POST['USERTASK'];
            $groupchoice = htmlspecialchars($groupchoice, ENT_QUOTES, 'UTF-8');
            $taskContent = htmlspecialchars($taskContent, ENT_QUOTES, 'UTF-8');
            $taskTitle = $groupchoice[0];
            $taskDesc = $taskContent;
            $startDate = date('Y-m-d');
            $start_time = date('H:i:s');
            $endTime = (isset($_POST['END__TIME']) && !empty($_POST['END__TIME'])) ? $_POST['END__TIME'] : date('H:i:s', strtotime('+1 hour'));  // add 1 hour by default
            $sql = "INSERT INTO tasks (task_title, task_desc, start_date, start_time, end_time, user_id) VALUES (?, ?, ?, ?, ?, ?)";

            if (rowExistance($userId, $groupchoice, $_conn)){
                sqlInsertion($_conn, $sql, 'sssssi', $taskTitle, $taskDesc, $startDate, $start_time, $endTime, $userId);
            }else{
                echo"Row does not exists";
                $_conn->close();
            }
    
            echo "user id:". $userId. "<br>";
            echo "Selected Group: " . $groupchoice . "<br>";
            echo "Task Content: " . $taskContent . "<br>";

        }else if (isset($_POST['GROUPNAME'])) {
            // update when submit group form
            date_default_timezone_set('Asia/Kuala_Lumpur');

            $taskTitle = $_POST['GROUPNAME'];
            $taskTitle = htmlspecialchars($taskTitle, ENT_QUOTES, 'UTF-8');

            $sql = "INSERT INTO tasks (task_title, user_id) VALUES (?, ?)";

            sqlInsertion($_conn, $sql, 'si',$taskTitle, $userId);

            echo "Group name: " . $taskTitle . "<br>";
        }
        else{
            echo "missing form data";
        }
    }else{
        echo "Request method:" . $_SERVER['REQUEST_METHOD']. "<br>";
    }
    header('Content-Type: application/json');
    $data = ["name" => "Alice", "age" => 30];
    json_encode($data);
    // $task = $_POST["USERTASK"];
    // $GroupChoice = $_POST["GROUPNAMECHOICE"];
    // $GroupName = $_POST["GROUPNAME"];

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