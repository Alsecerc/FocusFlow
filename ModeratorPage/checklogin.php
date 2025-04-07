<?php
session_start();

if (!isset($_COOKIE['UID'])) {
    $cookiesToClear = ['AUTH_TOKEN', 'EMAIL', 'PHPSESSID', 'UID', 'USERNAME', 'USERTYPE'];
    
    foreach ($cookiesToClear as $cookieName) {
        setcookie($cookieName, '', time() - 3600, '/'); 
        unset($_COOKIE[$cookieName]); 
    }
    
    session_unset();
    session_destroy();
    header("Location: /RWD_Assignment/FocusFlow/RegisterLayout/Login.php");
    exit();
}

if (!($_COOKIE['USERTYPE'] == 2)) {
    header("Location: /RWD_Assignment/FocusFlow/RegisterLayout/Login.php");
    exit();
}

?>