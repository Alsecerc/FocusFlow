<!-- change to .php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Our Features</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="index.css">

    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
</head>

<body>
    <header>
        <div class="HEADER__LEFT">
            <a href="Homepage.php">
                <h1 class="HEADER__TITLE CLICKABLE">F<span class="material-symbols-outlined HEADER__ICON">
                        schedule
                    </span>cusFlow
                </h1>
            </a>
            <nav>
                <ul class="HEADER__LIST">
                    <li class="HEADER__DROPDOWN_MENU CLICKABLE" id="FEATURES">Features
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

                    <li class="HEADER__DROPDOWN_MENU CLICKABLE" id="PLAN">Plans & Pricing
                        <span class="material-symbols-outlined ARROW">
                            arrow_drop_down
                        </span>
                    </li>
                    <div class="HEADER__DROPDOWN" id="PLAN">
                        <a class="DROPDOWN__ITEM" href="Plans.php">View Plan</a>
                    </div>

                    <li class="HEADER__DROPDOWN_MENU CLICKABLE" id="CONTACT">Contact Us
                        <span class="material-symbols-outlined ARROW">
                            arrow_drop_down
                        </span>
                    </li>
                    <div class="HEADER__DROPDOWN" id="CONTACT">
                        <a class="DROPDOWN__ITEM" href="">Customer Service</a>
                        <a class="DROPDOWN__ITEM" href="">Help Center</a>
                        <a class="DROPDOWN__ITEM" href="">Social Media Links</a>
                    </div>
                </ul>
            </nav>
        </div>
        <div class="HEADER__SIGNUP CTA">
            SIGN UP
        </div>
    </header>

    <main>
        <h1 class="ARTICLE_TITLE CENTER">Explore Our Features</h1>
        <h3 class="SUBTITLE CENTER">Your Productivity, <span style="color: #0077B6; font-size:1.5rem;">Upgraded</span></h3>

        <article class="FEATURE__CONTAINER DISPLAY" id="pomodoro">
            <img src="img/TIMER.png" alt="Pomodoro Timer" class="FEATURE__IMAGE">
            <div>
                <h1 class="FEATURE_TITLE">POMODORO TIMER</h1>
                <p class="FEATURE_TEXT">Boost your focus and productivity with the Pomodoro Technique! Work in 25-minute sessions, followed by short breaks to stay fresh and efficient. Perfect for tackling tasks without burnout! <a href="https://www.pomodorotechnique.com/" class="POMODORO__LINK" target="_blank">Find Out More</a></p>
            </div>
        </article>

        <article class="FEATURE__CONTAINER DISPLAY" id="task">
            <div>
                <h1 class="FEATURE_TITLE">Task Management</h1>
                <p class="FEATURE_TEXT">Stay organized and on top of your to-dos! Easily create, prioritize, and track tasks to boost efficiency and stay focused on what matters most. </p>
            </div>
            <img src="img\TASK_MANAGEMENT.png" alt="Task Management"
                class="FEATURE__IMAGE">
        </article>

        <article class="FEATURE__CONTAINER DISPLAY" id="collaboration">
            <img src="img\TEAM_FEATURES.png" alt="Task Management"
                class="FEATURE__IMAGE">
            <div>
                <h1 class="FEATURE_TITLE">Team Collaboration</h1>
                <p class="FEATURE_TEXT">Streamline teamwork by sharing tasks, tracking progress, and staying connected in real-time. Collaborate effectively and achieve your goals faster!</p>
            </div>
        </article>

        <article class="FEATURE__CONTAINER DISPLAY" id="analytic">
            <div>
                <h1 class="FEATURE_TITLE">Analytics</h1>
                <p class="FEATURE_TEXT">The Analytics function analyzes work history to identify patterns and suggest tasks, helping users prioritize efficiently and optimize productivity.</p>
            </div>
            <img src="img\ANALYTICS.png" alt="Analytics"
                class="FEATURE__IMAGE">
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
                <li><a href="">Get Help</a></li>
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
        <a href="#">
            <span class="material-symbols-outlined">
                stat_3
            </span>
        </a>
    </div>

    <script src="index.js"></script>
</body>

</html>