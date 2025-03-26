<!DOCTYPE html>
<html lang="en">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php"; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Mod.css">
    <link rel="shortcut icon" href="../img/icons8-staff-32.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Task Management</title>
</head>

<body>
    <?php include "../ModSidebar.php"; ?>

    <main class="DASH__MAIN">
        <div class="TASK__CONTAINER">

            <div class="TASK__WIDGET one ">
                <h3>Create Task</h3>
                <div>

                </div>
            </div>

            <div class="TASK__WIDGET two">
                <div class="flex-row" style="justify-content: space-between;">
                    <h3>Task List</h3>
                    <div>
                        <button onclick="fetchTasks('individual')">Individual Task</button>
                        <button onclick="fetchTasks('team')">Team Task</button>
                    </div>
                </div>

                <table class="TASK__TABLE">
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Status</th>
                            <th id="teamColumn" style="display: none;">Team Name</th>
                        </tr>
                    </thead>
                    <tbody id="taskTableBody">
                        <?php
                        $sql = "
                 SELECT DISTINCT
                     id, task_title AS task_name, task_desc AS task_description, 
                     status, user_id AS assigned_by, category AS assigned_to, -- Ensure assigned_to is present
                     end_date AS due_date, 
                     NULL AS completed_at, NULL AS team_name, NULL AS leader_id, NULL AS member_id
                 FROM tasks
                 WHERE task_title IS NOT NULL
                 UNION ALL
                 SELECT DISTINCT
                     gt.id, gt.task_name, gt.task_description, 
                     gt.status, gt.assigned_by, gt.assigned_to, -- Include assigned_to
                     gt.due_date, gt.completed_at, 
                     t.team_name, t.leader_id, 
                     GROUP_CONCAT(t.member_id ORDER BY t.member_id SEPARATOR ', ') AS members
                 FROM group_tasks gt
                 LEFT JOIN team t ON gt.team_name = t.team_name
                 WHERE gt.team_name IS NOT NULL
                 GROUP BY gt.id, gt.task_name, gt.task_description, gt.status, 
                          gt.assigned_by, gt.assigned_to, -- Ensure it's in GROUP BY
                          gt.due_date, gt.completed_at, t.team_name, t.leader_id;
                 ";


                        $result = $_conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            $taskData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');



                            $taskType = $row['team_name'] ? "team" : "individual";

                            if ($taskType === "team") {
                                $statusClass = ($row['status'] == "pending") ? "L-YEL" : (($row['status'] == "in progress") ? "L-RED" : "L-GRE");
                            } else {
                                $statusClass = ($row['status'] == "Incomplete") ? "L-YEL" : (($row['status'] == "Timeout") ? "L-RED" : "L-GRE");
                            }

                            echo "<tr class='$statusClass' data-type='$taskType' onclick='showTaskDetails($taskData)'>";
                            echo "<td>" . $row['task_name'] . "</td>";
                            echo "<td>" . ucfirst($row['status']) . "</td>";
                            echo "<td class='teamColumn' style='display: none;'>" . ($row['team_name'] ?? "N/A") . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="TASK__WIDGET three ">

            </div>

        </div>
    </main>
</body>
<script src="../Mod.js"></script>
<script src="TaskManagement.js"></script>

</html>