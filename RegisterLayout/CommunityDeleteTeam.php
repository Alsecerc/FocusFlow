<?php
include "conn.php";

if (!isset($_GET['team'])) {
    die("Error: No team specified!");
}

$teamName = $_GET['team'];

// delete all group task
$deleteTasksSql = "DELETE FROM group_tasks WHERE team_name = ?";
$deleteTasksStmt = $_conn->prepare($deleteTasksSql);
$deleteTasksStmt->bind_param("s", $teamName);
$deleteTasksStmt->execute();
$deleteTasksStmt->close();


// Get team ID before deleting
$sql = "SELECT id FROM team WHERE team_name = ?";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("s", $teamName);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $teamID = $row['id'];

    // Delete team from database
    $deleteSql = "DELETE FROM team WHERE id = ?";
    $deleteStmt = $_conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $teamID);

    if ($deleteStmt->execute()) {
        echo "Team deleted successfully!";
    } else {
        echo "Error deleting team.";
    }

    $deleteStmt->close();
} else {
    echo "Error: Team not found.";
}

$stmt->close();
$_conn->close();
?>
