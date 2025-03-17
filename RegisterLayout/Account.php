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
    <title>Account</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
</head>

<body>
    <?php
    include "header.php";
    ?>
    <main>
        <?php
        include "sidebar.php";
        ?>
        <article class="PROFILE">
            <h1 class="ARTICLE_TITLE">Account Management</h1>
            <div style="display:flex; justify-content:space-around;">
                <section class="PROFILE__SEC">
                    <div class="PROFILE__PIC__CONT">
                        <span class="material-icons" style="font-size:10rem;">
                            account_circle
                        </span>
                    </div>
                    <div>
                        <?php
                        include "conn.php";
                        $userID = $_COOKIE['UID'];

                        $name = $_COOKIE['USERNAME'];
                        $email = $_COOKIE['EMAIL'];

                        $user = [
                            "name" => $name,
                            "email" => $email,
                        ];


                        echo "<script>";
                        echo "var User = " . json_encode($user) . ";";
                        echo "</script>";
                        ?>


                        <form action="/RWD_assignment/FocusFlow/RegisterLayout/Account/AccountUpdate.php" method="POST" class="PROFILE__DETAILS" onsubmit="return verifyPassword()">

                            <div>
                                <p>Username :</p>
                                <label class="INPUT__BOX">
                                    <input type="text" name="username" class="INPUT__INPUT">
                                    <span class="INPUT__PLACEHOLDER" id="profile_name"></span>
                                </label>
                            </div>

                            <div>
                                <p>Email :</p>
                                <label class="INPUT__BOX">
                                    <input type="email" name="email" class="INPUT__INPUT">
                                    <span class="INPUT__PLACEHOLDER" id="profile_email"></span>
                                </label>
                            </div>

                            <div>
                                <p>Password</p>
                                <label class="INPUT__BOX">
                                    <input type="password" name="password" class="INPUT__INPUT" minlength="8"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}"
                                        placeholder="********"
                                        title="Must contain at least 8 characters, one uppercase, one lowercase, one number, and one special character.">
                                    <span class="INPUT__PLACEHOLDER" id="profile_password"></span>
                                </label>
                            </div>

                            <div>
                                <p>Confirm Current Password</p>
                                <label class="INPUT__BOX">
                                    <input type="password" id="current_password" name="current_password" class="INPUT__INPUT" required>
                                    <span class="INPUT__PLACEHOLDER" id="accPassword">Enter current password</span>
                                </label>
                            </div>

                            <button type="submit" class="PROFILE__SAVE">Save Changes</button>
                            <button type="reset" class="PROFILE__SAVE" onclick="resetField()">Reset</button>
                        </form>
                    </div>
                    <div class="PROFILE__LOGOUT">
                        <a class="PROFILE__LOGOUT_B" href="/RWD_assignment/FocusFlow/RegisterLayout/Account/AccountLogOutBackend.php">Log Out</a>
                    </div>
                </section>


                <div>
                    <h3 style="text-align: center; margin-top: 5rem;">Change Theme</h3>
                    <article class="ACCOUNT__THEME">
                        <button class="SETTING__BUTTON CLICKABLE" style="background-color: #3b3b3b; color: white;" onclick="changeTheme('default')">Default</button>
                        <button class="SETTING__BUTTON CLICKABLE" style="background-color: #7A3E1D; color: white;" onclick="changeTheme('theme_earth')">Earth</button>
                        <button class="SETTING__BUTTON CLICKABLE" style="background-color: #8BE9FD; color: black;" onclick="changeTheme('theme_neon')">Neon</button>
                        <button class="SETTING__BUTTON CLICKABLE" style="background-color: #52796F; color: white;" onclick="changeTheme('theme_forest')">Forest</button>
                </div>
            </div>

        </article>

    </main>


    <script src="Registered.js" defer></script>
    <script src="Account/Account.js" defer></script>
</body>

</html>