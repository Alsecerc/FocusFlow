<?php

require_once "conn.php";

$user_id = $_COOKIE['UID'];

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST['goal_id'])) {
        $goal_id = $_POST['goal_id'];

        // SQL query to delete the goal
        $sql = "DELETE FROM goals WHERE goal_id = ? AND user_id = ?";

        $stmt = $_conn->prepare($sql);
        $stmt->bind_param("ii", $goal_id, $user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Goal Deleted Successfully!')</script>";
            } else {
                echo "<script>alert('No goal found with the given ID for this user.')</script>";
            }
        } else {
            $_SESSION['message'] = "Error: " . $_conn->error;
        }

        $stmt->close();
        $_conn->close();

        // ✅ Redirect to clear $_POST data
        echo "<script>window.location.href='Goal.php'</script>";
    } else {
        $_SESSION['message'] = "Error: Missing goal_id!";
    }
}
