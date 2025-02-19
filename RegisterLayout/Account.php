<?php

session_start();

if (!isset($_COOKIE['userID'])) {
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
            <section class="PROFILE__SEC">
                <div class="PROFILE__PIC__CONT">
                    <img src="img/USER_ICON.png" alt="user profile" class="PROFILE__PIC">
                    <!-- <button class="PROFILE__CHANGE">Change</button> -->
                </div>
                <div>
                    <?php
                    include "../RegisterLayout/conn.php"; // Database connection file
                    $userID = $_COOKIE['userID'];

                    $name = $_SESSION['userName'];
                    $email = $_SESSION['userEmail'];
                    $password = $_SESSION['userPassword'];
                    $type = $_SESSION['usertype'];

                    $user = [
                        "name" => $name,
                        "email" => $email,
                        "password" => $password,
                        "type" => $type
                    ];


                    echo "<script>";
                    echo "var User = " . json_encode($user) . ";";
                    echo "</script>";

                    ?>

                    <form action="Account.php" method="POST" class="PROFILE__DETAILS">

                        Username :
                        <label class="INPUT__BOX">
                            <input type="text" name="username" class="INPUT__INPUT">
                            <span class="INPUT__PLACEHOLDER" id="profile_name"></span>
                        </label>

                        Email :
                        <label class="INPUT__BOX">
                            <input type="email" name="email" class="INPUT__INPUT">
                            <span class="INPUT__PLACEHOLDER" id="profile_email"></span>
                        </label>

                        Password :
                        <label class="INPUT__BOX">
                            <input type="password" name="password" class="INPUT__INPUT" minlength="8"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}"
                                title="Must contain at least 8 characters, one uppercase, one lowercase, one number, and one special character.">
                            <span class="INPUT__PLACEHOLDER" id="profile_password"></span>
                        </label>



                        <button type="submit" onclick="saveChanges()" class="PROFILE__SAVE">Save Changes</button>
                    </form>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Get new values from the form
                        $newName = !empty(trim($_POST['username'])) ? trim($_POST['username']) : $_SESSION['userName'];
                        $newEmail = !empty(trim($_POST['email'])) ? trim($_POST['email']) : $_SESSION['userEmail'];
                        $newPassword = !empty(trim($_POST['password'])) ? trim($_POST['password']) : $_SESSION['userPassword'];

                        $_SESSION['userName'] = $newName;
                        $_SESSION['userEmail'] = $newEmail;
                        $_SESSION['userPassword'] = $newPassword;

                        $userID = $_COOKIE['userID'];

                        $sql = "UPDATE users SET " .
                            "name = '$newName'," .
                            "email = '$newEmail'," .
                            "password = '$newPassword'" .
                            "WHERE id = $userID";

                        $result = mysqli_query($_conn, $sql);
                    }

                    ?>

                </div>
            </section>
            <div class="PROFILE__LOGOUT">
                <button class="PROFILE__LOGOUT_B" onclick="logOut()">Log Out</button>
            </div>

        </article>

    </main>


    <script src="Registered.js" defer></script>
    <script src="Account.js" defer></script>
</body>

</html>