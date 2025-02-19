<?php

session_start();

if (!isset($_COOKIE['id'])) {
    echo "<script>window.location.href='../Landing_Page/Homepage.php'</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
</head>

<body>
    <header>
        <div class="HEADER__LEFT">
            <button class="HEADER__MENU_BUTTON">
                <div class="HEADER__MENU_ICON"></div>
            </button>
            <a href="Homepage.php">
                <h1 class="HEADER__TITLE">F<span class="material-symbols-outlined HEADER__ICON">
                        schedule
                    </span>cusFlow
                </h1>
            </a>
        </div>
        <div class="HEADER__SEARCH">
            <button class="HEADER__SEARCH_BUTTON">
                <span class="material-symbols-outlined">
                    search
                </span>
            </button>
            <input type="text" class="HEADER__SEARCH_INPUT" placeholder="Search...">
        </div>
        <div class="HEADER__RIGHT">
            <nav>
                <ul class="HEADER__UL">
                    <li>
                        <a href="CusService.php" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                support_agent
                            </span>
                        </a>
                    </li>
                    <li>
                        <div class="HEADER__UL__ICON">
                            <span class="material-icons">
                                notifications
                            </span>
                        </div>
                    </li>
                    <li>
                        <a href="Setting.php" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                settings
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="Account.php" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                account_circle
                            </span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="SIDEBAR">
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li>
                        <a href="Homepage.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                home
                            </span>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="Timer.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                timer
                            </span>Focus Timer
                        </a>
                    </li>
                    <li>
                        <a href="Todo.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                task_alt
                            </span>To Do
                        </a>
                    </li>
                    <li>
                        <a href="Calendar.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                event
                            </span>Calendar
                        </a>
                    </li>
                    <li>
                        <a href="Analytic.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                analytics
                            </span>Analytics
                        </a>
                    </li>
                </ul>
            </nav>
            <nav class="SIDEBAR__NAV COMMUNITY">
                <h4 class="NAV_TITLE">Community</h4>
                <ul>
                    <li>
                        <a href="CommunityPage.php" class="SIDEBAR__ITEM COMMUNITY__ITEM">
                            Channel 1
                            <button class="material-icons">
                                more_horiz
                            </button>
                        </a>
                    </li>
                </ul>
                <h4 class="NAV_TITLE">DM</h4>
                <ul>
                    <li>
                        <a href="CommunityDMPage.php" class="SIDEBAR__ITEM COMMUNITY__ITEM">
                            Person 1
                            <button class="material-icons">
                                more_horiz
                            </button>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <article class="PROFILE">
            <h1 class="ARTICLE_TITLE">Account Management</h1>
            <section>
                <img src="img/USER_ICON.png" alt="user profile" class="PROFILE__PIC">
                <div>
                    <?php
                    include "../RegisterLayout/conn.php"; // Database connection file
                        $userID = $_COOKIE['id'];
                    
                        // Prepare SQL query
                        $sql = "SELECT id, name, email, usertype, last_login FROM users WHERE id = ?";
                        $stmt = $_conn->prepare($sql);
                        $stmt->bind_param("i", $userID); // "i" means integer
                    
                        // Execute and fetch result
                        $stmt->execute();
                        $result = $stmt->get_result();
                    
                        if ($result->num_rows > 0) {
                            $user = $result->fetch_assoc(); // Fetch user data
                            echo "<h2>Welcome, " . htmlspecialchars($user['name']) . "!</h2>";
                            echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
                            echo "<p>User Type: " . htmlspecialchars($user['usertype']) . "</p>";
                            echo "<p>Last Login: " . htmlspecialchars($user['last_login']) . "</p>";
                        } else {
                            echo "<p>No user found.</p>";
                        }
                    
                        // Close statement and connection
                        $stmt->close();
                        $_conn->close();
                    
                    ?>
                    <p>Username : <span id="profile_name"></span></p>
                    <p>Email : <span id="profile_email"></span></p>
                    <p>Password : <span id="profile_password"></span></p>
                </div>
            </section>

        </article>

    </main>

    <script src="Registered.js" defer></script>
    <script src="Account.js" defer></script>
</body>

</html>