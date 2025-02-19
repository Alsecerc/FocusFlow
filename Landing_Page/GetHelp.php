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
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#contactus">Customer Service</a>
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#FAQ">FAQ</a>
                        <a class="DROPDOWN__ITEM" href="GetHelp.php#feedback">Feedback</a>
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
        <article class="CS__Content">
            <section class="CS__FAQ" id="FAQ">
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
            </section>
        </article>

        <article class="GetHelp" id="contactus">
            <h1 class="ARTICLE_TITLE DISPLAY">Contact Us</h1>
            <section class="CONTACT__CONTAINER">
                <div class="DISPLAY">
                    <h3>Contact Information</h3>
                    <p>Email : focusflow@gmail.com</p>
                    <p>Phone : +60 (0)3 6138-7175</p>
                    <p>Address : Lot 4220 Persimpangan Jalan Batu Arang, Lebuh Raya Plus, Rawang</p>
                </div>
            </section>
        </article>
        <article class="SURVEY DISPLAY" id="feedback">
            <h3 class="CENTER ARTICLE_TITLE DISPLAY">Website Feedback Survey</h3>
            <form action="GetHelp.php" method="POST">
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

                    </div>

                    <div class="SURVEY__FORM_GROUP">
                        <label for="profession-role">Profession/ Role</label>
                        <select id="profession-role" name="profession-role" class="SELECT_CONTAINER" required>
                            <option value="" disabled selected>Select</option>
                            <option value="student">Student</option>
                            <option value="freelancer">Freelancer</option>
                            <option value="entrepreneur">Entrepreneur</option>
                            <option value="software-developer">Software Developer</option>
                            <option value="project-manager">Project Manager</option>
                            <option value="designer">Designer</option>
                            <option value="marketer">Marketer</option>
                            <option value="teacher">Teacher</option>
                            <option value="healthcare-professional">Healthcare Professional</option>
                            <option value="corporate-executive">Corporate Executive</option>
                            <option value="engineer">Engineer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>


                    <div class="SURVEY__FORM_GROUP">
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

                        <label for="most-used-feature">Which feature do you use the most?</label>
                        <select name="most-used-feature" id="most-used-feature" class="SELECT_CONTAINER" required>
                            <option value="" disabled selected>Select</option>
                            <option value="task-management">Task Management</option>
                            <option value="calendar">Calendar</option>
                            <option value="notes">Community</option>
                            <option value="pomodoro-timer">Pomodoro Timer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="SURVEY__FORM_GROUP">

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

                    <button type="submit" class="CLICKABLE SURVEY__SUBMIT">Submit</button>
                </fieldset>
            </form>
            <?php
            include "../RegisterLayout/conn.php";

            if ($_conn->connect_error) {
                die("Connection failed: " . $_conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Retrieve form data using $_POST
                $username = $_POST['username'];
                $email = $_POST['email'];
                $profession_role = $_POST['profession-role'];
                $ease_of_use = $_POST['ease-of-use'];
                $most_used_feature = $_POST['most-used-feature'];
                $impact = $_POST['impact'];
                $suggestions = $_POST['suggestions'];

                $query = "INSERT INTO survey_responses (username, email, profession_role, ease_of_use, most_used_feature, impact, suggestions)
          VALUES ('$username', '$email', '$profession_role', '$ease_of_use', '$most_used_feature', '$impact', '$suggestions')";

                if (mysqli_query($_conn, $query)) {
                    echo "<script>alert('Thank you for your feedback ! ');</script>";
                } else {
                    echo "Error: " . mysqli_error($_conn);
                }
            }

            ?>
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
        <a href="#welcome">
            <span class="material-symbols-outlined">
                stat_3
            </span>
        </a>
    </div>

    <script src="index.js"></script>

</body>

</html>