<?php

session_start();
include "conn.php";

if (!isset($_SESSION['userID'])) {
    echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
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
                        <a href="../Landing_Page/GetHelp.php" target="_blank" class="HEADER__UL__ICON">
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
                <h4 class="NAV_TITLE">Direct Messages</h4>
                <ul class="DM_USER_LIST">
                    <li>
                        <a href="CommunityDMPage?receiver_id=3&name=Michael+Brown" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 1')">
                            Micheal Brown
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage?receiver_id=2&name=Jane+Smith" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 2')">
                            Jane Smith
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage?receiver_id=4&name=Sarah+Lee" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 2')">
                        Sarah Lee
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <article class="GOAL__MAIN">
            <button class="GOAL__SET" onclick="togglePopup()">show</button>
            <div class="GOAL__INPUT" style="display: none;">
                <form action="GoalAdd.php" method="POST" class="GOAL__FORM">

                    <label class="INPUT__BOX">
                        <input type="text" name="goal_title" class="INPUT__INPUT">
                        <span class="INPUT__PLACEHOLDER">Goal Title</span>
                    </label>

                    <label class="INPUT__BOX">
                        <input type="text" name="goal_description" class="INPUT__INPUT">
                        <span class="INPUT__PLACEHOLDER">Description</span>
                    </label>

                    <div style="display: flex; flex-direction:column;">
                        <label>Goal Type:</label>
                        <select name="goal_type" required>
                            <option value="short-term">Short-Term</option>
                            <option value="long-term">Long-Term</option>
                        </select>
                    </div>

                    <label class="INPUT__BOX">
                        <input type="date" name="start_time" id="start_time" class="INPUT__INPUT" max="2050-01-01" required>
                        <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="start_time_ph">Starting Date</span>
                    </label>

                    <label class="INPUT__BOX">
                        <input type="date" name="end_time" id="end_time" class="INPUT__INPUT" max="2050-01-01" required>
                        <span class="INPUT__PLACEHOLDER AUTOFOCUS" id="end_time_ph">Ending Date</span>
                    </label>

                    <div style="display: flex; flex-direction:column;">
                        <label>Reminder Time:</label>
                        <input type="datetime-local" name="reminder_time" id="reminder_time">
                    </div>
                    <button type="submit" class="GOAL__SET">Set Goal</button>
                </form>
            </div>
            <div class="GOAL__DISPLAY">
                <?php

                $user_id = $_SESSION['userID'];

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
                        <div class="GOAL__CARD">
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

            <div>
                <form action="Goal.php" method="POST">
                    <label>Goal ID:</label>
                    <input type="number" name="goal_id" required>

                    <label>Progress:</label>
                    <input type="number" name="progress" min="0" max="100" required>

                    <button type="submit" class="GOAL__SET">Update Progress</button>
                </form>
                <?php

                $user_id = $_SESSION['userID'];

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
        </article>
    </main>
    <script src="Registered.js" defer></script>
    <script src="Goal.js" defer></script>
</body>

</html>