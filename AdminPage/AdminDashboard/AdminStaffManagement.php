<?php
include "../../RegisterLayout/conn.php";

// Fetch all moderators
$sql = "SELECT id, name, email, UserStatus, last_login FROM users WHERE usertype = 1";
$result = $_conn->query($sql);

// Count active moderators
$activeModsQuery = "SELECT COUNT(*) as active_count FROM users WHERE (usertype = 1 OR usertype = 2) AND UserStatus = 'Active'";
$activeModsResult = $_conn->query($activeModsQuery);
$activeMods = $activeModsResult->fetch_assoc()['active_count'];

// Handle moderator updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_mod'])) {
    $mod_id = $_POST['mod_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    
    $updateSql = "UPDATE users SET name = ?, email = ? WHERE id = ? AND usertype = 1";
    $stmt = $_conn->prepare($updateSql);
    $stmt->bind_param("ssi", $name, $email, $mod_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Moderator updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating moderator.";
    }
    header("Location: AdminStaffManagement.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Admin Dashboard</title>
    <link rel="stylesheet" href="Admin.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .staff-container {
            padding: 20px;
        }

        .stats-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .active-mods {
            font-size: 24px;
            color: #2ecc71;
            font-weight: bold;
        }

        .mods-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .mods-table th, .mods-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .mods-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .status-active {
            color: #2ecc71;
            font-weight: bold;
        }

        .status-inactive {
            color: #e74c3c;
            font-weight: bold;
        }

        .edit-form {
            display: none;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-edit {
            background: #3498db;
            color: white;
        }

        .btn-save {
            background: #2ecc71;
            color: white;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
        /* Responsive styles */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        
        /* Menu button for mobile */
        .HEADER__MENU_BUTTON {
            display: none;
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
        }
        
        @media screen and (max-width: 1024px) {
            .main-content {
                margin-left: 200px;
            }
            
            .stats-card {
                padding: 15px;
            }
            
            .mods-table th, .mods-table td {
                padding: 10px;
            }
        }
        
        @media screen and (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .HEADER__MENU_BUTTON {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
                z-index: 1000;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .modal-content {
                width: 90%;
                margin: 10% auto;
            }
            
            /* Improve table container for horizontal scrolling */
            .table-container {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch; /* For smoother scrolling on iOS */
            }
            
            /* Make sure the inner table takes the full width */
            .table-container .mods-table {
                min-width: 650px; /* Ensure minimum width to prevent squishing */
            }
        }
        
        @media screen and (max-width: 480px) {
            .staff-container {
                padding: 10px;
            }
            
            .stats-card {
                padding: 12px;
            }
            
            /* Enhanced table styling for small screens */
            .table-container {
                margin: 0 -10px; /* Negative margin to allow full-width scrolling */
                padding: 0 10px;
                width: calc(100% + 20px);
            }
            
            .mods-table th, .mods-table td {
                padding: 8px;
                font-size: 0.9em;
            }
            
            /* Add visual indicator for scrollable table */
            .table-scroll-hint {
                display: block;
                text-align: center;
                font-size: 0.8em;
                color: #666;
                margin-bottom: 8px;
            }
            
            .btn {
                padding: 6px 10px;
                font-size: 0.9em;
            }
            
            .modal-content {
                width: 95%;
                margin: 5% auto;
                padding: 15px;
            }
            
            /* Hide sidebar by default on small screens */
            .sidebar {
                display: none;
                width: 80%;
            }
            
            .sidebar.active {
                display: block;
            }
        }
        
        /* Add styles for very small screens */
        @media screen and (max-width: 320px) {
            .stats-card {
                padding: 8px;
            }
            
            h1 {
                font-size: 1.5em;
            }
            
            .table-container .mods-table {
                min-width: 500px; /* Smaller minimum width */
            }
            
            .modal-content {
                width: 100%;
                margin: 2% auto;
                padding: 10px;
            }
            
            .form-group label {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <?php include "AdminSidebar.php"; ?>
    
    <button class="HEADER__MENU_BUTTON" id="menuToggle">
        <i class="material-icons">menu</i>
    </button>

    <div class="main-content">
        <div class="staff-container">
            <h1>Staff Management</h1>

            <div class="stats-card">
                <h3>Active Moderators</h3>
                <div class="active-mods" id="activeMods"><?php echo $activeMods; ?></div>
            </div>

            <!-- Add table container and scroll hint -->
            <div class="table-container">
                <span class="table-scroll-hint">Scroll horizontally to view more <i class="material-icons" style="font-size: 0.9em; vertical-align: middle;">swipe</i></span>
                <table class="mods-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Last Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($mod = $result->fetch_assoc()): ?>
                        <tr id="mod-row-<?php echo $mod['id']; ?>">
                            <td><?php echo htmlspecialchars($mod['name']); ?></td>
                            <td><?php echo htmlspecialchars($mod['email']); ?></td>
                            <td class="<?php echo $mod['UserStatus'] === 'Active' ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $mod['UserStatus']; ?>
                            </td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($mod['last_login'])); ?></td>
                            <td>
                                <button class="btn btn-edit" onclick="showEditModal(<?php 
                                    echo $mod['id']; ?>, 
                                    '<?php echo htmlspecialchars($mod['name'], ENT_QUOTES); ?>', 
                                    '<?php echo htmlspecialchars($mod['email'], ENT_QUOTES); ?>'
                                )">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <form class="edit-form" id="edit-form-<?php echo $mod['id']; ?>" method="POST">
                                    <input type="hidden" name="update_mod" value="1">
                                    <input type="hidden" name="mod_id" value="<?php echo $mod['id']; ?>">
                                    <div class="form-group">
                                        <label>Name:</label>
                                        <input type="text" name="name" value="<?php echo htmlspecialchars($mod['name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" name="email" value="<?php echo htmlspecialchars($mod['email']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-save">Save Changes</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add this modal HTML -->
            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Moderator</h2>
                    <form id="editModalForm" method="POST">
                        <input type="hidden" name="update_mod" value="1">
                        <input type="hidden" name="mod_id" id="modal_mod_id">
                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" name="name" id="modal_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" id="modal_email" required>
                        </div>
                        <button type="submit" class="btn btn-save">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    const modal = document.getElementById("editModal");
    const span = document.getElementsByClassName("close")[0];
    const modalForm = document.getElementById("editModalForm");
    const modalModId = document.getElementById("modal_mod_id");
    const modalName = document.getElementById("modal_name");
    const modalEmail = document.getElementById("modal_email");

    function showEditModal(id, name, email) {
        modalModId.value = id;
        modalName.value = name;
        modalEmail.value = email;
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    setInterval(function() {
        fetch('update_active_mods.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('activeMods').textContent = data.active_count;
            });
    }, 30000);

    // Add function to check if table needs scroll hint
    function checkTableOverflow() {
        const tableContainer = document.querySelector('.table-container');
        const table = document.querySelector('.mods-table');
        const scrollHint = document.querySelector('.table-scroll-hint');
        
        if (tableContainer && table && scrollHint) {
            if (table.offsetWidth > tableContainer.offsetWidth) {
                scrollHint.style.display = 'block';
            } else {
                scrollHint.style.display = 'none';
            }
        }
    }
    
    // Check on load and resize
    window.addEventListener('load', checkTableOverflow);
    window.addEventListener('resize', checkTableOverflow);

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
    </script>
    <script src="Admin.js"></script>
</body>
</html>