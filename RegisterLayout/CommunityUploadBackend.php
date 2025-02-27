<?php
include 'conn.php'; // Database connection

session_start();


if (isset($_POST['upload'])) {
    $file = $_FILES['file'];

    // File properties
    $fileName = basename($file['name']);
    $fileType = $file['type'];
    $fileSize = $file['size'];
    $targetDir = "uploads/"; // Folder to store files
    $targetFilePath = $targetDir . $fileName;
    $teamName = isset($_POST['team_name']) ? $_POST['team_name'] : ''; 

    // Ensure the folder exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $userID = $_COOKIE['UID'];

    // Move file to the folder
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        // Insert file details into the database
        $sql = "INSERT INTO files (user_id, team_name, file_name, file_type, file_size, file_path, uploaded_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $_conn->prepare($sql);
        $stmt->bind_param("isssis", $userID, $teamName, $fileName, $fileType, $fileSize, $targetFilePath);
        if ($stmt->execute()) {
            echo "<script>
                        alert('File uploaded successfully!');
                        window.parent.closePopup(); // Closes popup after success
                      </script>";
        } else {
            echo "Database error: " . $stmt->error;
        }
    } else {
        echo "Failed to upload file.";
    }
}
