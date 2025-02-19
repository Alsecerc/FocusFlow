<?php

$host = 'localhost';
$username = 'root';
$password = '';
$db = 'assignment';
    
try {
    $_conn = mysqli_connect($host,$username,$password,$db);
} catch (mysqli_sql_exception) {
    die("<script>alert('Not Connected');</script>");
};

?>