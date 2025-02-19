<?php
session_start();

if (isset($_COOKIE['userID'])) {
    header("Location: Homepage.php");
    exit();
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
    <title>Log In</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<div class="container">
    <!-- Left Section -->
    <div class="left-section">
        <h3>Focus on your task</h3>
        <h2>Idk what to put here</h2>
        <div class="image-placeholder"></div>

        <!-- For paging on the left -->
        <div class="pagination">
            <span class="dot"></span>
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <!-- Right Section -->
    <div class="right-section">
        <h1>FocusFlow</h1>
        <h2>Log In</h2>
        <h3>Don't have an account? <a href="Signup.php">Sign Up</a></h3>

        <form id="signinForm" action="LoginBackend.php" method="POST">
            <div class="form input">
                <input type="text" name="username" placeholder="Name" required>
                <input type="text" name="password" placeholder="Password" required>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>
</body>

</html>