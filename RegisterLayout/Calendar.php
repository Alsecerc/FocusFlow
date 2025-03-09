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

    <main class="CALENDAR__MAIN">
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


        <!-- Calendar Content -->
        <article class="CONTAINER">
            <div class="POP_UP">
                <div class="OVERLAY"></div>
                <div class="POP_UP__CONTENT">
                    <h2>Create Task</h2>
                    <form action="CalendarBackend.php" method="POST" id="popUpForm">

                        <input type="hidden" name="action" value="Add">

                        <label class="INPUT__BOX" style="display: flex;">
                            <span class="INPUT__PLACEHOLDER AUTOFOCUS">Task Category : </span>
                            <select id="task_group" name="task_group" class="INPUT__INPUT" required>
                                <option value="" disabled selected>Select or Add Category</option>
                                <!-- Categories from database will be inserted here dynamically -->
                            </select>
                            <input type="text" id="new_category" class="INPUT__INPUT" style="display: none;">
                            <button type="button" id="add_category" class="CLICKABLE">Add</button>
                        </label>


                        <label class="INPUT__BOX">
                            <input type="text" name="task_title" id="task_title" class="INPUT__INPUT" required>
                            <span class="INPUT__PLACEHOLDER">Task Name : </span>
                        </label>

                        <label class="INPUT__BOX">
                            <input type="text" name="task_desc" id="task_desc" class="INPUT__INPUT" required>
                            <span class="INPUT__PLACEHOLDER">Task Description : </span>
                        </label>

                        <label class="INPUT__BOX">
                            <input type="date" name="start_date" id="start_date" class="INPUT__INPUT" min="2020-01-01" max="2030-01-01" required>
                            <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="start_date_ph">Starting Date : </span>
                        </label>

                        <label class="INPUT__BOX">
                            <input type="date" name="end_date" id="end_date" class="INPUT__INPUT" min="2020-01-01" max="2030-01-01" required>
                            <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="end_date_ph">Ending Date : </span>
                        </label>

                        <label class="INPUT__BOX">
                            <input type="time" name="start_time" id="start_time" class="INPUT__INPUT" required>
                            <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="start_time_ph">Starting Time : </span>
                        </label>

                        <label class="INPUT__BOX">
                            <input type="time" name="end_time" id="end_time" class="INPUT__INPUT" required>
                            <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="end_time_ph">Ending Time : </span>
                        </label>

                        <div class="POP_UP__CONTROLS">
                            <button type="button" class="CONTROLS__CLOSE" id="submitButton">Close</button>
                            <button type="reset" class="CONTROLS__RESET" id="resetButton">Reset</button>
                            <button type="submit" class="CONTROLS__SUBMIT">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
            include 'conn.php';

            if (!$_conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $user_id = $_COOKIE['UID'];
            $sql = "SELECT * FROM tasks WHERE user_id = '$user_id'";
            $result = mysqli_query($_conn, $sql);

            // Check if there are results
            if (mysqli_num_rows($result) > 0) {
                // Initialize an array to store the data
                $taskList = array();

                // Fetch each row as an associative array
                while ($row = mysqli_fetch_assoc($result)) {
                    $TaskList[] = array(
                        'task_id' => $row['id'],
                        'task_title' => $row['task_title'],
                        'task_desc' => $row['task_desc'],
                        'start_date' => $row['start_date'],
                        'end_date' => $row['end_date'],
                        'start_time' => $row['start_time'],
                        'end_time' => $row['end_time'],
                        'created_at' => $row['created_at'],
                        'user_id' => $row['user_id'],
                        'status' => $row['status'],
                        'category' => $row['category']
                    );
                }


                // Send the task list to the frontend in JSON format
                echo "<script>";
                echo "var TaskList = " . json_encode($TaskList) . ";";
                echo "</script>";
            }

            mysqli_close($_conn);
            ?>

            <h1 class="ARTICLE_TITLE">Calendar</h1>
            <section class="CALENDAR">
                <div class="CALENDAR__HEADER">
                    <h1 class="CALENDAR__TITLE" id="calendar__title1"><span id="MONTH"></span> <span id="YEAR"></span></h1>
                    <div class="TITLE__CONTAINER">
                        <div class="Header__Container">
                            <button class="Header__Button" id="today"><span class=" Header__Wording">Today</span></button>
                        </div>
                        <div class="Header__Container">
                            <button class="Header__Button OPEN_POP_UP"><span class="material-icons Header__Wording">add</span></button>
                        </div>
                        <div class="Header__Container">
                            <button type="button" class="Header__Button SelectView" id="left"><span class="material-icons Header__Wording">arrow_left</span></button>
                            <span style="margin: 0 1rem;">Week</span>
                            <button class="Header__Button SelectView" id="right"><span class="material-icons Header__Wording">arrow_right</span></button>
                        </div>
                    </div>
                </div>

                <div class="CALENDAR__CONTENT">
                    <div class="CALENDAR__CONTENT__CONTAINER" id="weekContent">
                        <div class="HEADER">
                            <ul class="DAY_NAME">
                                <!-- store data in li -->
                                <li data-mobile="S" data-tablet="Sun"><span>Sunday</span></li>
                                <li data-mobile="M" data-tablet="Mon"><span>Monday</span></li>
                                <li data-mobile="T" data-tablet="Tue"><span>Tuesday</span></li>
                                <li data-mobile="W" data-tablet="Wed"><span>Wednesday</span></li>
                                <li data-mobile="T" data-tablet="Thu"><span>Thursday</span></li>
                                <li data-mobile="F" data-tablet="Fri"><span>Friday</span></li>
                                <li data-mobile="S" data-tablet="Sat"><span>Saturday</span></li>
                            </ul>

                            <ul class="DAY_NUM">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>

                        <div class="TIMESLOT__CONTAINER">
                            <ul class="TIMESLOT">
                                <li>0:00</li>
                                <li>1:00</li>
                                <li>2:00</li>
                                <li>3:00</li>
                                <li>4:00</li>
                                <li>5:00</li>
                                <li>6:00</li>
                                <li>7:00</li>
                                <li>8:00</li>
                                <li>9:00</li>
                                <li>10:00</li>
                                <li>11:00</li>
                                <li>12:00</li>
                                <li>13:00</li>
                                <li>14:00</li>
                                <li>15:00</li>
                                <li>16:00</li>
                                <li>17:00</li>
                                <li>18:00</li>
                                <li>19:00</li>
                                <li>20:00</li>
                                <li>21:00</li>
                                <li>22:00</li>
                                <li>23:00</li>
                                <li>24:00</li>
                            </ul>

                        </div>

                        <div class="EVENT__CONTAINER">
                        </div>

                    </div>

                    <!-- <div class="CALENDAR__CONTENT__CONTAINER" id="monthContent">Month</div> -->
                    <!-- <div class="CALENDAR__CONTENT__CONTAINER" id="yearContent">Year</div> -->
                </div>

            </section>
        </article>

    </main>
    <script src="Registered.js" defer></script>
    <script src="Calendar.js" defer></script>
</body>

</html>