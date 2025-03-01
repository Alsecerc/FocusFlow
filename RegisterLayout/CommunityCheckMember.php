<?php
include "conn.php";

if (!isset($_GET['member_id']) || empty($_GET['member_id'])) {
    echo json_encode(["exists" => false]);
    exit;
}

$member_id = $_GET['member_id'];

$sql = "SELECT id FROM users WHERE id = ?";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$stmt->store_result();

echo json_encode(["exists" => $stmt->num_rows > 0]);
$stmt->close();
$_conn->close();
?>
