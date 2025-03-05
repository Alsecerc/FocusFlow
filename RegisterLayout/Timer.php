<?php
include "conn.php";
session_start();

include "AccountVerify.php";
if (!verifyUser($_conn)) {
    header("Location: Landing_Page/Homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img/SMALL_CLOCK_ICON.ico">
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



    <div class="TIMER__BODY">
    <div class="SIDEBAR" style="overflow-y: auto;">
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
                    <li>
                        <a href="Goal.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                track_changes
                            </span>Goals
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">
                                chat
                            </span>Direct Message
                        </a>
                    </li>
                </ul>
            </nav>
            <?php
            $loggedInUserID = $_COOKIE['UID']; // Assuming you store the logged-in user ID in a cookie

            $sql = "SELECT id, team_name FROM team 
            WHERE leader_id = ? OR member_id = ? 
            GROUP BY team_name";
            $stmt = $_conn->prepare($sql);
            $stmt->bind_param("ii", $loggedInUserID, $loggedInUserID);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            <nav class="SIDEBAR__NAV COMMUNITY">
                <div class="NAV_TITLE">
                    <h4>Community</h4>

                    <button class="NAV__TITLE__ADD CLICKABLE">
                        <span class="material-icons">
                            add_circle
                        </span>
                    </button>

                    <div class="NEW__TEAM__SURVEY ">
                        <h4>Create New Team</h4>
                        <form action="1AddTeam.php" method="POST" style="display:flex; flex-direction:column; gap:1rem; align-items:start;">
                            <label class="INPUT__BOX__SIDEBAR">
                                <input type="text" name="team_name" id="team_name" class="INPUT__INPUT__SB" required>
                                <span class="INPUT__PLACEHOLDER">Team Name : </span>
                            </label>
                            <div style="display:flex; justify-content:space-between; width: 100%;">
                                <button type="submit" class="TEAM__CREATE CLICKABLE">Create Team</button>
                                <button type="reset" class="TEAM__RESET CLICKABLE">Reset</button>
                            </div>
                        </form>
                    </div>

                </div>
                <ul>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<li>
        <a href="CommunityPage.php?team_id=' . urlencode($row['id']) . '&team=' . urlencode($row['team_name']) . '" class="SIDEBAR__ITEM COMMUNITY__ITEM">
            ' . htmlspecialchars($row['team_name']) . '
        </a>
      </li>';
                        }
                    } else {
                        echo '<li>No teams found</li>';
                    }
                    ?>
                </ul>
            </nav>

        </div>
        <div class="container">
            <div class="timer">
                <h1>Pomodoro Timer</h1>

                <div class="button-container">
                    <button class="button" id="pomodoro-session">Pomodoro</button>
                    <button class="button" id="short-break">Short Break</button>
                    <button class="button" id="long-break">Long Break</button>
                </div>

                <main class="MAIN__TIMER">
                    <div class="timer-wrapper">
                        <!-- Plus and Minus Buttons (Stacked) -->
                        <div class="adjust-buttons">
                            <h6 style="margin:0;">MIN</h6>
                            <button id="plus-btn" class="adjust-btn">+</button>
                            <button id="minus-btn" class="adjust-btn">−</button>
                        </div>

                        <!-- Timer Display -->
                        <div class="timer-display active">
                            <h1 class="time"><span id="pomodoro-timer"></span></h1>
                            <h1 class="time"><span id="short-timer"></span></h1>
                            <h1 class="time"><span id="long-timer"></span></h1>
                        </div>
                    </div>
                </main>

                <div class="buttons">
                    <button id="start">START</button>
                    <button id="stop">STOP</button>
                </div>
            </div>
        </div>
    </div>

    <script src="Registered.js" defer></script>
    <script src="Timer.js" defer></script>
</body>

</html>