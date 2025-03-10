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
                <a href="Communication.php" class="SIDEBAR__ITEM">
                    <span class="material-icons">chat</span>Communication
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