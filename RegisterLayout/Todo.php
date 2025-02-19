<!-- change to .php -->
<?php 
    // include 'conn.php';

session_start();

if (!isset($_COOKIE['userID'])) {
    echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
    exit();
}
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
        // echo "ERROR";
        // echo "Request Method: " . $_SERVER['REQUEST_METHOD'];
    }
    // $task = $_POST["USERTASK"];
    // $GroupChoice = $_POST["GROUPNAMECHOICE"];
    // $GroupName = $_POST["GROUPNAME"];
    
    // echo $task;
    // echo $GroupChoice;
    // echo $GroupName;



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
</head>

<body>
    <div class="Hiddenlayer" style="display: none;">
    </div>
    <header>
        <div class="HEADER__LEFT">
            <button class="HEADER__MENU_BUTTON">
                <div class="HEADER__MENU_ICON"></div>
            </button>
            <a href="Homepage.php">
                <h1 class="HEADER__TITLE">F<span class="material-symbols-outlined HEADER__ICON">
                        schedule
                    </span>cusFlow
                </h1>
            </a>
        </div>
        <div class="HEADER__SEARCH">
            <button class="HEADER__SEARCH_BUTTON">
                <span class="material-symbols-outlined">
                    search
                </span>
            </button>
            <input type="text" class="HEADER__SEARCH_INPUT" placeholder="Search...">
        </div>
        <div class="HEADER__RIGHT">
            <nav>
                <ul class="HEADER__UL">
                    <li>
                        <a href="CusService.php" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                support_agent
                            </span>
                        </a>
                    </li>
                    <li>
                        <div class="HEADER__UL__ICON">
                            <span class="material-icons">
                                notifications
                            </span>
                        </div>
                    </li>
                    <li>
                        <a href="Setting.php" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                settings
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="Account.php" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                account_circle
                            </span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="SIDEBAR">
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li>
                        <a href="Homepage.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                home
                            </span>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="Timer.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                timer
                            </span>Focus Timer
                        </a>
                    </li>
                    <li>
                        <a href="Todo.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                task_alt
                            </span>To Do
                        </a>
                    </li>
                    <li>
                        <a href="Calendar.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                event
                            </span>Calendar
                        </a>
                    </li>
                    <li>
                        <a href="Analytic.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                analytics
                            </span>Analytics
                        </a>
                    </li>
                </ul>
            </nav>
            <nav class="SIDEBAR__NAV COMMUNITY">
                <h4 class="NAV_TITLE">Community</h4>
                <ul>
                    <li>
                        <a href="" class="SIDEBAR__ITEM COMMUNITY__ITEM">
                            Channel 1
                            <button class="material-icons">
                                more_horiz
                            </button>
                        </a>
                    </li>
                </ul>
                <h4 class="NAV_TITLE">DM</h4>
                <ul>
                    <li>
                        <a href="" class="SIDEBAR__ITEM COMMUNITY__ITEM">
                            Person 1
                            <button class="material-icons">
                                more_horiz
                            </button>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- TODO LIST CONTENT -->
        <article class="TODO">
            <section class="TODO__HEADER">
                <h1>Task Management</h1>
                <div class="TODO__BUTTON">
                    <button class="TODO__ADD">Group<span class="material-icons">
                            add_circle
                        </span>
                    </button>

                    <button class="TODO__ADD">Task<span class="material-icons">
                            add_circle
                        </span>
                    </button>
                </div>
            </section>

            <section class="TODO__CONTAINER">
                <div class="TODO__CARD" id="To Do 1">
                    <h3 class="TODO__CARD_HEADER">To Do 1</h3>
                    <p class="TODO__TASK" draggable="true">Get grocery</p>
                </div>
            </section>
        </article>

    </main>

    <div class="TODO__GROUP__ADD" style="display: none;">
        <h2>Add a New Group</h2>
        <form id="groupForm" method="post">
            <input type="text" id="groupName" name= "GROUPNAME" value = "" placeholder="Enter group name" required>
            <button type="submit">Add Group</button>
        </form>
    </div>
    <script src="Registered.js" defer></script>
    <script src="Todo.js" defer></script>
</body>

</html>

