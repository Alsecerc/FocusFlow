
<?php 
echo $_POST['GROUPNAMECHOICE'][0];

    // $_GET["groupName"];
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['GROUPNAMECHOICE']) && isset($_POST['USERTASK'])){
            $groupName = $_POST['GROUPNAMECHOICE'];
            $taskContent = $_POST['USERTASK'];
            $groupName = htmlspecialchars($groupName[0], ENT_QUOTES, 'UTF-8');
            $taskContent = htmlspecialchars($taskContent, ENT_QUOTES, 'UTF-8');
    
            echo "Selected Group: " . $groupName . "<br>";
            echo "Task Content: " . $taskContent . "<br>";
        }else{
            echo "missing form data";
        }
    }else{
        echo $_SERVER['REQUEST_METHOD'];
    }
    // $task = $_POST["USERTASK"];
    // $GroupChoice = $_POST["GROUPNAMECHOICE"];
    // $GroupName = $_POST["GROUPNAME"];
    
?>