<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Mod.css">
    <link rel="shortcut icon" href="../img/icons8-staff-32.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Moderator Dashboard</title>
</head>

<body>
    <?php include "../ModSidebar.php"; ?>

    <header>
        <button class="HEADER__MENU_BUTTON">
            <div class="HEADER__MENU_ICON"></div>
        </button>
    </header>
    <div class="DASH__MAIN">
        <div class="DASH__USER__CARD">
            <div class="DASH__MAIN__DIV">
                <h3>Total User</h3>
                <div>
                    
                </div>
            </div>
            <div class="DASH__MAIN__DIV">Recent Login User</div>
            <div class="DASH__MAIN__DIV">Total Task</div>
            <div class="DASH__MAIN__DIV">Recent Message</div>
        </div>

        <div>
            <div class="DASH__MAIN__DIV">Total Teams</div>
        </div>

        <div>
            <div class="DASH__MAIN__DIV">Survey Response</div>
        </div>
    </div>

</body>
<script src="../Mod.js"></script>

</html>