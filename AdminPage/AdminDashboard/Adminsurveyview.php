<?php
session_start();
include "../../RegisterLayout/conn.php";

// if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true || $_SESSION['usertype'] !== 1) {
//     header("Location: ../../RegisterLayout/Login.php");
//     exit();
// }

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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Survey Responses Dashboard</title>
    <style>

        * {
            box-sizing: border-box;
        }
        
        body, html {
            overflow-x: hidden;
        }

        .admin-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
            background-color: #f4f4f4;
            position: relative;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            background-color: #f9f9f9;
            transition: margin-left 0.3s ease;
            min-width: 0;
            overflow-y: auto;
        }
        
        .DASH__MAIN {
            display: none;
        }
        
        .survey-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .table-container {
            width: 100%;
    overflow-x: auto;
    max-width: 100%;
    margin-bottom: 20px;
    -webkit-overflow-scrolling: touch; /* Enable smooth scrolling on iOS */
        }
        
        .table-scroll-hint {
            display: block;
            text-align: center;
            font-size: 0.8em;
            color: #666;
            margin-bottom: 8px;
        }

        .survey-table {
            width: 100%;
    min-width: 800px; /* This forces horizontal scrolling on smaller screens */
    border-collapse: collapse;
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
        
        /* Menu button for mobile */
        .HEADER__MENU_BUTTON {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px;
            cursor: pointer;
            display: none;
        }

        .hamburger {
            width: 24px;
            height: 18px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .hamburger span {
            display: block;
            height: 3px;
            width: 100%;
            background: white;
            border-radius: 3px;
            transition: all 0.25s ease;
        }
        
        /* Overlay for sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        /* Scroll indicators */
        .scroll-indicator {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(44, 62, 80, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
            z-index: 100;
        }
        
        /* Responsive styles */
        @media screen and (max-width: 1024px) {
            .main-content {
                margin-left: 200px;
                padding: 15px;
            }
            
            .survey-container {
                padding: 15px;
            }
        }
        
        @media screen and (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
                max-height: none;
            }
            
            .HEADER__MENU_BUTTON {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
                z-index: 1000;
                height: 100vh;
                overflow-y: auto;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .survey-table th, .survey-table td {
                padding: 10px;
            }
            
            .table-container {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table-container .survey-table {
                min-width: 650px;
            }
            
            .survey-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .view-toggle {
                margin-top: 10px;
                width: 100%;
            }
            
            .view-btn {
                flex: 1;
                text-align: center;
            }
        }
        
        @media screen and (max-width: 480px) {
            .main-content {
                padding: 10px;
            }
            
            .survey-container {
                padding: 12px;
            }
            
            .survey-table {
                min-width: 800px;
            }
            
            .table-container {
                margin: 0 -10px;
        padding: 0 10px;
        width: calc(100% + 20px);
            }
            
            .survey-table th, .survey-table td {
                padding: 8px;
                font-size: 0.9em;
            }
            
            .suggestions-cell {
                max-width: 150px;
            }
            
            .survey-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .sidebar {
                display: none;
                width: 80%;
                max-width: 280px;
            }
            
            .sidebar.active {
                display: block;
            }
        }

        /* Add styles for the view toggle buttons */
        .view-toggle {
            display: flex;
            gap: 10px;
        }
        
        .view-btn {
            padding: 8px 16px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .view-btn.active {
            background: #2c3e50;
            color: white;
        }
        
        /* Box view styles */
        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .survey-box {
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .survey-box-header {
            background: #f5f5f5;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .survey-box-header h3 {
            margin: 0;
            font-size: 1.1em;
            color: #2c3e50;
        }
        
        .survey-box-content {
            padding: 15px;
        }
        
        .survey-box-content p {
            margin: 8px 0;
        }
        
        .suggestions {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #eee;
        }

        /* Replace view toggle buttons with toggle switch */
        .view-toggle-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: sticky;
            top: 10px;
            z-index: 10;
        }
        
        .view-toggle-switch {
            position: relative;
            width: 220px;
            height: 40px;
            background: #f5f5f5;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .toggle-input {
            display: none;
        }
        
        .toggle-label {
            display: flex;
            position: relative;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .toggle-option {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-weight: bold;
            transition: all 0.3s ease;
            z-index: 2;
        }
        
        .toggle-slider {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 50%;
            height: calc(100% - 4px);
            background: #2c3e50;
            border-radius: 18px;
            transition: all 0.3s ease;
            z-index: 1;
        }
        
        .toggle-input:checked + .toggle-label .toggle-slider {
            left: calc(50% - 2px);
        }
        
        .toggle-input:not(:checked) + .toggle-label .toggle-option.left,
        .toggle-input:checked + .toggle-label .toggle-option.right {
            color: white;
        }
        
        @media screen and (max-width: 768px) {
            .view-toggle-container {
                position: fixed;
                bottom: 20px;
                left: 0;
                right: 0;
                top: auto;
                margin-bottom: 0;
                z-index: 900;
            }
            
            .view-toggle-switch {
                margin: 0 auto;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
    <?php include "AdminSidebar.php"; ?>
    
    <button class="HEADER__MENU_BUTTON" id="menuToggle">
        <i class="material-icons">menu</i>
    </button>
    
        <div class="main-content">
            <div class="survey-container">
                <div class="survey-header">
                    <h2>Survey Responses</h2>
                </div>
                
                <!-- Centered view toggle switch -->
                <div class="view-toggle-container">
                    <div class="view-toggle-switch">
                        <input type="checkbox" id="viewToggle" class="toggle-input">
                        <label for="viewToggle" class="toggle-label">
                            <span class="toggle-option left">Table</span>
                            <span class="toggle-option right">Box</span>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                
                <div class="table-container" id="tableView">
                    <span class="table-scroll-hint">Scroll horizontally to view more <i class="material-icons" style="font-size: 0.9em; vertical-align: middle;">swipe</i></span>
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

                <!-- Add box view container -->
                <div class="box-container" id="boxView" style="display: none;">
                    <?php 
                    // Reset the result pointer
                    $result->data_seek(0);
                    while($response = $result->fetch_assoc()): ?>
                    <div class="survey-box">
                        <div class="survey-box-header">
                            <h3><?php echo htmlspecialchars($response['user_name']); ?></h3>
                        </div>
                        <div class="survey-box-content">
                            <p><strong>Professional Role:</strong> <?php echo htmlspecialchars($response['profession_role']); ?></p>
                            <p><strong>Ease of Use:</strong> <?php echo htmlspecialchars($response['ease_of_use']); ?></p>
                            <p><strong>Most Used Feature:</strong> <?php echo htmlspecialchars($response['most_used_feature']); ?></p>
                            <p><strong>Impact:</strong> <?php echo htmlspecialchars($response['impact']); ?></p>
                            <div class="suggestions">
                                <p><strong>Suggestions:</strong></p>
                                <p><?php echo htmlspecialchars($response['suggestions']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Add mobile menu toggle functionality
    document.getElementById('menuToggle').addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('active');
        
        // Close sidebar when clicking outside
        if(sidebar.classList.contains('active')) {
            document.addEventListener('click', function closeMenu(e) {
                if(!e.target.closest('.sidebar') && !e.target.closest('#menuToggle')) {
                    sidebar.classList.remove('active');
                    document.removeEventListener('click', closeMenu);
                }
            });
        }
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        const sidebar = document.querySelector('.sidebar');
        if(window.innerWidth > 768) {
            sidebar.classList.remove('active');
        }
    });

    // Add function to check if table needs scroll hint
    function checkTableOverflow() {
        const tableContainer = document.querySelector('.table-container');
        const table = document.querySelector('.survey-table');
        const scrollHint = document.querySelector('.table-scroll-hint');

        if (tableContainer && table && scrollHint) {
            if (tableContainer.scrollWidth > tableContainer.clientWidth) {
                scrollHint.style.display = 'block';
            } else {
                scrollHint.style.display = 'none';
            }
        }
    }

    // Run this when the page loads
    window.addEventListener('load', checkTableOverflow);
    window.addEventListener('resize', checkTableOverflow);
    
    // Add function to force table scroll visibility
    function forceScrollableTable() {
        const tableContainer = document.querySelector('.table-container');
        const table = document.querySelector('.survey-table');
        
        if (tableContainer && table) {
            tableContainer.style.overflowX = 'auto';
            tableContainer.style.width = '100%';
            table.style.minWidth = '800px';
            
            console.log("Table width after force: " + table.offsetWidth);
            console.log("Container width after force: " + tableContainer.offsetWidth);
        }
    }
    
    // Toggle between table and box view using the new toggle switch
    const viewToggle = document.getElementById('viewToggle');
    const tableView = document.getElementById('tableView');
    const boxView = document.getElementById('boxView');
    
    viewToggle.addEventListener('change', function() {
        if (this.checked) {
            // Show box view
            tableView.style.display = 'none';
            boxView.style.display = 'grid';
        } else {
            // Show table view
            tableView.style.display = 'block';
            boxView.style.display = 'none';
            setTimeout(checkTableOverflow, 100);
        }
    });
    
    // Run on page load with a small delay
    window.addEventListener('load', function() {
        setTimeout(forceScrollableTable, 100);
        setTimeout(checkTableOverflow, 200);
    });
    </script>
</body>
</html>