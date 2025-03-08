<?php

session_start();
include "conn.php";

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
    <title>Calendar </title>

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

        <article class="GOAL__MAIN">
            <div style="display: flex; justify-content:space-between; align-items:center;">
                <h2>Your Goals</h2>
                <div>
                    <button class="GOAL__SET" onclick="togglePopup()">Add Goal</button>
                    <button class="GOAL__SET" onclick="toggleProgressPopup()">Update Goal</button>
                    <button class="GOAL__SET" onclick="toggleRemovalPopup()">Remove Goal</button>
                    <script>
                        function toggleProgressPopup() {
                            let form = document.getElementById("progressForm");
                            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
                        }

                        function toggleRemovalPopup() {
                            let form = document.getElementById("removalForm");
                            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
                        }
                    </script>
                </div>
            </div>
            <div class="GOAL__INPUT" style="display: none;">
                <h4>Set your goal</h4>
                <form action="GoalBackend.php" method="POST" class="GOAL__FORM">
                    <input type="hidden" name="action" value="Add">

                    <label class="INPUT__BOX">
                        <input type="text" name="goal_title" class="INPUT__INPUT" required>
                        <span class="INPUT__PLACEHOLDER">Goal Title</span>
                    </label>

                    <label class="INPUT__BOX">
                        <input type="text" name="goal_description" class="INPUT__INPUT" required>
                        <span class="INPUT__PLACEHOLDER">Description</span>
                    </label>


                    <label class="INPUT__BOX">
                        <select name="goal_type" required>
                            <option value="short-term">Short-Term</option>
                            <option value="long-term">Long-Term</option>
                        </select>
                        <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="goal_ph">Goal Type</span>
                    </label>

                    <label class="INPUT__BOX">
                        <input type="date" name="start_time" id="start_time" class="INPUT__INPUT" max="2050-01-01" required>
                        <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="start_time_ph">Starting Date</span>
                    </label>

                    <label class="INPUT__BOX">
                        <input type="date" name="end_time" id="end_time" class="INPUT__INPUT" max="2050-01-01" required>
                        <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="end_time_ph">Ending Date</span>
                    </label>


                    <label class="INPUT__BOX">
                        <input type="datetime-local" name="reminder_time" id="reminder_time">
                        <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="remainder_ph">Reminder Time</span>
                    </label>

                    <button type="submit" class="GOAL__SET">Set Goal</button>
                </form>
            </div>
            <div class="GOAL__DISPLAY">
                <?php

                $user_id = $_COOKIE['UID'];

                $sql = "SELECT * FROM goals WHERE user_id = ?";
                $stmt = $_conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $goals = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $goals[] = $row;
                    }
                }

                $stmt->close();
                ?>

                <?php if (!empty($goals)): ?>
                    <?php foreach ($goals as $goal): ?>
                        <?php $goalClass = (htmlspecialchars($goal['status']) === 'completed') ? 'COMP' : ((htmlspecialchars($goal['status']) === 'in-progress') ? 'PROG' : 'DUE'); ?>
                        <div class="GOAL__CARD <?php echo $goalClass ?> ">
                            <h3 class="GOAL__TITLE"><?= htmlspecialchars($goal['goal_title']) ?></h3>
                            <p><strong>Goal ID:</strong> <?= htmlspecialchars($goal['goal_id']) ?></p>
                            <p><strong>Type:</strong> <?= htmlspecialchars($goal['goal_type']) ?></p>
                            <p><strong>Progress:</strong> <?= htmlspecialchars($goal['progress']) ?>%</p>
                            <p><strong>Status:</strong> <?= htmlspecialchars($goal['status']) ?></p>
                            <p><strong>Reminder:</strong> <?= htmlspecialchars($goal['reminder_time']) ?></p>

                            <div class="GOAL__BAR">
                                <div class="GOAL__BAR_FILL" style="width: <?= htmlspecialchars($goal['progress']) ?>%;">
                                    <?= htmlspecialchars($goal['progress']) ?>%
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No goals set yet.</p>
                <?php endif; ?>
            </div>



            <div id="progressForm" style="display: none;">
                <form action="Goal.php" id="goalUpdateForm" method="POST">
                    <label>Goal ID:</label>
                    <input type="number" name="goal_id" required>

                    <label>Progress:</label>
                    <input type="number" name="progress" min="0" max="100" required>

                    <button type="submit" class="GOAL__SET UPDATE__GOAL">Update Progress</button>
                </form>
                <?php

                $user_id = $_COOKIE['UID'];

                if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                    if (isset($_POST['goal_id']) && isset($_POST['progress'])) {
                        $goal_id = $_POST['goal_id'];
                        $progress = $_POST['progress'];

                        $sql = "UPDATE goals SET progress = ?, 
                status = CASE WHEN ? = 100 THEN 'completed' ELSE 'in-progress' END 
                WHERE goal_id = ? AND user_id = ?";

                        $stmt = $_conn->prepare($sql);
                        $stmt->bind_param("iiii", $progress, $progress, $goal_id, $user_id);

                        if ($stmt->execute()) {
                            if ($stmt->affected_rows > 0) {
                                echo "<script>alert('Goal Updated, Keep it up!')</script>";
                            } else {
                                echo "<script>alert('No goal found with the given ID for this user.')</script>";
                            }
                        } else {
                            $_SESSION['message'] = "Error: " . $_conn->error;
                        }

                        $stmt->close();
                        $_conn->close();

                        // ✅ Redirect to clear $_POST data
                        echo "<script>window.location.href='Goal.php'</script>";
                    } else {
                        $_SESSION['message'] = "Error: Missing goal_id or progress!";
                    }
                }
                ?>
            </div>
            <div id="removalForm" style="display: none;">
                <form action="GoalBackend.php" id="goalUpdateForm" method="POST">
                    <h3>Enter Goal ID to remove</h3>
                    <label>Goal ID:</label>
                    <input type="hidden" name="action" value="Remove">
                    <input type="number" name="goal_id" required>

                    <button type="submit" class="GOAL__SET UPDATE__GOAL">Update Progress</button>
                </form>
            </div>
        </article>
    </main>
    <script src="Registered.js" defer></script>
    <script src="Goal.js" defer></script>
</body>

</html>