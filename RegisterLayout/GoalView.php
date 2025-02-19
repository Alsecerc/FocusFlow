<?php
session_start();
include "conn.php";

if (!isset($_COOKIE['id'])) {
    die("User not logged in");
}

$user_id = $_COOKIE['id'];

$sql = "SELECT * FROM goals WHERE user_id = ?";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p>Title: " . htmlspecialchars($row['goal_title']) . "</p>";
        echo "<p>Type: " . $row['goal_type'] . "</p>";
        echo "<p>Progress: " . $row['progress'] . "%</p>";
        echo "<p>Status: " . $row['status'] . "</p>";
        echo "<p>Reminder: " . $row['reminder_time'] . "</p>";
        echo "<hr>";
    }
} else {
    echo "No goals set yet.";
}

$stmt->close();
$_conn->close();
?>

<style>
    .progress-bar {
        width: 100%;
        background-color: #ddd;
    }

    .progress-fill {
        height: 20px;
        background-color: green;
        width: 0%;
        text-align: center;
        color: white;
    }
</style>

<div class="progress-bar">
    <div class="progress-fill" style="width: <?= $row['progress'] ?>%;">
        <?= $row['progress'] ?>%
    </div>
</div>