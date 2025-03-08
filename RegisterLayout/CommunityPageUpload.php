<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
    <title>File Upload</title>
</head>

<body class="CM__POPUP">

    <h2 class="CM__POPUP__TITLE"><?php echo $_GET['team'] ?> : Upload a File</h2>
    <form class="CM__POPUP__FORM" action="CommunityBackend.php" method="POST" enctype="multipart/form-data">
        <!-- send team name to back end -->
        <input type="hidden" name="action" value="UploadFile">
        <input type="hidden" name="team_name" value="<?php echo htmlspecialchars($_GET['team']); ?>">
        <input class="CM__POPUP__INPUT" type="file" name="file" required>
        <button type="submit" name="upload" class="CM__POPUP__BUTTON">Upload</button>
    </form>

</body>

</html>