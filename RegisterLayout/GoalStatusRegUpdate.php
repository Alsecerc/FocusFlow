<?php
include 'conn.php'; // Ensure database connection

// Get current date-time
$current_date = date('Y-m-d H:i:s');

// Update overdue tasks
$sql = "UPDATE goals SET status = 'failed' WHERE end_date < ? AND status NOT IN ('fail', 'completed')";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("s", $current_date);

if ($stmt->execute()) {
    echo "Overdue tasks updated!";
} else {
    echo "Error updating tasks: " . $stmt->error;
}

$stmt->close();
$_conn->close();
?>
