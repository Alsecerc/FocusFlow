<?php

session_start();

if (!isset($_COOKIE['userID'])) {
    echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Message</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
</head>

<body>
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
        <!-- temp SIDEBAR_SHOW -->
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
                        <a href="CommunityDMPage.php" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 1')">
                            Person 1
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage.php" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 2')">
                            Person 2
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <article class="DMPage">
            <section class="DMPAGE__HEADER">
                <div class="DMPAGE__HEADER2">
                    <span class="material-icons PROFILE_ICON">
                        face
                    </span>
                    <h1>James Carter</h1>
                </div>
                <span class="material-icons PROFILE_CAM">
                    videocam
                </span>
            </section>

            <section class="DMPAGE__CONVERSATION">
                <div class="CONVERSATION RECEIVE">Hey! How are you?</div>
                <div class="CONVERSATION SENT">I’m good, how about you?</div>
                <div class="CONVERSATION RECEIVE">Hey! How are you?</div>
                <div class="CONVERSATION SENT">I’m good, how about you?</div>
                <div class="CONVERSATION RECEIVE">Hey! How are you?</div>
                <div class="CONVERSATION SENT">I’m good, how about you?</div>
                <div class="CONVERSATION RECEIVE">Hey! How are you?</div>
                <div class="CONVERSATION SENT">I’m good, how about you?</div>
                <div class="CONVERSATION RECEIVE">Hey! How are you?</div>
                <div class="CONVERSATION SENT">I’m good, how about you?</div>
            </section>

            <section class="DMPAGE__MESSAGE">
                <div class="MESSAGE__BOX">
                    <input class= "ENTER__MESSAGE" type="text" name="message" id="message" placeholder="Type something...">
                    <button onclick="sendMSG()" class="SEND__MESSAGE"><span class="material-icons">
                            send
                        </span></button>
                </div>
            </section>

        </article>

    </main>
    <script src="Registered.js" defer></script>
    <script src="Community.js" defer></script>
</body>

</html>