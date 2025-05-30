<?php
include "conn.php";
session_start();
include "AccountVerify.php";
requireAuthentication($_conn);

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
    <?php
    include "header.php";
    ?>
    <main>

        <?php
        include "sidebar.php";
        ?>

        <article class="COMMUNITY1">
            <section class="COMMUNITY1__HEADER">
                <?php
                $teamName = $_GET['team'];

                echo " <h1> $teamName </h1>";
                ?>
                <div class="COMMUNITY1__HEADER__RIGHT">

                    <div class="COMM__ADD_TASK">
                        <p class="COMMUNITY__HEADER__TITLE">Add Task</p>
                        <!-- Add Task Button -->
                        <a href="#" class="HEADER__UL__ICON" id="openTaskForm">
                            <span class="material-icons">add_circle_outline</span>
                        </a>

                        <!-- the add task survey -->
                        <div id="taskPopUp" class="ADD_TASK__SURVEY">
                            <h4>Add New Task</h4>
                            <form id="taskPopUpForm" class="POP_UP__FORM">
                                <input type="hidden" name="action" value="AddTask">
                            <form id="taskPopUpForm" class="POP_UP__FORM">
                                <input type="hidden" name="action" value="AddTask">

                                <!-- Task Name -->
                                <label class="INPUT__BOX">
                                    <input type="text" name="task_name" id="task_name" class="INPUT__INPUT" required>
                                    <span class="INPUT__PLACEHOLDER">Task Name:</span>
                                </label>

                                <!-- Assigned To -->
                                <label class="INPUT__BOX">
                                    <select name="assigned_to" id="assigned_to" class="INPUT__INPUT" required>
                                        <option value="" disabled selected>Select Member</option>
                                        <!-- Dynamically load team members from database -->
                                    </select>
                                    <span class="INPUT__PLACEHOLDER AUTOFOCUS">Assign To:</span>
                                </label>

                                <!-- Task Description -->
                                <label class="INPUT__BOX">
                                    <input type="text" name="task_desc" id="task_desc" class="INPUT__INPUT" required>
                                    <span class="INPUT__PLACEHOLDER">Task Description:</span>
                                </label>

                                <!-- Due Date -->
                                <label class="INPUT__BOX">
                                    <input type="date" name="due_date" id="due_date" class="INPUT__INPUT" min="" max="2040-01-01" required>
                                    <span class="INPUT__PLACEHOLDER AUTOFOCUS">Due Date:</span>
                                </label>

                                <!-- Popup Controls -->
                                <div class="POP_UP__CONTROLS">
                                    <button type="button" class="CONTROLS__CLOSE" id="closeTaskForm">Close</button>
                                    <button type="reset" class="CONTROLS__RESET">Reset</button>
                                    <button type="submit" class="CONTROLS__SUBMIT">Submit</button>
                                </div>
                            </form>
                        </div>


                    </div>

                    <?php

                    // Ensure UID cookie and team name exist
                    if (!isset($_COOKIE['UID']) || !isset($_GET['team'])) {
                        die("Unauthorized request.");
                    }

                    $user_id = (int) $_COOKIE['UID'];
                    $team_name = htmlspecialchars(urldecode($_GET['team']));

                    // Check if the user is the leader of the team
                    $sql = "SELECT leader_id FROM team WHERE team_name = ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("s", $team_name);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $isLeader = false;
                    if ($row = $result->fetch_assoc()) {
                        $isLeader = ($row['leader_id'] == $user_id);
                    }

                    $stmt->close();
                    $_conn->close();
                    ?>

                    <!-- FRONTEND -->
                    <?php if ($isLeader): ?>
                        <div>
                            <p class="COMMUNITY__HEADER__TITLE">Remove Task</p>
                            <!-- Add Task Button -->
                            <a href="#" class="HEADER__UL__ICON" id="removeTaskBtn">
                                <span class="material-icons">remove_circle_outline</span>
                            </a>
                        </div>
                    <?php endif; ?>


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


                    <?php if ($isLeader): ?>
                        <div>
                            <p class="COMMUNITY__HEADER__TITLE">Remove Team</p>
                            <!-- Add Task Button -->
                            <a href="#" class="HEADER__UL__ICON" id="deleteTeam">
                                <span class="material-icons">delete_forever</span>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </section>

            <section class="COMMUNITY1__MAIN">
                <div class="TASK__CONTAINER">
                    <div class="TASK__CONTAINER__TITLE">
                        <h3 class="DISPLAY__TITLE">Task List</h3>
                        <div class="DISPLAY__ICON">
                            <div>
                                <p>Completed </p>
                                <span class="material-icons">check_circle</span>
                            </div>
                            <div>
                                <p>In Progress </p>
                                <span class="material-icons">pending</span>
                            </div>
                            <div>
                                <p>Pending </p>
                                <span class="material-icons">watch_later</span>
                            </div>
                        </div>
                    </div>
                    <?php
                    include "conn.php";

                    if (!isset($_GET['team']) || empty($_GET['team'])) {
                        die("<p>Error: Team name is required!</p>");
                    }

                    $teamName = $_GET['team'];

                    // Prepare query to get tasks assigned to the team
                    $sql = "SELECT 
                    gt.id,
                    gt.task_name, 
                    gt.task_description, 
                    gt.status, 
                    gt.assigned_at, 
                    GROUP_CONCAT(DISTINCT u1.name SEPARATOR ', ') AS assigned_to_names, 
                    u2.name AS assigned_by_name
                FROM group_tasks gt
                JOIN users u1 ON FIND_IN_SET(u1.id, gt.assigned_to)
                JOIN users u2 ON gt.assigned_by = u2.id
                WHERE gt.team_name = ?
                GROUP BY gt.id, gt.task_name, gt.task_description, gt.status, gt.assigned_at, u2.name";


                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("s", $teamName);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo '<ul class="TASK_LIST" id="taskList">';
                        while ($row = $result->fetch_assoc()) {
                            $taskID = htmlspecialchars($row['id']);
                            $taskName = htmlspecialchars($row['task_name']);
                            $taskDesc = htmlspecialchars($row['task_description']);
                            $status = htmlspecialchars($row['status']);
                            // Remove due_date reference since column doesn't exist
                            $assignedTo = htmlspecialchars($row['assigned_to_names']); // Multiple people assigned
                            $assignedFrom = htmlspecialchars($row['assigned_by_name']);
                            $assignedAt = htmlspecialchars($row['assigned_at']);

                            // Assign class and icon based on task status
                            $statusClass = ($status == 'completed') ? 'task-completed' : (($status == 'in progress') ? 'task-inprogress' : 'task-pending');
                            $statusIcon = ($status == 'completed') ? 'check_circle' : (($status == 'in progress') ? 'pending' : 'watch_later');

                            echo "<li class='TASK_ITEM $statusClass' data-task-id='$taskID'>
                            <div class='REMOVE__OVERLAY REMOVE__OVERLAY__HIDE'><span class='material-icons'>
                            do_disturb_on
                            </span></div>
                                <div class='TASK_ITEM_TITLE'>
                                
                                    <h4>$taskName</h4>
                                    
                                    <div class='TASK_STATUS_DROPDOWN'>
                                        <button class='task-status-btn' onclick='toggleDropdown(this)'>
                                            <span class='material-icons'>$statusIcon</span>
                                        </button>
                                        
                                        <div class='dropdown-menu'>
                                            <button onclick='updateStatus($taskID, \"pending\")'>Pending</button>
                                            <button onclick='updateStatus($taskID, \"in progress\")'>In Progress</button>
                                            <button onclick='updateStatus($taskID, \"completed\")'>Completed</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Remove the due date display -->
                                <div class='TASK_ITEM_MIDDLE'>
                                    <strong>Description</strong>
                                    <p>$taskDesc</p>
                                </div>
                                <div class='TASK_ITEM_BOTTOM'>
                                    <h4>Members Assigned</h4>
                                    <p>$assignedTo</p> <!-- Now displays multiple names -->
                                </div>
                                <small><strong>Assigned By: </strong>$assignedFrom</small><br>
                                <small><strong>At: </strong>$assignedAt</small>
                            </li>";
                        }

                        echo '</ul>';
                    } else {
                        echo "<p class='NO_TASK__FOUND'>No tasks found for this team.</p>";
                    }


                    $stmt->close();
                    ?>
                </div>
            </section>




            <section class="COMMUNITY1__MEMBER">

                <button class="RESPONSIVE__MEMBER_BUTTON"><span class="material-icons RESPONSIVE__SHOW_ICON">
                        keyboard_double_arrow_left
                    </span></button>


                <div class="LEADER__CONTAINER">
                    <div class="MEMBER__MANAGE">
                        <button class="HEADER__UL__ICON CLICKABLE" onclick="addMember()">
                            <span class="material-icons">person_add</span>
                        </button>


                        <!-- check if user is leader then hides if he is not -->
                        <?php

                        $teamName = $_GET['team']; // Get team name from URL
                        $currentUserID = isset($_COOKIE['UID']) ? $_COOKIE['UID'] : null;

                        // Fetch leader_id for this team
                        $sql = "SELECT leader_id FROM team WHERE team_name = ?";
                        $stmt = $_conn->prepare($sql);
                        $stmt->bind_param("s", $teamName);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $leaderID = $row['leader_id'];

                        $stmt->close();
                        ?>

                        <?php if ($currentUserID == $leaderID): ?>
                            <button class="HEADER__UL__ICON CLICKABLE" onclick="removeMember()">
                                <span class="material-icons">person_remove</span>
                            </button>
                        <?php endif; ?>



                    </div>
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
                                    echo "<li class='MEMBER'><a href='CommunityDMPage.php?receiver_id=" . urlencode($leaderID) . "&name=" . urlencode($leaderName) . "'><span class='material-icons'>person</span>$leaderName</a></li>";
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
                    $sql = "SELECT id, name FROM users WHERE id IN (
                          SELECT member_id FROM team WHERE team_name = ?
                      ) AND id != ?";
                    $stmt = $_conn->prepare($sql);
                    $stmt->bind_param("si", $teamName, $leaderID);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if (!$result) {
                        echo "<p>Error loading members: " . htmlspecialchars($_conn->error) . "</p>";
                    } else {
                        echo '<ul class="MEMBER_LIST">';
                        while ($memberRow = $result->fetch_assoc()) {
                            $memberID = $memberRow['id'];
                            $memberName = htmlspecialchars($memberRow['name']);
                            if ($memberID == $user_id) {
                                echo "<p class='MEMBER'><span class='material-icons'>account_circle</span>$memberName (You)</p>";
                            } else {
                                echo "<li class='MEMBER'><a href='CommunityDMPage.php?receiver_id=" . urlencode($memberID) . "&name=" . urlencode($memberName) . "'><span class='material-icons'>person</span>$memberName</a></li>";
                            }
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
    <script src="Communication/Community/Community.js" defer></script>
</body>

</html>