<?php
include "conn.php";
session_start();
include "AccountVerify.php";
if (!verifyUser($_conn)) {
    header("Location: /Landing_Page/Homepage.php");
    exit();
}
$userID = $_COOKIE['UID'];

// Fetch task completion rate
$sql_completion = "SELECT 
                    COUNT(CASE WHEN status = 'Complete' THEN 1 END) as completed_tasks,
                    COUNT(*) as total_tasks
                 FROM tasks 
                 WHERE user_id = $userID";
$result_completion = $_conn->query($sql_completion);
$completion_data = $result_completion->fetch_assoc();

$completion_rate = 0;
if ($completion_data['total_tasks'] > 0) {
    $completion_rate = round(($completion_data['completed_tasks'] / $completion_data['total_tasks']) * 100);
}

// Fetch average completion time for completed tasks
$sql_avg_time = "SELECT AVG(TIMESTAMPDIFF(HOUR, start_time, end_time)) as avg_hours
                FROM tasks
                WHERE user_id = $userID AND status = 'Complete' AND start_time IS NOT NULL AND end_time IS NOT NULL";
$result_avg_time = $_conn->query($sql_avg_time);
$avg_time_data = $result_avg_time->fetch_assoc();

$avg_completion_hours = 0;
if ($avg_time_data['avg_hours'] !== null) {
    $avg_completion_hours = round($avg_time_data['avg_hours'], 1);
}

// Fetch best and worst completion times
$sql_best_time = "SELECT MIN(TIMESTAMPDIFF(HOUR, start_time, end_time)) as best_hours
                 FROM tasks
                 WHERE user_id = $userID AND status = 'Complete' AND start_time IS NOT NULL AND end_time IS NOT NULL";
$result_best_time = $_conn->query($sql_best_time);
$best_time_data = $result_best_time->fetch_assoc();

$sql_worst_time = "SELECT MAX(TIMESTAMPDIFF(HOUR, start_time, end_time)) as worst_hours
                  FROM tasks
                  WHERE user_id = $userID AND status = 'Complete' AND start_time IS NOT NULL AND end_time IS NOT NULL";
$result_worst_time = $_conn->query($sql_worst_time);
$worst_time_data = $result_worst_time->fetch_assoc();

$best_hours = $best_time_data['best_hours'] ? round($best_time_data['best_hours'], 1) : 0;
$worst_hours = $worst_time_data['worst_hours'] ? round($worst_time_data['worst_hours'], 1) : 0;

// Fetch completion rate from previous week for comparison
$prev_week_start = date('Y-m-d H:i:s', strtotime('-2 weeks'));
$prev_week_end = date('Y-m-d H:i:s', strtotime('-1 week'));

$sql_prev_completion = "SELECT 
                         COUNT(CASE WHEN status = 'Complete' THEN 1 END) as completed_tasks,
                         COUNT(*) as total_tasks
                      FROM tasks 
                      WHERE user_id = $userID 
                      AND created_at BETWEEN '$prev_week_start' AND '$prev_week_end'";
$result_prev_completion = $_conn->query($sql_prev_completion);
$prev_completion_data = $result_prev_completion->fetch_assoc();

$prev_completion_rate = 0;
if ($prev_completion_data['total_tasks'] > 0) {
    $prev_completion_rate = round(($prev_completion_data['completed_tasks'] / $prev_completion_data['total_tasks']) * 100);
}

$completion_change = $completion_rate - $prev_completion_rate;
$completion_change_class = $completion_change >= 0 ? 'positive' : 'negative';
$completion_change_arrow = $completion_change >= 0 ? '↑' : '↓';

// Fetch previous week average completion time for comparison
$sql_prev_avg_time = "SELECT AVG(TIMESTAMPDIFF(HOUR, start_time, end_time)) as avg_hours
                    FROM tasks
                    WHERE user_id = $userID 
                    AND status = 'Complete' 
                    AND start_time IS NOT NULL 
                    AND end_time IS NOT NULL
                    AND created_at BETWEEN '$prev_week_start' AND '$prev_week_end'";
$result_prev_avg_time = $_conn->query($sql_prev_avg_time);
$prev_avg_time_data = $result_prev_avg_time->fetch_assoc();

$prev_avg_completion_hours = 0;
if ($prev_avg_time_data['avg_hours'] !== null) {
    $prev_avg_completion_hours = round($prev_avg_time_data['avg_hours'], 1);
}

// Calculate time improvement (lower is better for time)
$time_change_percent = 0;
if ($prev_avg_completion_hours > 0) {
    $time_change = $prev_avg_completion_hours - $avg_completion_hours;
    $time_change_percent = round(($time_change / $prev_avg_completion_hours) * 100);
}
$time_change_class = $time_change_percent >= 0 ? 'positive' : 'negative';
$time_change_arrow = $time_change_percent >= 0 ? '↓' : '↑'; // Down arrow is positive (less time)

// Fetch task categories and their counts
$sql_categories = "SELECT category, COUNT(*) as task_count 
                  FROM tasks 
                  WHERE user_id = $userID 
                  GROUP BY category 
                  ORDER BY task_count DESC";
$result_categories = $_conn->query($sql_categories);

$categories = [];
$total_category_tasks = 0;

while ($row = $result_categories->fetch_assoc()) {
    $categories[] = $row;
    $total_category_tasks += $row['task_count'];
}

$colors = [
    '#4285F4',
    '#EA4335',
    '#FBBC05',
    '#34A853',
    '#FF6D01',
    '#46BDC6',
    '#7B1FA2',
    '#0097A7',
    '#D81B60',
    '#5D4037',
    '#8E44AD',
    '#3498DB',
    '#1ABC9C',
    '#F39C12',
    '#C0392B',
    '#27AE60',
    '#2980B9',
    '#D35400',
    '#2C3E50',
    '#E74C3C',
    '#9B59B6',
    '#16A085',
    '#F1C40F',
    '#BDC3C7',
    '#34495E',
    '#E67E22',
    '#95A5A6',
    '#7F8C8D',
    '#2ECC71',
    '#A93226'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
</head>
<style>
    :root {
        --primary-color: #4a6fa5;
        --secondary-color: #166088;
        --accent-color: #4caf50;
        --text-color: #333;
        --background-color: #f5f7fa;
        --card-bg: #ffffff;
        --border-radius: 10px;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    body {
        background-color: var(--background-color);
        color: var(--text-color);
    }

    .dashboard {
        max-width: 1200px;
        margin: 0 auto;
    }

    .dashboard-header {
        margin-bottom: 30px;
    }

    .dashboard-header h1 {
        color: var(--primary-color);
        font-size: 28px;
        margin-bottom: 10px;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background-color: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 20px;
        box-shadow: var(--shadow);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card h2 {
        font-size: 16px;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }

    .stat-value {
        font-size: 36px;
        font-weight: bold;
        color: var(--primary-color);
        margin-bottom: 10px;
    }

    .stat-change {
        display: flex;
        align-items: center;
        font-size: 14px;
    }

    .positive {
        color: var(--accent-color);
    }

    .negative {
        color: #f44336;
    }

    .chart-container {
        background-color: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 20px;
        box-shadow: var(--shadow);
    }

    .progress-bar {
        height: 10px;
        background-color: #e0e0e0;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: var(--accent-color);
        border-radius: 5px;
        transition: width 0.5s ease-in-out;
    }

    .time-stats {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
        font-size: 14px;
        color: #666;
    }

    #taskCategories {
        height: 300px;
        position: relative;
    }

    .pie-chart {
        position: relative;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        margin: 0 auto;
    }

    .pie-segment {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        clip: rect(0px, 100px, 200px, 0px);
        transform: rotate(0deg);
    }

    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .legend-color {
        width: 15px;
        height: 15px;
        border-radius: 3px;
    }
</style>

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

                    <?php
                    $userID = $_COOKIE['UID'];

                    // Check if there are any unread notifications for this user
                    $sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = $userID AND status = 'unread'";
                    $result = $_conn->query($sql);
                    $row = $result->fetch_assoc();
                    $hasUnread = $row['unread_count'] > 0; // True if there are unread notifications
                    ?>

                    <li class="HEADER__ITEM" style="position: relative; user-select: none; cursor: pointer;">
                        <div class="HEADER__UL__ICON" id="notiButton">
                            <span class="material-icons" id="notiIcon">
                                <?= $hasUnread ? 'notifications_active' : 'notifications' ?>
                            </span>
                        </div>
                        <?php
                        $userID = $_COOKIE['UID'];
                        $sql = "SELECT * FROM notifications WHERE user_id = $userID ORDER BY status ASC, created_at DESC";
                        $result = $_conn->query($sql);
                        ?>

                        <div class="NOTIFICATION__POPUP" id="notificationPopup" style="height: 300px; overflow-y: auto; cursor:default; display:none;">
                            <?php if ($result->num_rows > 0): ?>
                                <ul id="notificationList">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <?php if ($row['type'] == 'system'): ?>
                                            <li class="NOTI__ITEM <?= strtolower($row['status']) == 'unread' ? 'UNREAD' : 'READ' ?>">
                                                📢 System Notification: <?= $row['notification_message'] ?>
                                                <small> (<?= $row['created_at'] ?>)</small>
                                            </li>
                                        <?php else: ?>
                                            <li class="NOTI__ITEM <?= strtolower($row['status']) == 'unread' ? 'UNREAD' : 'READ' ?> NOTI__ITEM__MSG">
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

    <main>
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
                            <span class="material-icons">
                                track_changes
                            </span>Goals
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
                    <li>
                        <a href="CommunityPage.php" class="SIDEBAR__ITEM COMMUNITY__ITEM">
                            Channel 1
                            <button class="material-icons">
                                more_horiz
                            </button>
                        </a>
                    </li>
                </ul>
                <h4 class="NAV_TITLE">Direct Messages</h4>
                <ul class="DM_USER_LIST">
                    <li>
                        <a href="CommunityDMPage?receiver_id=3&name=Michael+Brown" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 1')">
                            Micheal Brown
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage?receiver_id=2&name=Jane+Smith" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 2')">
                            Jane Smith
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                    <li>
                        <a href="CommunityDMPage?receiver_id=4&name=Sarah+Lee" class="SIDEBAR__ITEM COMMUNITY__ITEM" onclick="openChat('Person 2')">
                            Sarah Lee
                            <button class="material-icons">more_horiz</button>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </main>

    <div class="dashboard">
        <div class="dashboard-header">
            <h1>Productivity Analytics</h1>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <h2>Task Completion Rate</h2>
                <div class="stat-value" id="completionRate"><?= $completion_rate ?>%</div>
                <div class="progress-bar">
                    <div class="progress-bar-fill" id="completionRateBar" style="width: <?= $completion_rate ?>%;"></div>
                </div>
                <div class="stat-change <?= $completion_change_class ?>">
                    <span><?= $completion_change_arrow ?> <?= abs($completion_change) ?>% from last week</span>
                </div>
            </div>

            <div class="stat-card">
                <h2>Average Completion Time</h2>
                <div class="stat-value" id="avgCompletionTime"><?= $avg_completion_hours ?>h</div>
                <div class="time-stats">
                    <span>Best: <?= $best_hours ?>h</span>
                    <span>Worst: <?= $worst_hours ?>h</span>
                </div>
                <div class="stat-change <?= $time_change_class ?>">
                    <span><?= $time_change_arrow ?> <?= abs($time_change_percent) ?>% <?= $time_change_percent >= 0 ? 'improvement' : 'increase' ?> from last week</span>
                </div>
            </div>
        </div>

        <div class="chart-container">
            <h2>Task Categories</h2>
            <div id="taskCategories">
                <div class="pie-chart" id="pieChart"></div>
                <div class="chart-legend" id="chartLegend"></div>
            </div>
        </div>
    </div>
    <script src="Registered.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryData = <?php
                                    $categoryData = [];
                                    foreach ($categories as $index => $category) {
                                        $percentage = ($total_category_tasks > 0) ? round(($category['task_count'] / $total_category_tasks) * 100) : 0;
                                        $colorIndex = $index % count($colors);
                                        $categoryData[] = [
                                            'name' => $category['category'] ? $category['category'] : 'Uncategorized',
                                            'value' => $percentage,
                                            'count' => $category['task_count'],
                                            'color' => $colors[$colorIndex]
                                        ];
                                    }
                                    echo json_encode($categoryData);
                                    ?>;

            // Create pie chart
            if (categoryData.length > 0) {
                createPieChart(categoryData);
            } else {
                document.getElementById('pieChart').innerHTML = '<p>No task data available</p>';
            }

            function createPieChart(data) {
                const pieChart = document.getElementById('pieChart');
                const chartLegend = document.getElementById('chartLegend');

                // Clear existing content
                pieChart.innerHTML = '';
                chartLegend.innerHTML = '';

                let startAngle = 0;

                // Create pie segments
                data.forEach((category, index) => {
                    const angle = (category.value / 100) * 360;
                    const endAngle = startAngle + angle;

                    // Create a pie segment
                    const segment = document.createElement('div');
                    segment.className = 'pie-segment';
                    segment.style.clip = `rect(0px, 200px, 200px, 100px)`;
                    segment.style.backgroundColor = category.color;
                    segment.style.transform = `rotate(${startAngle}deg)`;

                    if (angle > 180) {
                        const segment2 = document.createElement('div');
                        segment2.className = 'pie-segment';
                        segment2.style.clip = `rect(0px, 100px, 200px, 0px)`;
                        segment2.style.backgroundColor = category.color;
                        segment2.style.transform = `rotate(${startAngle + 180}deg)`;
                        pieChart.appendChild(segment2);
                    }

                    pieChart.appendChild(segment);

                    // Create legend item
                    const legendItem = document.createElement('div');
                    legendItem.className = 'legend-item';

                    const colorBox = document.createElement('div');
                    colorBox.className = 'legend-color';
                    colorBox.style.backgroundColor = category.color;

                    const label = document.createElement('span');
                    label.textContent = `${category.name}: ${category.value}% (${category.count})`;

                    legendItem.appendChild(colorBox);
                    legendItem.appendChild(label);
                    chartLegend.appendChild(legendItem);

                    startAngle = endAngle;
                });
            }
        });
    </script>
</body>

</html>