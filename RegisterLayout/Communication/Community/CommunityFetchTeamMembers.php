<?php
include $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php";

header("Content-Type: application/json");

// Get the team name from the request
$teamName = isset($_GET['team']) ? $_GET['team'] : '';

if (!$teamName) {
    echo json_encode(["error" => "No team name provided"]);
    exit;
}

// SQL query to fetch members based on the given team name
$sql = "SELECT 
            t.leader_id, 
            u1.name AS leader_name, 
            t.member_id, 
            u2.name AS member_name 
        FROM team t
        LEFT JOIN users u1 ON t.leader_id = u1.id
        LEFT JOIN users u2 ON t.member_id = u2.id
        WHERE t.team_name = ?"; 

$stmt = $_conn->prepare($sql);
$stmt->bind_param("s", $teamName);
$stmt->execute();
$result = $stmt->get_result();

$members = array();
$seenLeaders = array();
$seenMembers = array();

while ($row = $result->fetch_assoc()) {
    // Add leader only once
    if (!isset($seenLeaders[$row["leader_id"]])) {
        $members[] = array(
            "id" => $row["leader_id"],
            "name" => $row["leader_name"],
            "role" => "Leader"
        );
        $seenLeaders[$row["leader_id"]] = true; // Mark leader as added
    }

    // Add members only once
    if (!isset($seenMembers[$row["member_id"]])) {
        $members[] = array(
            "id" => $row["member_id"],
            "name" => $row["member_name"],
            "role" => "Member"
        );
        $seenMembers[$row["member_id"]] = true; // Mark member as added
    }
}

echo json_encode($members);
$_conn->close();
?>
