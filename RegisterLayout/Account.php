<?php
include "conn.php";
session_start();

if (!isset($_COOKIE['UID'])) {
    echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
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
            <span class="material-icons SEARCH_ICON">search</span>
            <input type="text" id="searchInput" class="HEADER__SEARCH_INPUT" placeholder="Search..." onkeyup="searchFunction()" autocomplete="off">
            <div id="searchResults" class="SEARCH_RESULTS"></div>
        </div>


        <div class="HEADER__RIGHT">
            <nav>
                <ul class="HEADER__UL">
                    <li class="HEADER__ITEM">
                        <a href="../Landing_Page/GetHelp.php" target="_blank" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                support_agent
                            </span>
                        </a>
                    </li>
                    <li class="HEADER__ITEM" style="position: relative;user-select:none;cursor:pointer;">
                        <div class="HEADER__UL__ICON" id="notiButton">
                            <span class="material-icons">
                                notifications
                            </span>
                        </div>
                        <?php
                        $userID = $_COOKIE['UID'];
                        $sql = "SELECT * FROM notifications WHERE user_id = $userID ORDER BY created_at DESC";
                        $result = $_conn->query($sql);
                        ?>

                        <div class="NOTIFICATION__POPUP" id="notificationPopup" style="overflow-y: auto; cursor:default; display:none;">
                            <?php if ($result->num_rows > 0): ?>
                                <ul id="notificationList">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <?php if ($row['type'] == 'system'): ?>
                                            <li class="NOTI__ITEM">
                                                📢 System Notification: <?= $row['notification_message'] ?>
                                                <small> (<?= $row['created_at'] ?>)</small>
                                            </li>
                                        <?php else: ?>
                                            <li class="NOTI__ITEM NOTI__ITEM__MSG">
                                                <?php
                                                $sql2 = "SELECT * FROM users WHERE id = " . $row['sender_id'];
                                                $result2 = $_conn->query($sql2);
                                                $sender = $result2->fetch_assoc();

                                                if ($result2->num_rows > 0) {
                                                ?>
                                                    <a href="CommunityDMPage?receiver_id=<?= $row['sender_id'] ?>&name=<?= urlencode($sender['name']) ?>" class="NOTI__LINK">
                                                        🗨️ <?= $row['notification_message'] ?>
                                                        <small> (<?= $row['created_at'] ?>)</small>
                                                    </a>
                                                <?php
                                                }
                                                ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </ul>
                            <?php else: ?>
                                <p id="noNotifications">No new notifications</p>
                            <?php endif; ?>
                        </div>


                    </li>
                    <li class="HEADER__ITEM">
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
        <div class="SIDEBAR" style="overflow-y: auto;">
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li>
                        <a href="Homepage.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">home</span>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="Timer.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">timer</span>Focus Timer
                        </a>
                    </li>
                    <li>
                        <a href="Todo.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">task_alt</span>To Do
                        </a>
                    </li>
                    <li>
                        <a href="Calendar.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">event</span>Calendar
                        </a>
                    </li>
                    <li>
                        <a href="Analytic.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">analytics</span>Analytics
                        </a>
                    </li>
                    <li>
                        <a href="Goal.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">track_changes</span>Goals
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">chat</span>Direct Message
                        </a>
                    </li>
                </ul>
            </nav>

            <?php
            $loggedInUserID = $_COOKIE['UID']; // Assuming user ID is stored in a cookie

            $sql = "SELECT id, team_name FROM team 
            WHERE leader_id = ? OR member_id = ? 
            GROUP BY team_name";
            $stmt = $_conn->prepare($sql);
            $stmt->bind_param("ii", $loggedInUserID, $loggedInUserID);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            <nav class="SIDEBAR__NAV COMMUNITY">
                <h4 class="NAV_TITLE">Community</h4>
                <ul>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <li>
                                <a href="CommunityPage.php?team_id=<?= urlencode($row['id']) ?>&team=<?= urlencode($row['team_name']) ?>"
                                    class="SIDEBAR__ITEM COMMUNITY__ITEM">
                                    <?= htmlspecialchars($row['team_name']) ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>No teams found</li>
                    <?php endif; ?>
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
                    $userID = $_COOKIE['UID'];

                    $name = $_COOKIE['USERNAME'];
                    $email = $_COOKIE['EMAIL'];
                    $password = $_COOKIE['PASSWORD'];
                    $type = $_COOKIE['USERTYPE'];

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
                        $newName = !empty(trim($_POST['username'])) ? trim($_POST['username']) : $_COOKIE['USERNAME'];
                        $newEmail = !empty(trim($_POST['email'])) ? trim($_POST['email']) : $_COOKIE['EMAIL'];
                        $newPassword = !empty(trim($_POST['password'])) ? trim($_POST['password']) : $_COOKIE['PASSWORD'];
                        $_COOKIE['UID'] = $newName;
                        $_COOKIE['EMAIL'] = $newEmail;
                        $_COOKIE['PASSWORD'] = $newPassword;

                        $userID = $_COOKIE['UID'];

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
                <a class="PROFILE__LOGOUT_B" href="AccountLogOutBackend.php">Log Out</a>
            </div>

        </article>
        <article style="text-align: center;">
            <h3>Change Theme</h3>
            <button class="SETTING__BUTTON" style="background-color: #3b3b3b; color: white;" onclick="changeTheme('default')">Default</button>
            <button class="SETTING__BUTTON" style="background-color: #7A3E1D; color: white;" onclick="changeTheme('theme_earth')">Earth</button>
            <button class="SETTING__BUTTON" style="background-color: #8BE9FD; color: black;" onclick="changeTheme('theme_neon')">Neon</button>
            <button class="SETTING__BUTTON" style="background-color: #52796F; color: white;" onclick="changeTheme('theme_forest')">Forest</button>
        </article>
    </main>


    <script src="Registered.js" defer></script>
    <script src="Account.js" defer></script>
</body>

</html>