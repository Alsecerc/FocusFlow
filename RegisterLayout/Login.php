<?php
session_start();
include 'conn.php';

if (isset($_COOKIE['UID'])) {
    header("Location: Homepage.php");
    exit();
}else{
    echo "doesnt have userid";
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
    <title>Sign in</title>
    <link rel="stylesheet" href="loginandsignup.css">
</head>
<body>
    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <div class="carousel-container">
                <div class="carousel-slide active">
                    <h3 class="slide-title">Focus on your task</h3>
                    <h2 class="slide-subtitle">Track your productivity seamlessly</h2>
                    <div class="image-placeholder">
                        <img src="test.png" alt="Productivity">
                    </div>
                </div>
                <div class="carousel-slide">
                    <h3 class="slide-title">Manage your time</h3>
                    <h2 class="slide-subtitle">Set goals and achieve them</h2>
                    <div class="image-placeholder">
                        <img src="test.png" alt="Time Management">
                    </div>
                </div>
                <div class="carousel-slide">
                    <h3 class="slide-title">Stay organized</h3>
                    <h2 class="slide-subtitle">Keep your workflow structured</h2>
                    <div class="image-placeholder">
                        <img src="test.png" alt="Organization">
                    </div>
                </div>
                <div class="carousel-slide">
                    <h3 class="slide-title">Track Progress</h3>
                    <h2 class="slide-subtitle">Monitor your improvements</h2>
                    <div class="image-placeholder">
                    <img src="test.png" alt="Progress">
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
            <h2>Sign in</h2>
            <h3>Don't have an account? <a href="Signup.php">Sign Up</a></h3>

            <form id="signinForm" action="LoginBackend.php" method="POST">
                <div class="form-input">
                    <input type="text" id="username" name="username" placeholder="Name" required>
                    <div id="usernameError" class="error"></div>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <div id="passwordError" class="error"></div>
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script src="loginandsignup.js" defer></script>
</body>
</html>