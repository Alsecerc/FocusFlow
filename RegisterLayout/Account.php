<?php
include "conn.php";
session_start();
include "AccountVerify.php";

if (!verifyUser($_conn)) {
    header("Location: Login.php");
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

                    <?php
                    $userID = $_COOKIE['UID'];

                    // Check if there are any unread notifications for this user
                    $sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = $userID AND status = 'unread'";
                    $result = $_conn->query($sql);
                    $row = $result->fetch_assoc();
                    $hasUnread = $row['unread_count'] > 0; // True if there are unread notifications
                    ?>

                    <li class="HEADER__ITEM" style="position: relative; user-select: none; cursor: pointer;">
                        <div class="HEADER__UL__ICON" id="notiButton">
                            <span class="material-icons" id="notiIcon">
                                <?= $hasUnread ? 'notifications_active' : 'notifications' ?>
                            </span>
                        </div>
                        <?php
                        $userID = $_COOKIE['UID'];
                        $sql = "SELECT * FROM notifications WHERE user_id = $userID ORDER BY status ASC, created_at DESC";
                        $result = $_conn->query($sql);
                        ?>

                        <div class="NOTIFICATION__POPUP" id="notificationPopup" style="height: 300px; overflow-y: auto; cursor:default; display:none;">
                            <?php if ($result->num_rows > 0): ?>
                                <ul id="notificationList">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <?php if ($row['type'] == 'system'): ?>
                                            <li class="NOTI__ITEM <?= strtolower($row['status']) == 'unread' ? 'UNREAD' : 'READ' ?>">
                                                📢 System Notification: <?= $row['notification_message'] ?>
                                                <small> (<?= $row['created_at'] ?>)</small>
                                            </li>
                                        <?php else: ?>
                                            <li class="NOTI__ITEM <?= strtolower($row['status']) == 'unread' ? 'UNREAD' : 'READ' ?> NOTI__ITEM__MSG">
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
            <div style="display:flex; justify-content:space-around;">
                <section class="PROFILE__SEC">
                    <div class="PROFILE__PIC__CONT">
                        <span class="material-icons" style="font-size:10rem;">
                            account_circle
                        </span>
                    </div>
                    <div>
                        <?php
                        include "conn.php"; // Database connection file
                        $userID = $_COOKIE['UID'];

                        $name = $_COOKIE['USERNAME'];
                        $email = $_COOKIE['EMAIL'];
                        $type = $_COOKIE['USERTYPE'];


                        $user = [
                            "name" => $name,
                            "email" => $email,
                            "type" => $type
                        ];


                        echo "<script>";
                        echo "var User = " . json_encode($user) . ";";
                        echo "</script>";
                        ?>


                        <form action="AccountUpdate.php" method="POST" class="PROFILE__DETAILS" onsubmit="return verifyPassword()">

                            <div>
                                <p>Username :</p>
                                <label class="INPUT__BOX">
                                    <input type="text" name="username" class="INPUT__INPUT">
                                    <span class="INPUT__PLACEHOLDER" id="profile_name"></span>
                                </label>
                            </div>

                            <div>
                                <p>Email :</p>
                                <label class="INPUT__BOX">
                                    <input type="email" name="email" class="INPUT__INPUT">
                                    <span class="INPUT__PLACEHOLDER" id="profile_email"></span>
                                </label>
                            </div>

                            <div>
                                <p>Confirm Current Password</p>
                                <label class="INPUT__BOX">
                                    <input type="password" id="current_password" class="INPUT__INPUT" required>
                                    <span class="INPUT__PLACEHOLDER">Enter current password</span>
                                </label>
                            </div>

                            <button type="submit" class="PROFILE__SAVE">Save Changes</button>
                            <button type="reset" class="PROFILE__SAVE" onclick="resetField()">Reset</button>
                        </form>

                        <script>
                            // Properly escape the password to avoid JavaScript syntax errors
                            const storedPassword = "<?php echo addslashes($_COOKIE['PASSWORD']); ?>";

                            function resetField() {
                                // Fix: Replace getQueryAll with document.querySelectorAll
                                let INPUTS = document.querySelectorAll('.INPUT__BOX');
                                INPUTS.forEach((element) => {
                                    let INPUT = element.querySelector(".INPUT__INPUT");
                                    let PLACEHOLDER = element.querySelector(".INPUT__PLACEHOLDER");
                                    INPUT.classList.remove("INVALID_BORDER");
                                    PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
                                    INPUT.classList.remove("VALID_BORDER");
                                    PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
                                });
                            }

                            function verifyPassword() {
                                const currentPassword = document.getElementById("current_password").value;

                                console.log("Entered:", currentPassword, "Stored:", storedPassword);

                                if (currentPassword !== storedPassword) {
                                    alert("Incorrect password! Please enter the correct password to proceed.");

                                    // Clear all input fields
                                    document.querySelector("input[name='username']").value = "";
                                    document.querySelector("input[name='email']").value = "";
                                    document.querySelector("input[name='password']").value = "";
                                    document.getElementById("current_password").value = "";

                                    resetField();
                                    return false;
                                }

                                resetField();
                                return true;
                            }
                        </script>
                    </div>
                    <div class="PROFILE__LOGOUT">
                        <a class="PROFILE__LOGOUT_B" href="AccountLogOutBackend.php">Log Out</a>
                    </div>
                </section>


                <div>
                    <h3 style="text-align: center; margin-top: 5rem;">Change Theme</h3>
                    <article class="ACCOUNT__THEME">
                        <button class="SETTING__BUTTON CLICKABLE" style="background-color: #3b3b3b; color: white;" onclick="changeTheme('default')">Default</button>
                        <button class="SETTING__BUTTON CLICKABLE" style="background-color: #7A3E1D; color: white;" onclick="changeTheme('theme_earth')">Earth</button>
                        <button class="SETTING__BUTTON CLICKABLE" style="background-color: #8BE9FD; color: black;" onclick="changeTheme('theme_neon')">Neon</button>
                        <button class="SETTING__BUTTON CLICKABLE" style="background-color: #52796F; color: white;" onclick="changeTheme('theme_forest')">Forest</button>
                </div>
            </div>

        </article>

    </main>


    <script src="Registered.js" defer></script>
    <script src="Account.js" defer></script>
</body>

</html>