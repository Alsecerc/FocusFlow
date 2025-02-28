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
    <title>Community</title>

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

            $sql = "SELECT DISTINCT team_name FROM team WHERE leader_id = ? OR member_id = ?";
            $stmt = $_conn->prepare($sql);
            $stmt->bind_param("ii", $loggedInUserID, $loggedInUserID);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            <nav class="SIDEBAR__NAV COMMUNITY">
                <h4 class="NAV_TITLE">Community</h4>
                <ul>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<li>
                        <a href="CommunityPage.php?team=' . urlencode($row['team_name']) . '" class="SIDEBAR__ITEM COMMUNITY__ITEM">
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

        <article class="COMMUNITY1">
            <section class="COMMUNITY1__HEADER">
                <?php
                $teamName = $_GET['team'];

                echo " <h1> $teamName </h1>";
                ?>
                <div class="COMMUNITY1__HEADER__RIGHT">

                    <div>
                        <p class="COMMUNITY__HEADER__TITLE">Add Task</p>
                        <a href="#" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                add_circle_outline
                            </span>
                        </a>
                    </div>

                    <div>
                        <p class="COMMUNITY__HEADER__TITLE">Upload File</p>
                        <a href="#" class="HEADER__UL__ICON" onclick="openPopup()">
                            <span class="material-icons">
                                upload_file
                            </span>
                        </a>
                        <div class="COM__POPUP__OVERLAY" id="popupOverlay">
                            <div class="COM__POPUP">
                                <button class="close-btn" onclick="closePopup()">×</button>
                                <iframe id="popupIframe" class="popup-iframe"></iframe>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="COMMUNITY__HEADER__TITLE">View Share File</p>
                        <a href="#" class="HEADER__UL__ICON" onclick="openPopup1()">
                            <span class="material-icons">
                                folder
                            </span>
                        </a>
                        <div class="COM__POPUP__OVERLAY" id="popupOverlay">
                            <div class="COM__POPUP">
                                <button class="close-btn" onclick="closePopup2()">×</button>
                                <iframe id="popupIframe" class="popup-iframe"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="COMMUNITY1__MAIN">
                <div class="TASK__CONTAINER">
                    <h3>Task List</h3>
                    <?php
                    include "conn.php";

                    if (!isset($_GET['team']) || empty($_GET['team'])) {
                        die("<p>Error: Team name is required!</p>");
                    }

                    $teamName = $_GET['team'];

                    // Prepare query to get tasks assigned to the team
                    $sql = "SELECT gt.task_name, gt.task_description, gt.status, gt.due_date, u.name AS assigned_to_name 
                FROM group_tasks gt
                JOIN users u ON gt.assigned_to = u.id
                WHERE gt.team_name = ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("s", $teamName);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo '<ul class="TASK_LIST">';
                        while ($row = $result->fetch_assoc()) {
                            $taskName = htmlspecialchars($row['task_name']);
                            $taskDesc = htmlspecialchars($row['task_description']);
                            $status = htmlspecialchars($row['status']);
                            $dueDate = htmlspecialchars($row['due_date']);
                            $assignedTo = htmlspecialchars($row['assigned_to_name']);

                            // Assign class based on task status
                            $statusClass = ($status == 'completed') ? 'task-completed' : (($status == 'in progress') ? 'task-inprogress' : 'task-pending');

                            echo "<li class='TASK_ITEM $statusClass'>
                        <h4>$taskName</h4>
                        <p><strong>Description:</strong> $taskDesc</p>
                        <p><strong>Assigned To:</strong> $assignedTo</p>
                        <p><strong>Status:</strong> <span class='task-status'>$status</span></p>
                        <p><strong>Due Date:</strong> $dueDate</p>
                    </li>";
                        }
                        echo '</ul>';
                    } else {
                        echo "<p>No tasks available for this team.</p>";
                    }

                    $stmt->close();
                    $_conn->close();
                    ?>
                </div>
            </section>




            <section class="COMMUNITY1__MEMBER">
                <div class="LEADER__CONTAINER">
                    <h3>Leader</h3>

                    <?php
                    if (!isset($_GET['team']) || empty($_GET['team'])) {
                        die("<p>Error: Team name is required!</p>");
                    }

                    include "conn.php";

                    // Get the team name from the URL
                    $teamName = $_GET['team'];
                    $currentUserID = isset($_COOKIE['UID']) ? $_COOKIE['UID'] : null; // Get logged-in user ID from cookie

                    // Prepare the query to get team details
                    $sql = "SELECT * FROM team WHERE team_name = ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("s", $teamName);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if (!$result) {
                        echo "<p>Error loading team: " . htmlspecialchars($_conn->error) . "</p>";
                    } else {
                        if ($row = $result->fetch_assoc()) {
                            $leaderID = $row['leader_id']; // Assuming 'leader_id' exists in 'team' table

                            // Fetch leader's name securely
                            $sqlLeader = "SELECT name FROM users WHERE id = ?";
                            $stmtLeader = $_conn->prepare($sqlLeader);
                            $stmtLeader->bind_param("i", $leaderID);
                            $stmtLeader->execute();
                            $leaderResult = $stmtLeader->get_result();

                            if ($leaderRow = $leaderResult->fetch_assoc()) {
                                $leaderName = htmlspecialchars($leaderRow['name']);

                                // If logged-in user is the leader, show as <p>, otherwise show as <a>
                                if ($currentUserID == $leaderID) {
                                    echo "<p class='LEADER'><span class='material-icons'>account_circle</span>$leaderName (You)</p>";
                                } else {
                                    echo "<li class='MEMBER'><a href='CommunityDMPage.php?receiver_id=" . urlencode($leaderID) . "&name=" . urlencode($leaderName) . "'><span class='material-icons'>boy</span>$leaderName</a></li>";
                                }
                            }

                            $stmtLeader->close();
                        }
                    }

                    $stmt->close();
                    ?>
                </div>
                <div class="MEMBER__CONTAINER">
                    <h3>Member List</h3>
                    <?php


                    if (!isset($_GET['team']) || empty($_GET['team'])) {
                        die("<p>Error: Team name is required!</p>");
                    }

                    // Sanitize the team name
                    $teamName = $_GET['team'];

                    // Prepare the first query securely
                    $sql = "SELECT * FROM team WHERE team_name = ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("s", $teamName);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if (!$result) {
                        echo "<p>Error loading members: " . htmlspecialchars($_conn->error) . "</p>";
                    } else {
                        echo '<ul class="MEMBER_LIST">';
                        while ($row = $result->fetch_assoc()) {
                            $memberID = $row['member_id']; // Assuming 'member_id' is the correct column

                            // Securely fetch user details using prepared statements
                            $sql2 = "SELECT * FROM users WHERE id = ?";
                            $stmt2 = $_conn->prepare($sql2);
                            $stmt2->bind_param("i", $memberID);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();

                            if ($row2 = $result2->fetch_assoc()) {
                                $username = htmlspecialchars($row2['name']); // Prevent XSS

                                if ($username == $_COOKIE['USERNAME']) {
                                    echo "<li class='MEMBER'>
                                    <a href='CommunityDMPage.php?receiver_id=" . urlencode($memberID) . "&name=" . urlencode($username) . "' class='memberName'>
                                        <span class='material-icons'>account_circle</span>
                                        <p>$username</p>
                                    </a>
                                  </li>";
                                } else {
                                    echo "<li class='MEMBER'>
                                    <a href='CommunityDMPage.php?receiver_id=" . urlencode($memberID) . "&name=" . urlencode($username) . "' class='memberName'>
                                        <span class='material-icons'>boy</span>
                                        <p>$username</p>
                                    </a>
                                  </li>";
                                }
                            }
                            $stmt2->close();
                        }
                        echo '</ul>';
                    }

                    // Close statements and connection
                    $stmt->close();
                    $_conn->close();
                    ?>
                </div>
            </section>
        </article>

    </main>
    <script src="Registered.js" defer></script>
    <script src="Community.js" defer></script>
</body>

</html>