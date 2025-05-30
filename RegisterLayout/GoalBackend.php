<?php
include "conn.php"; // Database connection file

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case "Update":
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
            break;


        case "Add":
            if (!isset($_COOKIE['UID'])) {
                echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
                exit();
            }

            $user_id = $_COOKIE['UID'];
            $goal_title = $_POST['goal_title'];
            $goal_description = $_POST['goal_description'];
            $goal_type = $_POST['goal_type'];
            $start_date = $_POST['start_time'];
            $end_date = $_POST['end_time'];
            $reminder_time = $_POST['reminder_time'];

            $sql = "INSERT INTO goals (user_id, goal_title, goal_description, goal_type, start_date, end_date, reminder_time) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $_conn->prepare($sql);
            $stmt->bind_param("issssss", $user_id, $goal_title, $goal_description, $goal_type, $start_date, $end_date, $reminder_time);

            if ($stmt->execute()) {
                echo "<script>
                alert('Goal added successfully!');
                window.location.href='Goal.php'; // Closes popup after success
                </script>";
            } else {
                echo "Error: " . $_conn->error;
            }

            $stmt->close();
            $_conn->close();
            break;

        case "Remove":
            if (isset($_POST['goal_id'])) {

                $user_id = $_COOKIE['UID'];
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
            break;
            
        case "Reminder":
            function notificationExists($_conn, $user_id, $message)
            {
                $sql = "SELECT id FROM notifications WHERE user_id = ? AND notification_message = ?";
                $stmt = $_conn->prepare($sql);
                $stmt->bind_param("is", $user_id, $message);
                $stmt->execute();
                $stmt->store_result();
                $exists = $stmt->num_rows > 0; // Check if any rows exist
                $stmt->close();
                return $exists;
            }

            function storeNotification($_conn, $user_id, $message)
            {
                if (!notificationExists($_conn, $user_id, $message)) {
                    $sql = "INSERT INTO notifications (type, user_id, notification_message) VALUES (?, ?, ?)";
                    $stmt = $_conn->prepare($sql);
                    $type = "system"; // Ensure correct data type for bind_param
                    $stmt->bind_param("sis", $type, $user_id, $message);
                    $stmt->execute();
                    $stmt->close();
                }
            }

            date_default_timezone_set("Asia/Kuala_Lumpur");

            $current_time = date("Y-m-d H:i:00"); // Current time (rounded to the nearest minute)
            $one_hour_later = date("Y-m-d H:i:00", strtotime("+1 hour"));

            // TODO: Debug
            error_log("Checking reminders between $current_time and $one_hour_later");

            // Fetch goals with reminders within the next hour
            $sql = "SELECT g.goal_id, g.goal_title, g.reminder_time, g.user_id, u.email 
                        FROM goals g 
                        JOIN users u ON g.user_id = u.id 
                        WHERE g.reminder_time BETWEEN ? AND ?";
            $stmt = $_conn->prepare($sql);
            $stmt->bind_param("ss", $current_time, $one_hour_later);
            $stmt->execute();
            $result = $stmt->get_result();

            $reminders = [];
            while ($row = $result->fetch_assoc()) {
                error_log("Fetched Goal: " . json_encode($row));

                $user_id = $row['user_id'];
                $goal_title = $row['goal_title'];
                $reminder_time = $row['reminder_time'];

                $message = "Reminder: Your goal '$goal_title' is due at $reminder_time!";

                // Store in notifications table only if it does not exist
                if (!notificationExists($_conn, $user_id, $message)) {
                    storeNotification($_conn, $user_id, $message);
                    $reminders[] = [
                        "user_id" => $user_id,
                        "goal_title" => $goal_title,
                        "reminder_time" => $reminder_time,
                        "message" => $message
                    ];
                }
            }

            $stmt->close();
            $_conn->close();

            // TODO: Debug
            if (empty($reminders)) {
                error_log("No reminders matched the condition.");
            } else {
                error_log("Reminders stored: " . json_encode($reminders));
            }

            echo json_encode(["status" => "success", "message" => "Reminders processed"]);
            break;


        default:
            echo json_encode(["error" => "Invalid action"]);
    }
}
