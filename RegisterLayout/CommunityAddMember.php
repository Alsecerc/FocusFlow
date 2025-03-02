<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $team_name = $_POST['team_name'];
    $memberName = $_POST['member_name'];

    // Query to find the member ID based on the provided name
    $sql = "SELECT id FROM users WHERE name = ?";
    $stmt = $_conn->prepare($sql);
    $stmt->bind_param("s", $memberName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $member_id = $row['id'];
    } else {
        die("Error: Member not found!");
    }

    $stmt->close();

    $leader_id = $_POST['leader_id']; // Assuming leader ID is known

    // Check if the member already exists in the team
    $checkSql = "SELECT id FROM team WHERE team_name = ? AND (member_id = ? OR leader_id = ?)";
    $checkStmt = $_conn->prepare($checkSql);
    $checkStmt->bind_param("sii", $team_name, $member_id, $member_id);
    $checkStmt->execute();
    $checkStmt->bind_result($existing_id);
    $exists = $checkStmt->fetch(); // Fetch result to check if row exists
    $checkStmt->close();

    if ($exists) {
        echo "Member already exists in the team!";
        $_conn->close();
    } else {
        // Insert new member if they do not exist
        $sql = "INSERT INTO team (team_name, leader_id, member_id) VALUES (?, ?, ?)";
        $stmt = $_conn->prepare($sql);
        $stmt->bind_param("sii", $team_name, $leader_id, $member_id);

        if ($stmt->execute()) {
            echo "Member added successfully!";
        } else {
            echo "Error adding member.";
        }
        $stmt->close();
        $_conn->close();
    }
}
