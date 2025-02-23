<?php 
    // $_GET["groupName"];
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        echo $_SERVER['REQUEST_METHOD'];
        if (isset($_POST['GROUPNAMECHOICE']) && isset($_POST['USERTASK'])){
            $groupchoice = $_POST['GROUPNAMECHOICE'];
            $taskContent = $_POST['USERTASK'];
            $groupchoice = htmlspecialchars($groupchoice[0], ENT_QUOTES, 'UTF-8');
            $taskContent = htmlspecialchars($taskContent, ENT_QUOTES, 'UTF-8');
    
            echo "Selected Group: " . $groupchoice . "<br>";
            echo "Task Content: " . $taskContent . "<br>";
        }else if (isset($_POST['GROUPNAME'])) {
            $groupName = $_POST['GROUPNAME'];
            $groupName = htmlspecialchars($groupName, ENT_QUOTES, 'UTF-8');

            echo "Group name: " . $groupName . "<br>";
        }
        else{
            echo "missing form data";
        }
    }else{
        echo "HI";
        echo $_SERVER['REQUEST_METHOD'];
    }
    // $task = $_POST["USERTASK"];
    // $GroupChoice = $_POST["GROUPNAMECHOICE"];
    // $GroupName = $_POST["GROUPNAME"];
    
?>