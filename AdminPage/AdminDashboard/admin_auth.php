<?php
session_start();

function requireAdminAuth() {
    if (!isset($_SESSION['userID']) || !isset($_SESSION['usertype'])) {
        header("Location: /RWD_assignment/FocusFlow/RegisterLayout/Login.php");
        exit();
    }

    if ($_SESSION['usertype'] !== 1) {
        header("Location: /RWD_assignment/FocusFlow/RegisterLayout/Login.php");
        exit();
    }

    return true;
}