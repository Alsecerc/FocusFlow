<?php
session_start();
include "../../RegisterLayout/conn.php";

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['usertype'] !== 1) {
    header("Location: ../../RegisterLayout/Login.php");
    exit();
}

// Fetch all survey responses with user information
$sql = "SELECT sr.*, u.name as user_name 
        FROM survey_responses sr 
        JOIN users u ON sr.id = u.id";
$result = $_conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Admin.css">
    <title>Survey Responses Dashboard</title>
    <style>
        .admin-container {
            display: flex; /* Add this */
            margin-left: 0; /* Remove this margin */
            width: 100%; /* Update this */
            min-height: 100vh;
            background-color: #f4f4f4;
        }
        .DASH__MAIN {
            flex: 1; /* Change from flex-grow to flex */
            margin-left: 250px; /* Move margin here instead */
            padding: 20px;
            background-color: #f9f9f9;
        }
        .survey-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .survey-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .survey-table th, .survey-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .survey-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .suggestions-cell {
            max-width: 300px;
            white-space: pre-wrap;
        }
        .survey-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .survey-header h2 {
            margin: 0;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="admin-container">
    <?php include "AdminSidebar.php"; ?>
        <div class="DASH__MAIN">
            <div class="survey-container">
                <div class="survey-header">
                    <h2>Survey Responses</h2>
                </div>
                
                <table class="survey-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Professional Role</th>
                            <th>Ease of Use</th>
                            <th>Most Used Feature</th>
                            <th>Impact</th>
                            <th>Suggestions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($response = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($response['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($response['profession_role']); ?></td>
                            <td><?php echo htmlspecialchars($response['ease_of_use']); ?></td>
                            <td><?php echo htmlspecialchars($response['most_used_feature']); ?></td>
                            <td><?php echo htmlspecialchars($response['impact']); ?></td>
                            <td class="suggestions-cell"><?php echo htmlspecialchars($response['suggestions']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>