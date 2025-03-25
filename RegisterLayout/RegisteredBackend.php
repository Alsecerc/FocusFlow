<?php
include 'conn.php'; 

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $communityName = trim($_POST['communityName']);
    $userID = $_COOKIE['UID'];

    if (empty($communityName)) {
        echo json_encode(["success" => false, "message" => "Community name cannot be empty."]);
        exit();
    }

    // Check if the team name exists
    $stmt = $_conn->prepare("SELECT id FROM team WHERE team_name = ?");
    $stmt->bind_param("s", $communityName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Community name already exists."]);
    } else {
        // Insert new team
        $stmt = $_conn->prepare("INSERT INTO team (team_name, leader_id, member_id, created_at) VALUES (?, ?,?, NOW())");
        $stmt->bind_param("sii", $communityName, $userID, $userID);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Community created successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error creating community."]);
        }
    }

    $stmt->close();
    $_conn->close();
}
?>