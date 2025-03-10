<?php
include $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php";
session_start();
$userID = $_COOKIE['UID'];

$teamName = $_GET['team'];  // Get team name from URL
$teamName = $_conn->real_escape_string($teamName); // Prevent SQL injection

$sql = "SELECT * FROM files WHERE user_id = ? AND team_name = ? ORDER BY uploaded_at DESC";
$stmt = $_conn->prepare($sql);
$stmt->bind_param("is", $userID, $teamName); // "i" for integer, "s" for string
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>View Files</title>
</head>

<body class="CM__POPUP">
    <h2 class="CM__POPUP__TITLE"><?php echo htmlspecialchars($teamName); ?> : Uploaded Files</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()):
        ?>
            <li>
                <a class="FileLink" href="<?php echo $row['file_path']; ?>" download>
                    <span class="material-icons">
                        file_download
                    </span>
                    <?php echo $row['file_name']; ?>
                </a>

            </li>
        <?php endwhile; ?>
    </ul>
</body>

</html>