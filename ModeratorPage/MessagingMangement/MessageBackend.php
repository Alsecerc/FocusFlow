<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php";

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'fetch_messages':
        if (isset($_GET['sender_id']) && isset($_GET['receiver_id'])) {
            $senderId = intval($_GET['sender_id']);
            $receiverId = intval($_GET['receiver_id']);

            $sql = "SELECT sender_id, receiver_id, message_text, sent_at 
                    FROM message
                    WHERE (sender_id = $senderId AND receiver_id = $receiverId) 
                       OR (sender_id = $receiverId AND receiver_id = $senderId)
                    ORDER BY sent_at ASC";

            $result = $_conn->query($sql);
            $messages = [];

            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }

            echo json_encode($messages);
        }
        break;

    case 'fetch_receivers':
        if (isset($_GET['sender_id'])) {
            $senderId = intval($_GET['sender_id']);

            $sql = "SELECT DISTINCT receiver_id FROM messages WHERE sender_id = $senderId";
            $result = $_conn->query($sql);

            $receivers = [];
            while ($row = $result->fetch_assoc()) {
                $receivers[] = $row['receiver_id'];
            }

            echo json_encode($receivers);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid action"]);
        break;
}
?>