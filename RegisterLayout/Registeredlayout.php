<!-- change to .php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusFlow</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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
        <div class="SIDEBAR">
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li><img src="" alt="x">Item one</li>
                    <li><img src="" alt="x">Item two</li>
                    <li><img src="" alt="x">Item three</li>
                    <li><img src="" alt="x">Item four</li>
                </ul>
            </nav>
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li><img src="" alt="x">Item one</li>
                    <li><img src="" alt="x">Item two</li>
                    <li><img src="" alt="x">Item three</li>
                    <li><img src="" alt="x">Item four</li>
                </ul>
            </nav>
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li>
                        <img src="img\SETTINGS_ICON.png" alt="Setting Icon" class="SIDEBAR__IMAGE">Settings
                    </li>
                </ul>
            </nav>
        </div>

    </main>
    <script src="Registered.js"></script>
</body>

</html>