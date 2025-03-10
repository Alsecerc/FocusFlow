<!-- change to .php -->
<?php
include "conn.php";
session_start();

include "AccountVerify.php";
requireAuthentication($_conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
    <link rel="stylesheet" href="../css/task-status.css">
    <link rel="stylesheet" href="../css/drag-helpers.css">
    <link rel="stylesheet" href="../css/todo-forms.css">
    <link rel="stylesheet" href="status-fix.css">
</head>

<body>
    <div class="Hiddenlayer" style="display: none;">
    </div>
    <?php
    include "header.php";
    ?>

    <main>
        <?php
        include "sidebar.php";
        ?>

        <!-- TODO LIST CONTENT -->
        <article class="TODO">
            <section class="TODO__HEADER">
                <h1>Task Management</h1>
                <div class="TODO__BUTTON">
                    <button class="TODO__ADD">Group<span class="material-icons">
                            add_circle
                        </span>
                    </button>

                    <button class="TODO__ADD">Task<span class="material-icons">
                            add_circle
                        </span>
                    </button>
                </div>
            </section>

            <section class="TODO__CONTAINER">
            </section>
        </article>

    </main>

    <!-- FIX: Remove duplicate group form - Keep only one version -->
    <div class="TODO__GROUP__ADD" style="display: none;">
        <h2>Add New Group</h2>
        <button id="closeGroupAdd">&times;</button>
        <form id="groupForm" method="post">
            <input type="text" id="groupName" name="groupName" placeholder="Enter group name" required>
            <button type="submit">Create Group</button>
        </form>
    </div>

    <div class="Hiddenlayer" style="display: none;"></div>
    <script src="Registered.js" defer></script>
    <script src="Todo/Todo.js" defer></script>
    <script src="button-fix.js" defer></script>
    <script src="drag-fix.js" defer></script>
</body>

</html>