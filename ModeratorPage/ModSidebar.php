<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="HEADER">
    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/Dashboard/ModDashboard.php"
        class="SIDEBAR__ITEM <?= $current_page == 'ModDashboard.php' ? 'active' : '' ?>">
        <span class="material-icons">home</span>
        <span class="SIDEBAR__TEXT">Dashboard</span>
    </a>

    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/MessagingMangement/MessagingManagement.php"
        class="SIDEBAR__ITEM <?= $current_page == 'MessagingManagement.php' ? 'active' : '' ?>">
        <span class="material-icons">chat</span>
        <span class="SIDEBAR__TEXT">Messaging Management</span>
    </a>

    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/TeamManagement/TeamManagement.php"
        class="SIDEBAR__ITEM <?= $current_page == 'TeamManagement.php' ? 'active' : '' ?>">
        <span class="material-icons">groups</span>
        <span class="SIDEBAR__TEXT">Team Management</span>
    </a>

    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/TaskManagement/TaskManagement.php"
        class="SIDEBAR__ITEM <?= $current_page == 'TaskManagement.php' ? 'active' : '' ?>">
        <span class="material-icons">task</span>
        <span class="SIDEBAR__TEXT">Task Management</span>
    </a>

    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/UploadedFileManagement/FileManagement.php"
        class="SIDEBAR__ITEM <?= $current_page == 'FileManagement.php' ? 'active' : '' ?>">
        <span class="material-icons">folder</span>
        <span class="SIDEBAR__TEXT">File Management</span>
    </a>

    <button class="logout-btn" onclick="logoutUser()">Logout</button>
    <script>
        function logoutUser() {
            fetch("/RWD_Assignment/FocusFlow/ModeratorPage/Modlogout.php", {
                    method: "POST"
                })
                .then(response => response.json()) // Ensure response is parsed as JSON
                .then(data => {
                    if (data.success) {
                        window.location.href = "/RWD_Assignment/FocusFlow/Landing_Page/Homepage.php"; // Redirect after successful logout
                    } else {
                        alert("Logout failed. Please try again.");
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    </script>
</div>