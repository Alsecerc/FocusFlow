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
    <link rel="stylesheet" href="Features.css">
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
                        <a class="DROPDOWN__ITEM" href="">Pomodoro Timer</a>
                        <a class="DROPDOWN__ITEM" href="">Task Management</a>
                        <a class="DROPDOWN__ITEM" href="">Team Features</a>
                        <a class="DROPDOWN__ITEM" href="">Analytics</a>
                    </div>

                    <li class="HEADER__DROPDOWN_MENU CLICKABLE" id="PLAN">Plans & Pricing
                        <span class="material-symbols-outlined ARROW">
                            arrow_drop_down
                        </span>
                    </li>
                    <div class="HEADER__DROPDOWN" id="PLAN">
                        <a class="DROPDOWN__ITEM" href="">Poop</a>
                        <a class="DROPDOWN__ITEM" href="">Poop</a>
                        <a class="DROPDOWN__ITEM" href="">Poop</a>
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

        <article class="FEATURE__CONTAINER">
            <div>
                <img src="img/POMODORO_TIMER.png" alt="Pomodoro Timer" class="FEATURE__IMAGE">
                <div>
                    <h1 class="FEATURE_TITLE">POMODORO TIMER</h1>
                    <p class="FEATURE_TEXT">Boost your focus and productivity with the Pomodoro Technique! Work in 25-minute sessions, followed by short breaks to stay fresh and efficient. Perfect for tackling tasks without burnout! ⏳🚀</p>
                </div>
            </div>
        </article>

        <article class="FEATURE__CONTAINER">
            <img src="img/POMODORO_TIMER.png" alt="Pomodoro Timer">

        </article>
        <!-- <article class="FEATURE__CONTAINER">
            <img src="img/POMODORO_TIMER.png" alt="Pomodoro Timer">

        </article>
        <article class="FEATURE__CONTAINER">
            <img src="img/POMODORO_TIMER.png" alt="Pomodoro Timer">

        </article> -->

    </main>

    <footer class="FOOTER">
        <div class="FOOTER__COMPANY">
            <h3>F<span class="material-symbols-outlined FOOTER__TITLE_ICON">schedule</span>cusFlow</h3>
        </div>

        <div class="FOOTER__LINK">
            <h4 class="FOOTER__LINK__TITLE">Contact</h4>
            <ul>
                <li><a href="#features">Our Service</a></li>
                <li><a href="">Get Help</a></li>
            </ul>
        </div>

        <div class="">
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
    <script src="Homepage.js"></script>
</body>

</html>