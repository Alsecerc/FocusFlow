<!-- change to .php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support</title>

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

        <article class="CS__Content">
            <section class="CS__FAQ">
                <h1 class="ARTICLE_TITLE">FAQ</h1>
                <div class="dropdown">
                    <h2>General</h2>
                    <div class="dropdown-container">
                        <button class="dropdown-button">
                            <h3>What is this website about?</h3>
                        </button>
                        <ul class="dropdown-list">
                            <li class="dropdown-item">Our website is a productivity platform designed to help users manage tasks, track time, set goals, and boost efficiency. Whether you're a student, professional, or entrepreneur, our tools can help you stay organized.</li>
                        </ul>
                    </div>

                    <div class="dropdown-container">
                        <button class="dropdown-button">
                            <h3>Is this website free to use?</h3>
                        </button>
                        <ul class="dropdown-list">
                            <li class="dropdown-item">We offer a free plan with essential features. For advanced tools like team collaboration, analytics, and integrations, we have premium plans.</li>
                        </ul>
                    </div>

                    <div class="dropdown-container">
                        <button class="dropdown-button">
                            <h3>Do I need to create an account to use the website?</h3>
                        </button>
                        <ul class="dropdown-list">
                            <li class="dropdown-item">Yes, creating an account allows you to save your tasks, track progress, and sync across devices.</li>
                        </ul>
                    </div>
                </div>

                <div class="dropdown">
                    <h2>Task & Time Management</h2>
                    <div class="dropdown-container">
                        <button class="dropdown-button">
                            <h3>How can I create a new task?</h3>
                        </button>
                        <ul class="dropdown-list">
                            <li class="dropdown-item">Simply click the "Add Task" button, enter the task details, set a deadline, and save it. You can also categorize tasks by priority or project.</li>
                        </ul>
                    </div>

                    <div class="dropdown-container">
                        <button class="dropdown-button">
                            <h3>Does the website have a Pomodoro Timer?</h3>
                        </button>
                        <ul class="dropdown-list">
                            <li class="dropdown-item">Yes! Our built-in Pomodoro Timer helps you work in focused intervals (e.g., 25 minutes of work, 5 minutes of break) to boost productivity.</li>
                        </ul>
                    </div>
                </div>

                <div class="dropdown">
                    <h2>Support & Troubleshooting</h2>
                    <div class="dropdown-container">
                        <button class="dropdown-button">
                            <h3>I found a bug. How do I report it?</h3>
                        </button>
                        <ul class="dropdown-list">
                            <li class="dropdown-item">Please visit our Support Page and submit a bug report. You can also email us at focusflow@gmail.com.</li>
                        </ul>
                    </div>

                    <div class="dropdown-container">
                        <button class="dropdown-button">
                            <h3>Does the website have a Pomodoro Timer?</h3>
                        </button>
                        <ul class="dropdown-list">
                            <li class="dropdown-item">Yes! Our built-in Pomodoro Timer helps you work in focused intervals (e.g., 25 minutes of work, 5 minutes of break) to boost productivity.</li>
                        </ul>
                    </div>
                </div>


            </section>
        </article>


    </main>
    <script src="Registered.js" defer></script>
</body>

</html>