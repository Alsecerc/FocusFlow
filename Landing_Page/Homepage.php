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
    <link rel="stylesheet" href="Homepage.css">
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
        <article class="WELCOME DISPLAY" id="welcome">
            <section class="WELCOME__CONTAINER">
                <div class="WELCOME__TITLE">
                    <h1>Boost Your <span class="KEYWORD">Productivity</span> Today!</h1>
                    <h3>Manage your time effectively with our smart task management and calendar system.</h3>
                    <div class="WELCOME__CTA">
                        <button class="HEADER__SIGNUP CLICKABLE CTA">GET STARTED</button>
                        <button class="HEADER__SIGNUP CLICKABLE">VIEW PLAN</button>
                    </div>
                </div>
                <img src="img\TITLE_IMAGE.png" alt="Productive Person" class="WELCOME__IMAGE">
            </section>
        </article>

        <article class="FEATURES DISPLAY" id="features">
            <h2 class="CENTER ARTICLE_TITLE">Service and Benefits</h2>
            <div class="FEATURES__CONTAINER">

                <div class="FEATURES__CONTAINER__CARD">
                    <div class="FEATURES__FRONT">
                        <img src="img/STATS.png" alt="Managing Task" class="FEATURES__IMAGE">
                        <div>
                            <h5 class="FEATURES__TITLE">Task Scheduling & Management </h5>
                        </div>
                    </div>
                    <div class="FEATURES__BACK">
                        <div>
                            Stay organized and on track by <strong>scheduling</strong>, <strong>prioritizing</strong>, and <strong>managing tasks</strong> with ease. Boost productivity with timely <strong>reminders</strong> and <strong>progress tracking</strong>.
                        </div>
                    </div>
                </div>

                <div class="FEATURES__CONTAINER__CARD">
                    <div class="FEATURES__FRONT">
                        <img src="img/CHAT.png" alt="Community Chat" class="FEATURES__IMAGE">
                        <div>
                            <h5 class="FEATURES__TITLE">Community & Collaboration</h5>
                        </div>
                    </div>
                    <div class="FEATURES__BACK">
                        <div>
                            Enhance teamwork with <strong>Community & Collaboration</strong>. Create <strong>workspaces</strong> for <strong>team communication</strong>, <strong>project management</strong>, and <strong>real-time collaboration</strong>. Set up <strong>channels</strong> for <strong>discussions</strong> and use <strong>DMs</strong> for <strong>private conversations</strong> to keep your team <strong>connected</strong>.
                        </div>
                    </div>
                </div>

                <div class="FEATURES__CONTAINER__CARD">
                    <div class="FEATURES__FRONT">
                        <img src="img/NOTI.png" alt="Receive Notifications" class="FEATURES__IMAGE">
                        <div>
                            <h5 class="FEATURES__TITLE">Smart Notifications & Reminders</h5>
                        </div>
                    </div>
                    <div class="FEATURES__BACK">
                        <div>
                            Stay on top of your tasks with <strong>Smart Notifications & Reminders</strong>. Get <strong>real-time updates</strong> on <strong>assignments</strong>, <strong>deadlines</strong>, and <strong>team activities</strong>, ensuring you never miss an important update. Customize <strong>reminders</strong> for upcoming tasks and receive <strong>alerts</strong> tailored to your workflow—keeping you <strong>productive</strong> and <strong>focused</strong> at all times!
                        </div>
                    </div>
                </div>

                <div class="FEATURES__CONTAINER__CARD">
                    <div class="FEATURES__FRONT">
                        <img src="img/CALENDAR.png" alt="View Calendar" class="FEATURES__IMAGE">
                        <div>
                            <h5 class="FEATURES__TITLE">Multi-view Calendar (Day, Week, Month)</h5>
                        </div>
                    </div>
                    <div class="FEATURES__BACK">
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

        <article class="SURVEY DISPLAY">
            <!-- survey  -->
            <h3 class="CENTER ARTICLE_TITLE">Website Feedback Survey</h3>
            <form action="Homepage.php" method="POST">
                <fieldset class="SURVEY__FORM">
                    <div class="SURVEY__FORM_GROUP">

                        <label class="INPUT__BOX">
                            <input type="text" name="username" class="INPUT__INPUT" required>
                            <span class="INPUT__PLACEHOLDER">Name</span>
                        </label>

                        <label class="INPUT__BOX">
                            <input type="email" name="email" class="INPUT__INPUT" required>
                            <span class="INPUT__PLACEHOLDER">Email</span>
                        </label>

                        <label class="INPUT__BOX">
                            <input type="text" name="role" class="INPUT__INPUT" required>
                            <span class="INPUT__PLACEHOLDER">Role/ Profession</span>
                        </label>

                    </div>
                    <div class="SURVEY__FORM_GROUP">
                        <!-- Ease of usage -->
                        <label for="ease-of-use">How easy was it to navigate the website?</label>
                        <select id="ease-of-use" name="ease-of-use" class="SELECT_CONTAINER" required>
                            <option value="" disabled selected>Select</option>
                            <option value="very-easy">Very Easy</option>
                            <option value="easy">Easy</option>
                            <option value="neutral">Neutral</option>
                            <option value="difficult">Difficult</option>
                            <option value="very-difficult">Very Difficult</option>
                        </select>
                    </div>

                    <div class="SURVEY__FORM_GROUP">
                        <!-- Features -->
                        <label for="most-used-feature">Which feature(s) do you use the most?</label>
                        <select name="most-used-feature" id="most-used-feature" class="SELECT_CONTAINER" required>
                            <option value="" disabled selected>Select</option>
                            <option value="task-management">Task Management</option>
                            <option value="calendar">Calendar</option>
                            <option value="notes">Notes</option>
                            <option value="pomodoro-timer">Pomodoro Timer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="SURVEY__FORM_GROUP">
                        <!-- Impact on Productivity -->
                        <label for="impact">Has the website helped you become more productive?</label>
                        <select name="impact" id="impact" class="SELECT_CONTAINER" required>
                            <option value="" disabled selected>Select</option>
                            <option value="significantly-more">Significantly More Productive</option>
                            <option value="more">More Productive</option>
                            <option value="neutral">No Change</option>
                            <option value="less">Less Productive</option>
                        </select>
                    </div>

                    <div class="SURVEY__FORM_GROUP">
                        <label for="suggestions">What improvements would you suggest to make the website more effective
                            for
                            you?</label>
                        <textarea class="SURVEY__SUGGESTIONS" name="suggestions" rows="4" cols="50" placeholder="Enter your feedback..."></textarea>
                    </div>
                    <!-- Submit -->
                    <button type="submit" class="CLICKABLE SURVEY__SUBMIT">Submit</button>
                </fieldset>

                <!-- create survey  -->
                <!-- 1️⃣ Heading & Short Description – Explain why users should fill it out.
2️⃣ User Experience Rating (1-5 stars or emoji scale) – Ask how their experience was.
3️⃣ Feature Feedback (Multiple Choice or Checkboxes) – Ask which features they liked.
4️⃣ Open-Ended Question (Text Input) – Let users share thoughts.
5️⃣ Would They Recommend? (Yes/No or Likert Scale)
6️⃣ Email (Optional) – To follow up for more feedback.
7️⃣ Submit Button – Send responses to your database or email. -->
            </form>
        </article>
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

    <script src="Homepage.js"></script>
</body>

</html>