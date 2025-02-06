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
    <link rel="stylesheet" href="index.css">
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
        <div class="PAGE__TITLE">
            <h1 class=" ARTICLE_TITLE">Choose Your Focus Plan</h1>
            <h3>Invest in Your Focus, One Plan at a Time</h3>
            <button class="TYPE">
                <div class="TYPE__MONTH">M</div>
                <!-- annually or monthly -->
            </button>
        </div>
        <article class="PLANS">
            <div class="PLANS__SIDEBAR">
                <div class="PLANS_PRICING">
                    <h2 class="PLANS__TITLE CENTER">Features</h2>
                </div>
                <ul class="SIDEBAR__LIST">
                    <li class="SIDEBAR__ITEM">Task Management</li>
                    <li class="SIDEBAR__ITEM">Focus Mode</li>
                    <li class="SIDEBAR__ITEM">Pomodoro Timer</li>
                    <li class="SIDEBAR__ITEM">Team Collaboration</li>
                    <li class="SIDEBAR__ITEM">File Storage</li>
                    <li class="SIDEBAR__ITEM">Admin Controls & User Roles</li>
                    <li class="SIDEBAR__ITEM">Calendar & Habit Tracking</li>
                    <li class="SIDEBAR__ITEM">Productivity Analytics</li>
                </ul>
            </div>
            <div class="PLANS__CONTAINER">
                <div class="PLANS_PRICING">
                    <h3 class="PLANS__TITLE">FREE</h3>
                    <h5 id="PLANS__PRICE">RM0/month</h5>
                </div>
                <ul class="PLANS__LIST">
                    <!-- use js to fill in -->
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                </ul>
                <button class="PLANS__BUTTON CLICKABLE">Choose Free</button>
            </div>

            <div class="PLANS__CONTAINER">
                <div class="PLANS_PRICING">
                    <span class="PLANS__TITLE__SELECTED">(Most Selected)</span>
                    <h3 class="PLANS__TITLE">PREMIUM</h3>
                    <h5 id="PLANS__PRICE">RM49/month</h5>
                </div>
                <ul class="PLANS__LIST">
                    <!-- use js to fill in -->
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                </ul>
                <button class="PLANS__BUTTON CLICKABLE">Choose Premium</button>
            </div>

            <div class="PLANS__CONTAINER">
                <div class="PLANS_PRICING">
                    <h3 class="PLANS__TITLE">ENTERPRISE</h3>
                    <h5 id="PLANS__PRICE">RM100/month</h5>
                </div>
                <ul class="PLANS__LIST">
                    <!-- use js to fill in -->
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                    <li class="PLANS_ITEM"></li>
                </ul>
                <button class="PLANS__BUTTON CLICKABLE">Choose Enterprise</button>
            </div>

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