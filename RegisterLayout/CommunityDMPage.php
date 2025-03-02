<?php

session_start();

if (!isset($_COOKIE['UID'])) {
    echo "<script>alert('Please Log In/ Create an account');window.location.href='../Landing_Page/Homepage.php'</script>";
    exit();
}

include "conn.php";

$result = $_conn->query("SELECT * FROM message ORDER BY sent_at ASC");

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}


echo "<script>";
echo "var MessageList = " . json_encode($messages) . ";";
echo "</script>";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direct Message</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
</head>

<body>
    <header>
        <div class="HEADER__LEFT">
            <button class="HEADER__MENU_BUTTON">
                <div class="HEADER__MENU_ICON"></div>
            </button>
            <a href="Homepage.php">
                <h1 class="HEADER__TITLE">F<span class="material-symbols-outlined HEADER__ICON">
                        schedule
                    </span>cusFlow
                </h1>
            </a>
        </div>

        <div class="HEADER__SEARCH">
            <span class="material-icons SEARCH_ICON">search</span>
            <input type="text" id="searchInput" class="HEADER__SEARCH_INPUT" placeholder="Search..." onkeyup="searchFunction()" autocomplete="off">
            <div id="searchResults" class="SEARCH_RESULTS"></div>
        </div>


        <div class="HEADER__RIGHT">
            <nav>
                <ul class="HEADER__UL">
                    <li class="HEADER__ITEM">
                        <a href="../Landing_Page/GetHelp.php" target="_blank" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                support_agent
                            </span>
                        </a>
                    </li>
                    <li class="HEADER__ITEM" style="position: relative;user-select:none;cursor:pointer;">
                        <div class="HEADER__UL__ICON" id="notiButton">
                            <span class="material-icons">
                                notifications
                            </span>
                        </div>
                        <?php
                        $userID = $_COOKIE['UID'];
                        $sql = "SELECT * FROM notifications WHERE user_id = $userID ORDER BY created_at DESC";
                        $result = $_conn->query($sql);
                        ?>

                        <div class="NOTIFICATION__POPUP" id="notificationPopup" style="overflow-y: auto; cursor:default; display:none;">
                            <?php if ($result->num_rows > 0): ?>
                                <ul id="notificationList">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <?php if ($row['type'] == 'system'): ?>
                                            <li class="NOTI__ITEM">
                                                📢 System Notification: <?= $row['notification_message'] ?>
                                                <small> (<?= $row['created_at'] ?>)</small>
                                            </li>
                                        <?php else: ?>
                                            <li class="NOTI__ITEM NOTI__ITEM__MSG">
                                                <?php
                                                $sql2 = "SELECT * FROM users WHERE id = " . $row['sender_id'];
                                                $result2 = $_conn->query($sql2);
                                                $sender = $result2->fetch_assoc();

                                                if ($result2->num_rows > 0) {
                                                ?>
                                                    <a href="CommunityDMPage?receiver_id=<?= $row['sender_id'] ?>&name=<?= urlencode($sender['name']) ?>" class="NOTI__LINK">
                                                        🗨️ <?= $row['notification_message'] ?>
                                                        <small> (<?= $row['created_at'] ?>)</small>
                                                    </a>
                                                <?php
                                                }
                                                ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </ul>
                            <?php else: ?>
                                <p id="noNotifications">No new notifications</p>
                            <?php endif; ?>
                        </div>


                    </li>
                    <li class="HEADER__ITEM">
                        <a href="Account.php" class="HEADER__UL__ICON">
                            <span class="material-icons">
                                account_circle
                            </span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main  style="overflow-y: auto;">
        <!-- temp SIDEBAR_SHOW -->
        <div class="SIDEBAR" style="overflow-y: auto;">
            <nav class="SIDEBAR__NAV">
                <ul>
                    <li>
                        <a href="Homepage.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">home</span>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="Timer.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">timer</span>Focus Timer
                        </a>
                    </li>
                    <li>
                        <a href="Todo.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">task_alt</span>To Do
                        </a>
                    </li>
                    <li>
                        <a href="Calendar.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">event</span>Calendar
                        </a>
                    </li>
                    <li>
                        <a href="Analytic.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">analytics</span>Analytics
                        </a>
                    </li>
                    <li>
                        <a href="Goal.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">track_changes</span>Goals
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage.php" class="SIDEBAR__ITEM">
                            <span class="material-icons">chat</span>Direct Message
                        </a>
                    </li>
                </ul>
            </nav>

            <?php
            $loggedInUserID = $_COOKIE['UID']; // Assuming user ID is stored in a cookie

            $sql = "SELECT id, team_name FROM team 
            WHERE leader_id = ? OR member_id = ? 
            GROUP BY team_name";
            $stmt = $_conn->prepare($sql);
            $stmt->bind_param("ii", $loggedInUserID, $loggedInUserID);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            <nav class="SIDEBAR__NAV COMMUNITY">
                <h4 class="NAV_TITLE">Community</h4>
                <ul>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <li>
                                <a href="CommunityPage.php?team_id=<?= urlencode($row['id']) ?>&team=<?= urlencode($row['team_name']) ?>"
                                    class="SIDEBAR__ITEM COMMUNITY__ITEM">
                                    <?= htmlspecialchars($row['team_name']) ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>No teams found</li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>


        <div class="DM__MAIN" >

            <div class="DMLIST__SIDEBAR">
                <?php

                // display recently text
                // display all person who have a convo b4
                $sql2 = "SELECT users.id, users.name, MAX(message.sent_at) as last_message_time
        FROM message 
        JOIN users ON (message.receiver_id = users.id OR message.sender_id = users.id) 
        WHERE message.sender_id = " . $_COOKIE['UID'] . " OR message.receiver_id = " . $_COOKIE['UID'] . " 
        GROUP BY users.id, users.name 
        ORDER BY last_message_time DESC";


                $result2 = $_conn->query($sql2);
                ?>

                <div>
                    <button class="SIDEBAR__CLOSE CLICKABLE">
                        <span class="material-icons">
                            close
                        </span>
                    </button>
                    <h3 style="text-align: center;">Direct Messages</h3>

                    <ul class="DM__LIST__UL">
                        <?php
                        if ($result2->num_rows > 0) {
                            while ($row = $result2->fetch_assoc()) {
                                echo "<li class='DM__LIST__PERSON'><span class='material-icons'>perm_identity</span><a href='CommunityDMPage.php?receiver_id=" . $row['id'] . "&name=" . urlencode($row['name']) . "'>" . htmlspecialchars($row['name']) . "</a></li>";
                            }
                        } else {
                            echo "<li>No messages found</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <article class="DMPage">
                <section class="DMPAGE__HEADER">
                    <button class="DM__TOGGLE CLICKABLE" id="toggleDMList">
                        <span class="material-icons" style="font-size: 2rem;">
                            list
                        </span>
                    </button>

                    <div class="DMPAGE__HEADER2">
                        <span class="material-icons PROFILE_ICON">
                            face
                        </span>
                        <?php
                        $senderID = $_COOKIE['UID'];


                        if (!isset($_GET['receiver_id'])) {
                            $sqlLastPerson = "SELECT receiver_id FROM message 
  WHERE sender_id = '$senderID' 
  ORDER BY sent_at DESC LIMIT 1";

                            $resultLastPerson = mysqli_query($_conn, $sqlLastPerson);
                            if ($rowLastPerson = mysqli_fetch_assoc($resultLastPerson)) {
                                $receiverID = $rowLastPerson['receiver_id'];

                                $sqlGetName = "SELECT * FROM users 
                                WHERE '$receiverID' = id";
                                $resultName = mysqli_query($_conn, $sqlGetName);
                                if ($rowName = mysqli_fetch_assoc($resultName)) {
                                    $userName = $rowName['name'];
                                }
                            } else {
                                $receiverID = null; // No messages yet
                            }
                        } else {
                            $receiverID = $_GET['receiver_id'];
                        }


                        // Get the name from URL
                        $name = isset($_GET['name']) ? $_GET['name'] : $userName;
                        ?>
                        <h1><?php echo htmlspecialchars($name); ?></h1>

                    </div>
                    <span class="material-icons PROFILE_CAM">
                        videocam
                    </span>
                </section>

                <section class="DMPAGE__CONVERSATION">
                    <?php
                    $sql3 = "SELECT * FROM message 
        WHERE (sender_id = '$senderID' AND receiver_id = '$receiverID') 
        OR (sender_id = '$receiverID' AND receiver_id = '$senderID')
        ORDER BY sent_at ASC";

                    $result3 = mysqli_query($_conn, $sql3);
                    if (!$result3) {
                        echo "<p>Error loading messages: " . mysqli_error($_conn) . "</p>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result3)) {
                            $messageText = htmlspecialchars($row['message_text']); // Prevent XSS
                            $isSent = ($row['sender_id'] == $senderID) ? "SENT" : "RECEIVE"; // Identify sender

                            echo "<div class='CONVERSATION $isSent'>$messageText</div>";
                        }
                    }


                    ?>
                </section>

                <section class="DMPAGE__MESSAGE">

                    <form action="CommunityDMPageSendMsg.php" method="POST" class="MESSAGE__BOX" id="chatForm">
                        <!-- change this dynamically -->
                        <input type="hidden" name="receiver_id" value=<?php echo $receiverID; ?>>
                        <input class="ENTER__MESSAGE" type="text" name="message" id="message1" placeholder="Type something...">
                        <button type="submit" class="SEND__MESSAGE"><span class="material-icons">
                                send
                            </span></button>
                    </form>
                </section>
            </article>
        </div>
    </main>
    <script src="Registered.js" defer></script>
    <script src="CommunityDM.js" defer></script>
</body>

</html>