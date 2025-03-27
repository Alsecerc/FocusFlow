<!DOCTYPE html>
<html lang="en">
<?php include $_SERVER['DOCUMENT_ROOT'] . "/RWD_assignment/FocusFlow/RegisterLayout/conn.php"; ?>



<!-- 
1. edit js to suit with backend.php switch case

2. change messages to directmessage






-->













<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Mod.css">
    <link rel="shortcut icon" href="../img/icons8-staff-32.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Messages Management</title>
</head>

<body>
    <?php include "../ModSidebar.php"; ?>

    <main class="DASH__MAIN">
        <div class="WIDGET__CONTAINER four">
            <div class="WIDGET msg_one flex-col">
                <div class="flex-row" style="justify-content: space-between;">
                    <h3>User List</h3>
                </div>

                <div class="USER__LIST">
                    <?php
                    $sql = "SELECT id, name, email FROM users";
                    $result = $_conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='USER__LI' data-user-id='" . $row['id'] . "' onclick='showReceiverDropdown(" . $row['id'] . ")'>";
                            echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
                            echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No users found.</p>";
                    }
                    ?>
                </div>

                <!-- Receiver Dropdown (Initially Hidden) -->
                <div id="receiverDropdown" class="dropdown" style="display: none;">
                    <ul id="receiverList"></ul>
                </div>


            </div>
            <div class="WIDGET msg_two">
                <h3>Conversation</h3>
                <div class="CHAT__CONTAINER">
                    <div id="chatBox"></div>
                </div>
            </div>
            <?php
            $selectedUserId = 1; // Replace with dynamic user selection logic
            $otherUserId = 2; // Replace with receiver ID

            $sql = "SELECT sender_id, receiver_id, message_text, sent_at 
        FROM message
        WHERE (sender_id = $selectedUserId AND receiver_id = $otherUserId) 
           OR (sender_id = $otherUserId AND receiver_id = $selectedUserId)
        ORDER BY sent_at ASC";

            $result = $_conn->query($sql);
            $messages = [];

            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
            ?>

            <script>
                const messages = <?php echo json_encode($messages); ?>;
                const currentUserId = <?php echo $selectedUserId; ?>;

                function displayMessages() {
                    const chatBox = document.getElementById("chatBox");
                    chatBox.innerHTML = "";

                    messages.forEach(msg => {
                        let messageClass = msg.sender_id == currentUserId ? "RIGHT" : "LEFT";
                        let messageElement = `
                <div class="MESSAGE ${messageClass}">
                    <p>${msg.message_text}</p>
                    <small>${msg.sent_at}</small>
                </div>
            `;
                        chatBox.innerHTML += messageElement;
                    });
                }

                document.addEventListener("DOMContentLoaded", displayMessages);
            </script>
        </div>
    </main>

    <footer></footer>
</body>
<script src="../Mod.js"></script>
<script src="../MessagingMangement/Message.js"></script>

</html>