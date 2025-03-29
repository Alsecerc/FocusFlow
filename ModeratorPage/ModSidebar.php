<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="HEADER">
    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/Dashboard/ModDashboard.php"
        class="SIDEBAR__ITEM <?= $current_page == 'ModDashboard.php' ? 'active' : '' ?>"><span class="material-icons">
            home
        </span></a>

    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/MessagingMangement/MessagingManagement.php"
        class="SIDEBAR__ITEM <?= $current_page == 'MessagingManagement.php' ? 'active' : '' ?>">Messaging Management</a>

    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/TeamManagement/TeamManagement.php"
        class="SIDEBAR__ITEM <?= $current_page == 'TeamManagement.php' ? 'active' : '' ?>">Team Management</a>

    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/TaskManagement/TaskManagement.php"
        class="SIDEBAR__ITEM <?= $current_page == 'TaskManagement.php' ? 'active' : '' ?>">Task Management</a>

    <a href="/RWD_Assignment/FocusFlow/ModeratorPage/UploadedFileManagement/FileManagement.php"
        class="SIDEBAR__ITEM <?= $current_page == 'FileManagement.php' ? 'active' : '' ?>">File Management</a>
</div>