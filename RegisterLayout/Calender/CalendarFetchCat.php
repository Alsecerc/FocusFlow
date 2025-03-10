<?php
include $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php";

if ($_conn->connect_error) {
    die("Connection failed: " . $_conn->connect_error);
}

$sql = "SELECT DISTINCT category FROM tasks WHERE user_id = " . $_COOKIE['UID']; // Adjust table name
$result = $_conn->query($sql);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category'];
}

echo json_encode($categories);
$_conn->close();
?>
