<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $team_name = $_POST['team_name'];
    $member_id = $_POST['member_id'];

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
        echo "Member, assigned tasks, and assigned-by tasks removed successfully!";
    } catch (Exception $e) {
        $_conn->rollback(); // Rollback transaction in case of an error
        echo "Error removing member and tasks: " . $e->getMessage();
    }

    $_conn->close();
}
?>
