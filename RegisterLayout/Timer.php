<?php

session_start();

if (!isset($_SESSION['userID'])) {
    echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img/SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
</head>

<body>

    <div class="TIMER__BODY">
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
                            <a href="../Landing_Page/GetHelp.php" target="_blank" class="HEADER__UL__ICON">
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
        <div id="timer-message">Please select a timer before starting.</div>

        <div class="container">
        <div class="SIDEBAR" style="overflow-y: auto;">
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
                    <li>
                        <a href="Goal.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                track_changes
                                </span>Goals
                        </a>
                    </li>
                </ul>
            </nav>
            <nav class="SIDEBAR__NAV COMMUNITY">
                <h4 class="NAV_TITLE">Community</h4>
                <ul>
                    <li>
                        <a href="CommunityPage.php" class="SIDEBAR__ITEM COMMUNITY__ITEM">
                            Channel 1
                            <button class="material-icons">
                                more_horiz
                            </button>
                        </a>
                    </li>
                </ul>
                <h4 class="NAV_TITLE">Direct Messages</h4>
                <ul class="DM_USER_LIST">
                    <li>
                        <a href="CommunityDMPage?receiver_id=3&name=Michael+Brown" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 1')">
                            Micheal Brown
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage?receiver_id=2&name=Jane+Smith" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 2')">
                            Jane Smith
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage?receiver_id=4&name=Sarah+Lee" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 2')">
                        Sarah Lee
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
            <div class="timer">
                <h1>⏰ Pomodoro Timer</h1>

                <div class="button-container">
                    <button class="button" id="pomodoro-session">Pomodoro</button>
                    <button class="button" id="short-break">Short Break</button>
                    <button class="button" id="long-break">Long Break</button>
                </div>

                <main class="MAIN__TIMER">
                    <div class="timer-wrapper">
                        <!-- Plus and Minus Buttons (Stacked) -->
                        <div class="adjust-buttons">
                            <button id="plus-btn" class="adjust-btn">+</button>
                            <button id="minus-btn" class="adjust-btn">−</button>
                        </div>

                        <!-- Timer Display -->
                        <div class="timer-display active">
                            <h1 class="time"><span id="pomodoro-timer"></span></h1>
                            <h1 class="time"><span id="short-timer"></span></h1>
                            <h1 class="time"><span id="long-timer"></span></h1>
                        </div>
                    </div>
                </main>

                <div class="buttons">
                    <button id="start">START</button>
                    <button id="stop">STOP</button>
                </div>
            </div>
        </div>
    </div>

    <script src="Registered.js" defer></script>
    <script src="Timer.js" defer></script>
</body>

</html>