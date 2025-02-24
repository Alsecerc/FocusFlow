<?php

session_start();

if (!isset($_COOKIE['UID'])) {
    echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
    exit();
}
?>

<form action="update_goal.php" method="POST">
    <label>Goal ID:</label>
    <input type="number" name="goal_id" required>

    <label>Progress:</label>
    <input type="number" name="progress" min="0" max="100" required>

    <button type="submit">Update Progress</button>
</form>
<?php

$user_id = $_COOKIE['UID'];
if (isset($_POST['goal_id']) && isset($_POST['progress'])) {
    $goal_id = $_POST['goal_id'];
    $progress = $_POST['progress'];

    // ✅ Proceed with updating the database
    echo "Progress updated!";
} else {
    echo "Error: Missing goal_id or progress!";
}


$sql = "UPDATE goals SET progress = ?, status = CASE WHEN ? = 100 THEN 'completed' ELSE 'in-progress' END 
        WHERE goal_id = ? AND user_id = ?";

$stmt = $_conn->prepare($sql);
$stmt->bind_param("iiii", $progress, $progress, $goal_id, $user_id);

if ($stmt->execute()) {
    echo "Progress updated!";
} else {
    echo "Error: " . $_conn->error;
}

$stmt->close();
$_conn->close();
?>