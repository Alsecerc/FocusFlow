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
            <nav>
                <ul class="HEADER__UL">
                    <li>
                        <span class="material-symbols-outlined HEADING__CS_ICON">
                            support_agent
                        </span>

                    </li>
                    <li>
                        <span class="material-symbols-outlined HEADING__NOTIF">
                            notifications
                        </span>
                    </li>
                    <li>
                        <span class="material-symbols-outlined HEADING__PROFILE">
                            account_circle
                        </span>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <!-- temp SIDEBAR_SHOW -->
        <article class="SIDEBAR">
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li class="SIDEBAR__ITEM">
                        <span class="material-icons">
                            home
                        </span>Dashboard
                    </li>
                    <li class="SIDEBAR__ITEM">
                        <span class="material-icons">
                            timer
                        </span>Focus Timer
                    </li>
                    <li class="SIDEBAR__ITEM">
                        <span class="material-icons">
                            task_alt
                        </span>To Do List
                    </li>
                    <li class="SIDEBAR__ITEM">
                        <span class="material-icons">
                            event
                        </span>Calendar
                    </li>
                </ul>
            </nav>
            <nav class="SIDEBAR__NAV COMMUNITY">
                <h4 class="NAV_TITLE">Community</h4>
                <ul>
                    <li class="SIDEBAR__ITEM COMMUNITY__ITEM">
                        Channel 1
                        <button class="material-icons">
                            more_horiz
                        </button>
                    </li>
                </ul>
                <h4 class="NAV_TITLE">DM</h4>
                <ul>
                    <li class="SIDEBAR__ITEM COMMUNITY__ITEM">
                        Person 1
                        <button class="material-icons">
                            more_horiz
                        </button>
                    </li>
                </ul>
            </nav>
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li class="SIDEBAR__ITEM">
                        <span class="material-icons">
                            settings
                        </span>Settings
                    </li>
                </ul>
            </nav>
        </article>

        <!-- TODO LIST CONTENT -->
        <article class="TODO">
            <section class="TODO__HEADER">
                <h1>Task Management</h1>
                <div class="TODO__BUTTON">
                    <button class="TODO__ADD">Group<span class="material-icons">
                            add_circle
                        </span>
                    </button>
                    <div class="TODO__GROUP__ADD" style="display: none;">
                            <h2>Add a New Group</h2>
                            <form id="groupForm">
                            <input type="text" id="groupName" placeholder="Enter group name" required>
                            <button type="submit">Add Group</button>
                        </form>
                    </div>
                    <button class="TODO__ADD">Task<span class="material-icons">
                            add_circle
                        </span>
                    </button>
                </div>
            </section>
            <section class="TODO__CONTAINER">
                <div class="TODO__CARD" draggable="true">
                    <h3 class="TODO__CARD_HEADER">To Do 1</h3>
                    <p class="TODO__TASK">Get grocery</p>
                </div>
                <div class="TODO__CARD" draggable="true">
                    <h3 class="TODO__CARD_HEADER">To Do 1</h3>
                    <p class="TODO__TASK">Get grocery</p>
                </div>
            </section>
        </article>

    </main>
    <script src="Registered.js"></script>
</body>

</html>