<!-- change to .php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar </title>

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
            <a href="Homepage.php">
                <h1 class="HEADER__TITLE">F<span class="material-symbols-outlined HEADER__ICON">
                        schedule
                    </span>cusFlow
                </h1>
            </a>
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

        <!-- Calendar Content -->
        <article class="CONTAINER">
            <h1 class="ARTICLE_TITLE">Calendar</h1>
            <section class="CALENDAR">
                <div class="CALENDAR__HEADER">
                    <h1 class="CALENDAR__TITLE" id="calendar__title1"></h1>
                    <div class="TITLE__CONTAINER">
                        <div class="Header__Container">
                            <button class="Header__Button" onclick="goToToday()"><span class=" Header__Wording">Today</span></button>
                        </div>
                        <div class="Header__Container">
                            <button class="Header__Button"><span class="material-icons Header__Wording">add</span></button>
                        </div>
                        <div class="Header__Container">
                            <button onclick="toggleViewPrevious()" class="Header__Button SelectView" id="left"><span class="material-icons Header__Wording">arrow_left</span></button>
                            <span style="margin: 0 1rem;">Week</span>
                            <button onclick="toggleViewNext()" class="Header__Button SelectView" id="right"><span class="material-icons Header__Wording">arrow_right</span></button>
                        </div>
                        <!-- <div class="BUTTON__CONTAINER">
                        <button onclick="togglePeriod('week')" id="weekButton" class="CALENDAR__HEADER__BUTTON">W</button>
                        <button onclick="togglePeriod('month')" id="monthButton" class="CALENDAR__HEADER__BUTTON">M</button>
                        <button onclick="togglePeriod('year')" id="yearButton" class="CALENDAR__HEADER__BUTTON">Y</button>
                    </div> -->
                    </div>
                </div>

                <div class="CALENDAR__CONTENT">
                    <div class="CALENDAR__CONTENT__CONTAINER" id="weekContent">
                        <div class="HEADER">
                            <ul class="DAY_NAME">
                                <li>Sunday</li>
                                <li>Monday</li>
                                <li>Tuesday</li>
                                <li>Wednesday</li>
                                <li>Thursday</li>
                                <li>Friday</li>
                                <li>Saturday</li>
                            </ul>

                            <ul class="DAY_NUM">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>

                        <div class="TIMESLOT__CONTAINER">
                            <ul class="TIMESLOT">
                                <li>0:00</li>
                                <li>1:00</li>
                                <li>2:00</li>
                                <li>3:00</li>
                                <li>4:00</li>
                                <li>5:00</li>
                                <li>6:00</li>
                                <li>7:00</li>
                                <li>8:00</li>
                                <li>9:00</li>
                                <li>10:00</li>
                                <li>11:00</li>
                                <li>12:00</li>
                                <li>13:00</li>
                                <li>14:00</li>
                                <li>15:00</li>
                                <li>16:00</li>
                                <li>17:00</li>
                                <li>18:00</li>
                                <li>19:00</li>
                                <li>20:00</li>
                                <li>21:00</li>
                                <li>22:00</li>
                                <li>23:00</li>
                                <li>24:00</li>
                            </ul>

                        </div>

                        <div class="EVENT__CONTAINER">
                            <!-- how long / which day / what time -->
                            <div class="EVENT EVENT1">
                                <div class="EVENT__STATUS"></div>
                                <span class="EVENT__NAME">Event 1</span>
                            </div>
                        </div>

                    </div>

                    <!-- <div class="CALENDAR__CONTENT__CONTAINER" id="monthContent">Month</div> -->
                    <!-- <div class="CALENDAR__CONTENT__CONTAINER" id="yearContent">Year</div> -->
                </div>

            </section>
        </article>

    </main>
    <script src="Registered.js" defer></script>
</body>

</html>