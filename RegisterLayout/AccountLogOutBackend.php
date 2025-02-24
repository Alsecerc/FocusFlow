<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

setcookie('UID', '', time() - 86400, '/');
setcookie("USERNAME", '', time() + 86400, '/');
setcookie("EMAIL", '', time() - 86400, '/');
setcookie("PASSWORD", '', time() - 86400, '/');
setcookie("USERTYPE", '', time() - 86400, '/');
// Redirect to login or homepage
header("Location: Login.php"); 
exit();
?>
