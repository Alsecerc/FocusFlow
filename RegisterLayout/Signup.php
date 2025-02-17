<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Sign Up</title>
    <link rel="stylesheet" href="Signupstyle.css">
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
        <h2>Sign up</h2>
        <h3>Already have an account? <a href="Login.php">Log In</a></h3>

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
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="email" placeholder="Email" required>
                <input type="text" name="password" placeholder="Password" required>
                <input type="text" name="confirmPassword" placeholder="Confirm Password" required>
                <button type="button" class="next-btn">Next</button>
            </div>
            <div class="form-step">
                <input type="number" name="age" placeholder="Age" required>
                <input type="text" name="gender" placeholder="Gender" required>
                <button type="button" class="next-btn">Next</button>
            </div>
            <div class="form-step">
                <label>Productivity Goals:</label>
                <select name="productivity_goals" required>
                    <option value="Task Management">Task Management</option>
                    <option value="Time Tracking">Time Tracking</option>
                    <option value="Habit Building">Habit Building</option>
                    <option value="Work-Life Balance">Work-Life Balance</option>
                    <option value="Learning New Skills">Learning New Skills</option>
                </select>

                <label>Preferred Work Hours:</label>
                <input type="text" name="preferred_hours" placeholder="e.g., 9 AM - 5 PM" required>

                <label>Purpose of Using FocusFlow:</label>
                <select name="purpose" required>
                    <option value="School/Education">School/Education</option>
                    <option value="Teaching Resource">Teaching Resource</option>
                    <option value="Project Management">Project Management</option>
                    <option value="Planning & Scheduling">Planning & Scheduling</option>
                    <option value="Learning New Skills">Learning New Skills</option>
                </select>
                <button type="submit" class="submit-btn">Sign Up</button>
            </div>
        </form>
    </div>
</div>
</body>
<script src="Signup.js"></script>

</html>