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


    $_conn->begin_transaction(); // Start transaction

    try {
        // First, delete the member from the team
        $sql1 = "DELETE FROM team WHERE team_name = ? AND member_id = ?";
        $stmt1 = $_conn->prepare($sql1);
        $stmt1->bind_param("si", $team_name, $member_id);
        $stmt1->execute();
        $stmt1->close();

        // Then, delete tasks where this member is assigned_to
        $sql2 = "DELETE FROM group_tasks WHERE FIND_IN_SET(?, assigned_to)";
        $stmt2 = $_conn->prepare($sql2);
        $stmt2->bind_param("i", $member_id);
        $stmt2->execute();
        $stmt2->close();

        // Then, delete tasks where this member is the assigned_by
        $sql3 = "DELETE FROM group_tasks WHERE assigned_by = ?";
        $stmt3 = $_conn->prepare($sql3);
        $stmt3->bind_param("i", $member_id);
        $stmt3->execute();
        $stmt3->close();

        $_conn->commit(); // Commit transaction
        echo "$memberName has been removed!";
    } catch (Exception $e) {
        $_conn->rollback(); // Rollback transaction in case of an error
        echo "Error removing member and tasks: " . $e->getMessage();
    }

    $_conn->close();
}
