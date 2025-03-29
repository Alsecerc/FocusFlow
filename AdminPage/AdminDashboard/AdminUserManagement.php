<?php
include "../../RegisterLayout/conn.php";
date_default_timezone_set('Asia/Kuala_Lumpur');

// Pagination settings
$users_per_page = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $users_per_page;

// Update the total users count query to only count users
$total_sql = "SELECT COUNT(*) as count FROM users WHERE usertype = 0";
$total_result = $_conn->query($total_sql);
$total_users = $total_result->fetch_assoc()['count'];
$total_pages = ceil($total_users / $users_per_page);

// Update recent users query to only fetch users
$recent_sql = "SELECT id, name, email, usertype, created_at, UserStatus, suspension_end 
               FROM users 
               WHERE usertype = 0 
               ORDER BY created_at DESC LIMIT 6";
$recent_result = $_conn->query($recent_sql);
$recentUsers = $recent_result->fetch_all(MYSQLI_ASSOC);

// Update paginated users query to only fetch users
$sql = "SELECT id, name, email, usertype, created_at, UserStatus, suspension_end 
        FROM users
        WHERE usertype = 0
        ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $_conn->prepare($sql);
$stmt->bind_param('ii', $users_per_page, $offset);
$stmt->execute();
$paginatedUsers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Admin.css">
    <link rel="shortcut icon" href="../../img/icons8-staff-32.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>UserManagement</title>
    <style>
        .user-management {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .user-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .user-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            transition: transform 0.2s;
        }

        .user-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .user-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-edit { background: #ffd700; }
        .btn-delete { background: #ff4444; color: white; }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            padding: 20px 0;
            flex-wrap: wrap;
        }

        .pagination .btn {
            padding: 8px 16px;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .pagination .btn:hover {
            background: #e9ecef;
        }

        .pagination .btn.active {
            background: #4CAF50;
            color: white;
        }

        .status {
            font-weight: bold;
            margin: 5px 0;
        }

        .status.active {
            color: #2ecc71;
        }

        .status.suspended {
            color: #e74c3c;
        }

        .btn-suspend {
            background: #e74c3c;
            color: white;
        }

        .btn-unsuspend {
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
            overflow-y: auto;
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
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

        /* Responsive styles */
        @media screen and (max-width: 1024px) {
            .user-management {
                margin-left: 200px;
            }
            
            .user-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
        
        @media screen and (max-width: 768px) {
            .user-management {
                margin-left: 0;
                padding: 15px;
            }
            
            .user-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 15px;
            }
            
            .HEADER__MENU_BUTTON {
                display: block;
            }
            
            .user-section {
                padding: 15px;
            }
            
            .modal-content {
                width: 90%;
                margin: 10% auto;
            }
            
            /* Sidebar styles for mobile */
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
                z-index: 1000;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
        }
        
        @media screen and (max-width: 480px) {
            .user-management {
                padding: 10px;
            }
            
            .user-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .user-card {
                padding: 12px;
            }
            
            .user-section {
                padding: 12px;
            }
            
            .pagination {
                gap: 5px;
            }
            
            .pagination .btn {
                padding: 6px 10px;
                font-size: 0.9em;
            }
            
            .modal-content {
                width: 95%;
                margin: 5% auto;
                padding: 15px;
            }
            
            .btn {
                padding: 8px 10px;
                font-size: 0.9em;
            }
            
            .close {
                right: 10px;
                top: 5px;
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
    </style>
</head>
<body>
    <?php include "AdminSidebar.php"; ?>
    
    <button class="HEADER__MENU_BUTTON" id="menuToggle">
        <i class="material-icons">menu</i>
    </button>

    <div class="user-management">
        <!-- Recent Registrations -->
        <div class="user-section">
            <h2>Recently Registered Users</h2>
            <div class="user-grid">
                <?php foreach($recentUsers as $user): ?>
                    <div class="user-card" data-name="<?= strtolower(htmlspecialchars($user['name'])) ?>">
                        <h3><?= htmlspecialchars($user['name']) ?></h3>
                        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                        <p>Role: <?= $user['usertype'] == 1 ? 'Admin' : 'User' ?></p>
                        <p>Joined: <?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                        <p class="status <?= $user['UserStatus'] === 'Suspended' ? 'suspended' : 'active' ?>">
                            Status: <?php 
                            if ($user['UserStatus'] === 'Suspended') {
                                echo 'Suspended until: ' . date('H:i:s', strtotime($user['suspension_end']));
                            } else {
                                echo $user['UserStatus'];
                            }
                            ?>
                        </p>
                        <div class="user-actions">
                            <?php if ($user['UserStatus'] !== 'Suspended'): ?>
                                <button class="btn btn-suspend" onclick="openSuspendModal(<?= $user['id'] ?>)">Suspend</button>
                            <?php else: ?>
                                <button class="btn btn-unsuspend" onclick="unsuspendUser(<?= $user['id'] ?>)">Unsuspend</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- All Users Management -->
        <div class="user-section">
            <h2>All Users</h2>
            <input type="text" id="userSearch" placeholder="Search users..." onkeyup="searchUsers()"
                style="width: 100%; padding: 8px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #ddd;">
            
            <div class="user-grid" id="allUsers">
                <?php foreach($paginatedUsers as $user): ?>
                    <div class="user-card" data-name="<?= strtolower(htmlspecialchars($user['name'])) ?>">
                        <h3><?= htmlspecialchars($user['name']) ?></h3>
                        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                        <p>Role: <?= $user['usertype'] == 1 ? 'Admin' : 'User' ?></p>
                        <p>Joined: <?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                        <p class="status <?= $user['UserStatus'] === 'Suspended' ? 'suspended' : 'active' ?>">
                            Status: <?php 
                            if ($user['UserStatus'] === 'Suspended') {
                                echo 'Suspended until: ' . date('H:i:s', strtotime($user['suspension_end']));
                            } else {
                                echo $user['UserStatus'];
                            }
                            ?>
                        </p>
                        <div class="user-actions">
                            <?php if ($user['UserStatus'] !== 'Suspended'): ?>
                                <button class="btn btn-suspend" onclick="openSuspendModal(<?= $user['id'] ?>)">Suspend</button>
                            <?php else: ?>
                                <button class="btn btn-unsuspend" onclick="unsuspendUser(<?= $user['id'] ?>)">Unsuspend</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <button onclick="changePage(<?= $i ?>)" 
                            class="btn <?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </button>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Add this modal HTML before the closing body tag -->
    <div id="suspendModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Suspend User</h2>
            <form id="suspendForm">
                <input type="hidden" id="suspendUserId" name="userId">
                <div class="form-group">
                    <label>Suspension Duration:</label>
                    <select name="duration" id="suspendDuration" required>
                        <option value="5">5 minutes</option>
                        <option value="15">15 minutes</option>
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                        <option value="1440">24 hours</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Reason (optional):</label>
                    <textarea name="reason" id="suspendReason"></textarea>
                </div>
                <button type="submit" class="btn btn-suspend">Confirm Suspension</button>
            </form>
        </div>
    </div>

    <!-- Add this confirmation modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <h2>Confirm Action</h2>
            <p>Are you sure you want to remove this user's suspension?</p>
            <div class="modal-actions">
                <button class="btn btn-confirm">Confirm</button>
                <button class="btn btn-cancel">Cancel</button>
            </div>
        </div>
    </div>

    <script>
    function searchUsers() {
        const input = document.getElementById('userSearch').value.toLowerCase();
        const cards = document.querySelectorAll('#allUsers .user-card');
        let visibleCount = 0;
        
        cards.forEach(card => {
            const name = card.dataset.name;
            const shouldShow = name.includes(input);
            card.style.display = shouldShow ? '' : 'none';
            if (shouldShow) visibleCount++;
        });

        // Hide pagination if searching
        document.querySelector('.pagination').style.display = input ? 'none' : 'flex';
    }

    function unsuspendUser(id) {
        const confirmModal = document.getElementById('confirmationModal');
        const confirmBtn = confirmModal.querySelector('.btn-confirm');
        const cancelBtn = confirmModal.querySelector('.btn-cancel');

        confirmModal.style.display = 'block';

        const handleConfirm = () => {
            fetch('user_actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=unsuspend&id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert('Error removing suspension');
                }
            });
            cleanup();
        };

        const handleCancel = () => {
            confirmModal.style.display = 'none';
            cleanup();
        };

        const cleanup = () => {
            confirmBtn.removeEventListener('click', handleConfirm);
            cancelBtn.removeEventListener('click', handleCancel);
            window.removeEventListener('click', handleWindowClick);
        };

        const handleWindowClick = (event) => {
            if (event.target === confirmModal) {
                handleCancel();
            }
        };

        confirmBtn.addEventListener('click', handleConfirm);
        cancelBtn.addEventListener('click', handleCancel);
        window.addEventListener('click', handleWindowClick);
    }

    // Add auto-update for suspension status
    setInterval(function() {
        fetch('check_suspension.php')
            .then(response => response.json())
            .then(data => {
                if(data.updated) location.reload();
            });
    }, 30000); // Check every 30 seconds

    function changePage(pageNum) {
        // Store current scroll position
        const scrollPos = window.scrollY;
        
        // Update URL without refreshing
        const url = new URL(window.location);
        url.searchParams.set('page', pageNum);
        window.history.pushState({}, '', url);

        // Fetch new page content
        fetch(`?page=${pageNum}`)
            .then(response => response.text())
            .then(html => {
                // Extract only the users grid content
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newUsers = doc.querySelector('#allUsers').innerHTML;
                
                // Update only the users grid
                document.querySelector('#allUsers').innerHTML = newUsers;
                
                // Update active state of pagination buttons
                document.querySelectorAll('.pagination .btn').forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.textContent.trim() === pageNum.toString()) {
                        btn.classList.add('active');
                    }
                });

                // Restore scroll position
                window.scrollTo(0, scrollPos);
            });
    }

    // Modal functionality
    const modal = document.getElementById("suspendModal");
    const span = document.getElementsByClassName("close")[0];

    function openSuspendModal(userId) {
        document.getElementById("suspendUserId").value = userId;
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

    document.getElementById("suspendForm").onsubmit = function(event) {
        event.preventDefault();
        const userId = document.getElementById("suspendUserId").value;
        const duration = document.getElementById("suspendDuration").value;
        const reason = document.getElementById("suspendReason").value;

        fetch('user_actions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=suspend&id=${userId}&duration=${duration}&reason=${encodeURIComponent(reason)}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert('Error suspending user');
            }
        });
    }

    // Function to update user statuses
    function updateUserStatuses() {
        fetch('get_user_statuses.php')
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('.user-card').forEach(card => {
                    const userId = card.querySelector('.user-actions button').getAttribute('onclick').match(/\d+/)[0];
                    const user = data.find(u => u.id === userId);
                    
                    if (user) {
                        const statusElement = card.querySelector('.status');
                        statusElement.className = `status ${user.UserStatus.toLowerCase()}`;
                        
                        // Update status text
                        if (user.UserStatus === 'Suspended' && user.suspension_end) {
                            statusElement.innerHTML = `Status: Suspended until ${new Date(user.suspension_end).toLocaleTimeString()}`;
                        } else {
                            statusElement.innerHTML = `Status: ${user.UserStatus}`;
                        }
                        
                        // Update suspend/unsuspend button
                        const actionButton = card.querySelector('.user-actions button');
                        if (user.UserStatus === 'Suspended') {
                            actionButton.className = 'btn btn-unsuspend';
                            actionButton.textContent = 'Unsuspend';
                            actionButton.onclick = () => unsuspendUser(userId);
                        } else {
                            actionButton.className = 'btn btn-suspend';
                            actionButton.textContent = 'Suspend';
                            actionButton.onclick = () => openSuspendModal(userId);
                        }
                    }
                });
            });
    }

    // Update every 30 seconds
    setInterval(updateUserStatuses, 30000);

    // Initial update
    updateUserStatuses();

    // Add mobile sidebar toggle functionality
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