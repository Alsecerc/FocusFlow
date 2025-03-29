<?php
session_start();
if (!isset($_SESSION['suspended']) || !isset($_SESSION['suspension_end'])) {
    header("Location: Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Suspended - FocusFlow</title>
    <style>
        .suspension-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .countdown {
            font-size: 2rem;
            font-weight: bold;
            color: #ff4444;
            margin: 1rem 0;
        }
        .message {
            color: #666;
            margin-bottom: 1rem;
        }
        .logout-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="suspension-container">
        <h2>Account Suspended</h2>
        <p class="message">Your account has been temporarily suspended.</p>
        <p>Time remaining until suspension ends:</p>
        <div id="countdown" class="countdown"></div>
        <a href="../../RegisterLayout/Account/AccountLogOutBackend.php" class="logout-btn">Logout</a>
    </div>

    <script>
        const suspensionEnd = <?php echo $_SESSION['suspension_end']; ?> * 1000; // Convert to milliseconds

        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = suspensionEnd - now;

            if (timeLeft <= 0) {
                document.getElementById('countdown').innerHTML = "Suspension ended";
                window.location.href = 'Login.php';
                return;
            }

            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            document.getElementById('countdown').innerHTML = 
                days + "d " + hours + "h " + minutes + "m " + seconds + "s";
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>