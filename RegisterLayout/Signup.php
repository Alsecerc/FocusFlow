<?php
session_start();
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "POST request received";
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $productivity_goals = $_POST['productivity_goals'];
    $preferred_hours = $_POST['preferred_hours'];
    $purpose = $_POST['purpose'];

    $sql = "INSERT INTO user (USERNAME_USER, EMAIL_USER, PASSWORD_USER, age, gender, productivity_goals, preferred_hours, purpose)
            VALUES ('$username', '$email', '$password', '$age', '$gender', '$productivity_goals', '$preferred_hours', '$purpose')";

    if ($_conn->query($sql) === TRUE) {
        header("Location: Signin.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $_conn-> error;
    }
    $_conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Sign Up</title>
    <link rel="stylesheet" href="loginandsignup.css">
</head>
<body>
    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <div class="carousel-container">
                <button class="carousel-nav prev">&lt;</button>
                <button class="carousel-nav next">&gt;</button>
                <div class="carousel-slide active">
                    <h3 class="slide-title">Focus on your task</h3>
                    <h2 class="slide-subtitle">Track your productivity seamlessly</h2>
                    <div class="image-placeholder">
                        <img src="img/undraw_dev-productivity_5wps.svg" alt="Productivity">
                    </div>
                </div>
                <div class="carousel-slide">
                    <h3 class="slide-title">Manage your time</h3>
                    <h2 class="slide-subtitle">Set goals and achieve them</h2>
                    <div class="image-placeholder">
                        <img src="img/undraw_time-management_fedt.svg" alt="Time Management">
                    </div>
                </div>
                <div class="carousel-slide">
                    <h3 class="slide-title">Stay organized</h3>
                    <h2 class="slide-subtitle">Keep your workflow structured</h2>
                    <div class="image-placeholder">
                        <img src="img/undraw_spreadsheet_g2tr.svg" alt="Organization">
                    </div>
                </div>
                <div class="carousel-slide">
                    <h3 class="slide-title">Track Progress</h3>
                    <h2 class="slide-subtitle">Monitor your improvements</h2>
                    <div class="image-placeholder">
                    <img src="img/undraw_progress-data_gvcq.svg" alt="Progress">
                    </div>
                </div>
            </div>
    <!-- Pagination dots -->
        <div class="pagination">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

        <!-- Right Section -->
        <div class="right-section">
            <h1>FocusFlow</h1>
            <h2>Sign up</h2>
            <h3>Already have an account? <a href="Login.php" class="signin-link">Sign in</a></h3>

            <!-- Progress Bar(1,2,3) -->
            <div class="progress-bar">
                <div class="step active">1</div>
                <div class="progress-bar-line"></div>
                <div class="step">2</div>
                <div class="progress-bar-line"></div>
                <div class="step">3</div>
            </div>

            <form id="signupForm" action="SignupBackend.php" method="POST">
                <div class="form-step active">
                    <input type="text" id="username" name="username" placeholder="Name" required>
                    <div id="usernameError" class="error"></div>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <div id="emailError" class="error"></div>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <div id="passwordError" class="error"></div>
                    <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                    <div id="passwordconfirmError" class="error"></div>
                    <button type="button" class="next-btn">Next</button>
                </div>

                <div class="form-step">
                    <input type="number" id="age" name="age" placeholder="Age" required min="0" max="100">
                    <div id="ageError" class="error"></div>
                    <div class="gender-group">
                        <label class="gender-label">Gender:</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="gender" value="male">
                                <span class="radio-text">Male</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="gender" value="female">
                                <span class="radio-text">Female</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="gender" value="other">
                                <span class="radio-text">Other</span>
                            </label>
                        </div>
                    </div>
                    <div id="genderError" class="error"></div>
                    <button type="button" class="next-btn">Next</button>
                </div>

                <div class="form-step">
                    <label for="productivity_goals">Productivity Goals:</label>
                    <select id="productivity_goals" name="productivity_goals" required>
                        <option value="Task Management">Task Management</option>
                        <option value="Time Tracking">Time Tracking</option>
                        <option value="Habit Building">Habit Building</option>
                        <option value="Work-Life Balance">Work-Life Balance</option>
                        <option value="Learning New Skills">Learning New Skills</option>
                    </select>
                    <div id="goalsError" class="error"></div>

                    <label for="preferred_hours">Preferred Work Hours:</label>
                    <input type="text" id="preferred_hours" name="preferred_hours" placeholder="e.g., 9 AM - 5 PM" required>
                    <div id="hoursError" class="error"></div>

                    <label for="purpose">Purpose of Using FocusFlow:</label>
                    <select id="purpose" name="purpose" required>
                        <option value="School/Education">School/Education</option>
                        <option value="Teaching Resource">Teaching Resource</option>
                        <option value="Project Management">Project Management</option>
                        <option value="Planning & Scheduling">Planning & Scheduling</option>
                        <option value="Learning New Skills">Learning New Skills</option>
                    </select>
                    <div id="purposeError" class="error"></div>
                    <button type="submit" class="submit-btn">Sign Up</button>
                </div>
            </form>
        </div>
    </div>
    <script src="loginandsignup.js" defer></script>
</body>
</html>