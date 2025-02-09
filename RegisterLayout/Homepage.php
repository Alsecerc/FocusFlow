<!-- change to .php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusFlow</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
</head>

<body>
    <header>
        <div class="HEADER__LEFT">
            <button class="HEADER__MENU_BUTTON">
                <div class="HEADER__MENU_ICON"></div>
            </button>
            <h1 class="HEADER__TITLE">F<span class="material-symbols-outlined HEADER__ICON">
                    schedule
                </span>cusFlow</h1>
        </div>
        <div class="HEADER__SEARCH">
            <input type="text" class="HEADER__SEARCH_INPUT" placeholder="Search...">
            <button class="HEADER__SEARCH_BUTTON">
                <span class="material-symbols-outlined">
                    search
                </span>
            </button>
        </div>
        <div class="HEADER__RIGHT">
            <!-- TODO: ADD customer service/ noti/ profile icon -->
            <nav>
                <ul class="HEADER__UL">
                    <li>
                        <a href="" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                support_agent
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="" class="HEADER__UL__ICON">
                        <span class="material-icons">
                            notifications
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                settings
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="" class="HEADER__UL__ICON">
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
                        <a href="" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                home
                            </span>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                timer
                            </span>Focus Timer
                        </a>
                    </li>
                    <li>
                        <a href="" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                task_alt
                            </span>To Do List
                        </a>
                    </li>
                    <li>
                        <a href="" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                event
                            </span>Calendar
                        </a>
                    </li>
                    <li>
                        <a href="" class="SIDEBAR__ITEM">
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

    </main>
    <script src="Registered.js"></script>
</body>

</html>