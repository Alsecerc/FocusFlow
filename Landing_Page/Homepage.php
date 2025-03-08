<!-- hellp -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FocusFlow</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="Responsive.css">
</head>

<body>
    <header>
        <div class="HEADER__LEFT">
            <!--For phone size  -->
            <!-- Hamburger Menu Icon -->
            <div class="MENU_ICON" onclick="toggleSidebar()">
                <span class="material-icons">menu</span>
            </div>

            <a href="Homepage.php">
                <h1 class="HEADER__TITLE CLICKABLE">F<span class="material-symbols-outlined HEADER__ICON">
                        schedule
                    </span>cusFlow
                </h1>
            </a>
            <nav>
                <ul class="HEADER__LIST original">
                    <li class="HEADER__DROPDOWN_MENU CLICKABLE" id="FEATURES" data-short="Feat">Features
                        <span class="material-symbols-outlined ARROW">
                            arrow_drop_down
                        </span>
                    </li>
                    <div class="HEADER__DROPDOWN" id="FEATURES">
                        <a class="DROPDOWN__ITEM" href="Features.php#pomodoro">Pomodoro Timer</a>
                        <a class="DROPDOWN__ITEM" href="Features.php#task">Task Management</a>
                        <a class="DROPDOWN__ITEM" href="Features.php#collaboration">Team Features</a>
                        <a class="DROPDOWN__ITEM" href="Features.php#analytic">Analytics</a>
                    </div>

                    <li class="HEADER__DROPDOWN_MENU CLICKABLE" id="PLAN" data-short="Plan">Plans & Pricing
                        <span class="material-symbols-outlined ARROW">
                            arrow_drop_down
                        </span>
                    </li>
                    <div class="HEADER__DROPDOWN" id="PLAN">
                        <a class="DROPDOWN__ITEM" href="Plans.php">View Plan</a>
                    </div>

                    <li class="HEADER__DROPDOWN_MENU CLICKABLE" id="CONTACT" data-short="Help">Contact Us
                        <span class="material-symbols-outlined ARROW">
                            arrow_drop_down
                        </span>
                    </li>
                    <div class="HEADER__DROPDOWN" id="CONTACT">
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#contactus">Customer Service</a>
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#FAQ">FAQ</a>
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#feedback">Feedback</a>
                    </div>
                </ul>
            </nav>


            <!-- For phone sized -->
            <!-- Sidebar Navigation -->
            <nav class="SIDEBAR">
                <ul class="HEADER__LIST">

                    <div class="HEADER__DROPDOWN SB" id="FEATURES">
                        <a class="DROPDOWN__ITEM" href="Features.php#pomodoro">Pomodoro Timer</a>
                        <a class="DROPDOWN__ITEM" href="Features.php#task">Task Management</a>
                        <a class="DROPDOWN__ITEM" href="Features.php#collaboration">Team Features</a>
                        <a class="DROPDOWN__ITEM" href="Features.php#analytic">Analytics</a>
                    </div>

                    <div class="HEADER__DROPDOWN SB" id="PLAN">
                        <a class="DROPDOWN__ITEM" href="Plans.php">View Plan</a>
                    </div>

                    <div class="HEADER__DROPDOWN SB" id="CONTACT">
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#contactus">Customer Service</a>
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#FAQ">FAQ</a>
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#feedback">Feedback</a>
                    </div>


                    <div class="CTA__GROUP SB">
                        <div class="HEADER__LOGIN CTA">
                            <a href="../RegisterLayout/Login.php">LOGIN</a>
                        </div>
                        <div class="HEADER__SIGNUP CTA">
                            <a href="../RegisterLayout/Signup.php">SIGN UP</a>
                        </div>
                    </div>
                </ul>


            </nav>


        </div>
        <div class="CTA__GROUP">
            <div class="HEADER__LOGIN CTA">
                <a href="../RegisterLayout/Login.php">LOGIN</a>
            </div>
            <div class="HEADER__SIGNUP CTA">
                <a href="../RegisterLayout/Signup.php">SIGN UP</a>
            </div>
        </div>
    </header>

    <main>
        <article class="WELCOME DISPLAY" id="welcome">
            <section class="WELCOME__CONTAINER">
                <div class="WELCOME__TITLE">
                    <h1>Boost Your <span class="KEYWORD">Productivity</span> Today!</h1>
                    <h3>Manage your time effectively with our smart task management and calendar system.</h3>
                    <div class="WELCOME__CTA">
                        <a class="HEADER__SIGNUP CLICKABLE CTA" href="../RegisterLayout/Signup.php">GET STARTED</a>
                        <a class="HEADER__LOGIN CLICKABLE" href="Plans.php">VIEW PLAN</a>
                    </div>
                </div>
                <img src="img\TITLE_IMAGE.png" alt="Productive Person" class="WELCOME__IMAGE">
            </section>
        </article>

        <article class="BENEFIT DISPLAY" id="benefit">
            <h2 class="CENTER ARTICLE_TITLE">Service and Benefits</h2>
            <div class="BENEFIT__CONTAINER">

                <div class="BENEFIT__CONTAINER__CARD">
                    <div class="BENEFIT__FRONT">
                        <img src="img/STATS.png" alt="Managing Task" class="BENEFIT__IMAGE">
                        <div>
                            <h5 class="BENEFIT__TITLE">Task Scheduling & Management </h5>
                        </div>
                    </div>
                    <div class="BENEFIT__BACK">
                        <div>
                            Stay organized and on track by <strong>scheduling</strong>, <strong>prioritizing</strong>, and <strong>managing tasks</strong> with ease. Boost productivity with timely <strong>reminders</strong> and <strong>progress tracking</strong>.
                        </div>
                    </div>
                </div>

                <div class="BENEFIT__CONTAINER__CARD">
                    <div class="BENEFIT__FRONT">
                        <img src="img/CHAT.png" alt="Community Chat" class="BENEFIT__IMAGE">
                        <div>
                            <h5 class="BENEFIT__TITLE">Community & Collaboration</h5>
                        </div>
                    </div>
                    <div class="BENEFIT__BACK">
                        <div>
                            Enhance teamwork with <strong>Community & Collaboration</strong>. Create <strong>workspaces</strong> for <strong>team communication</strong>, <strong>project management</strong>, and <strong>real-time collaboration</strong>. Set up <strong>channels</strong> for <strong>discussions</strong> and use <strong>DMs</strong> for <strong>private conversations</strong> to keep your team <strong>connected</strong>.
                        </div>
                    </div>
                </div>

                <div class="BENEFIT__CONTAINER__CARD">
                    <div class="BENEFIT__FRONT">
                        <img src="img/NOTI.png" alt="Receive Notifications" class="BENEFIT__IMAGE">
                        <div>
                            <h5 class="BENEFIT__TITLE">Smart Notifications & Reminders</h5>
                        </div>
                    </div>
                    <div class="BENEFIT__BACK">
                        <div>
                            Stay on top of your tasks with <strong>Smart Notifications & Reminders</strong>. Get <strong>real-time updates</strong> on <strong>assignments</strong>, <strong>deadlines</strong>, and <strong>team activities</strong>, ensuring you never miss an important update. Customize <strong>reminders</strong> for upcoming tasks and receive <strong>alerts</strong> tailored to your workflow—keeping you <strong>productive</strong> and <strong>focused</strong> at all times!
                        </div>
                    </div>
                </div>

                <div class="BENEFIT__CONTAINER__CARD">
                    <div class="BENEFIT__FRONT">
                        <img src="img/CALENDAR.png" alt="View Calendar" class="BENEFIT__IMAGE">
                        <div>
                            <h5 class="BENEFIT__TITLE">Multi-view Calendar (Day, Week, Month)</h5>
                        </div>
                    </div>
                    <div class="BENEFIT__BACK">
                        <div>
                            Visualize your tasks effortlessly with the <strong>Multi-View Calendar</strong>. Switch between <strong>Day</strong>, <strong>Week</strong>, and <strong>Month</strong> views to see upcoming <strong>deadlines</strong> and <strong>assignments</strong> at a glance. Each task appears with a brief description, showing its <strong>duration</strong> and <strong>due date</strong>—helping you stay <strong>organized</strong> and manage your time effectively.
                        </div>
                    </div>
                </div>

            </div>
        </article>

        <article class="REVIEW DISPLAY">
            <!-- put profile pic -->
            <!-- able to click left or right to view / default is animate auto browse -->
            <h2 class="CENTER ARTICLE_TITLE">User Review</h2>
            <section class="REVIEW__CARD">
                <img class="REVIEW__CARD__PROFILE" src="" alt="Review Person">
                <div class="REVIEW_STAR">
                </div>
                <q class="REVIEW__COMMENT"></q>
                <!-- use after/ before to create arrow to scroll -->
                <button class="REVIEW__BUTTON_LEFT"><span class="material-symbols-outlined">
                        keyboard_double_arrow_left
                    </span></button>
                <button class="REVIEW__BUTTON_RIGHT"><span class="material-symbols-outlined">
                        keyboard_double_arrow_right
                    </span></button>

            </section>
        </article>
    </main>

    <footer class="FOOTER">

        <div class="FOOTER__COMPANY">
            <h3>F<span class="material-symbols-outlined FOOTER__TITLE_ICON">schedule</span>cusFlow</h3>
        </div>

        <div class="FOOTER__LINK">
            <h4 class="FOOTER__LINK__TITLE">Contact</h4>
            <ul>
                <li><a href="Homepage.php#benefit">Our Service</a></li>
                <li><a href="GetHelp.php">Get Help</a></li>
            </ul>
        </div>

        <div>
            <h4 class="FOOTER__LINK__TITLE">About Us</h4>
            <ul>
                <li>
                    <address>Lot 4220 Persimpangan Jalan Batu Arang, Lebuh Raya Plus, Rawang</address>
                </li>
                <li>Call Us: <a href="tel:+600361387175">+60 (0)3 6138-7175</a></li>
            </ul>
        </div>
    </footer>

    <div class="BACK_TO_TOP CLICKABLE">
        <a href="#welcome">
            <span class="material-symbols-outlined">
                stat_3
            </span>
        </a>
    </div>

    <script src="index.js"></script>

</body>

</html>