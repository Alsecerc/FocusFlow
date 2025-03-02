<?php
include "conn.php";

if (!isset($_GET['member_name']) || empty($_GET['member_name'])) {
    echo json_encode(["exists" => false]);
    exit;
}

$member_name = $_GET['member_name'];

$sql = "SELECT id FROM users WHERE name = ?";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("i", $member_name);
$stmt->execute();
$stmt->store_result();

echo json_encode(["exists" => $stmt->num_rows > 0]);
$stmt->close();
$_conn->close();
?>
