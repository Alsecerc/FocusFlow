<?php
session_start();

include 'conn.php';

$username1 = $_POST['username'] ?? "";
$password1 = $_POST['password'] ?? "";
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($testName) && isset($testPass)) {

    // Get the form data securely using mysqli_real_escape_string
    $username = mysqli_real_escape_string($_conn, $username1);
    $password = mysqli_real_escape_string($_conn, $password1);

    // SQL query to check for matching username and password
    $sql = "SELECT * FROM users WHERE name='" . $username . "' AND password='" . $password . "' ";
    $result = mysqli_query($_conn, $sql);


    // Check if the query returned any results
    if (mysqli_num_rows($result) <= 0) {
        // If no results, check if the username or password is incorrect
        $sqlName = "SELECT * FROM users WHERE name ='" . $username . "'";
        $sqlPass = "SELECT * FROM users WHERE password ='" . $password . "' ";
        $resultName = mysqli_query($_conn, $sqlName);
        $resultPass = mysqli_query($_conn, $sqlPass);

        if (mysqli_num_rows($resultName) >= 1) {
            die("<script>alert('Password details are incorrect');window.location.href='Login.php';</script>");
        } else if (mysqli_num_rows($resultPass) >= 1) {
            die("<script>alert('Username details are incorrect');window.location.href='Login.php';</script>");
        } else {
            die("<script>alert('Both details are incorrect');window.location.href='Login.php';</script>");
        }
    } else {
        // If username and password match, set session variables
        if ($rows = mysqli_fetch_array($result)) {

            // Store user data in session variables
            $_SESSION['userID'] = $rows['id'];
            $_SESSION['userName'] = $rows['name'];
            $_SESSION['userEmail'] = $rows['email'];
            $_SESSION['userPassword'] = $rows['password'];
            $_SESSION['usertype'] = $rows['usertype'];

            $username = $_SESSION['userName'];
            //  '/' means available across whole website
            // set for 1 day
            setcookie("UID", $rows['id'], time() + 86400, '/');
            setcookie("USERNAME", $rows['name'], time() + 86400, '/');
            setcookie("EMAIL", $rows['email'], time() + 86400, '/');
            setcookie("PASSWORD", $rows['password'], time() + 86400, '/');
            setcookie("USERTYPE", $rows['usertype'], time() + 86400, '/');

            if (isset($_COOKIE["UID"])){
                echo "<script>alert('Welcome back, $username!');</script>";

            }else{
                echo "<script>window.location.href='Homepage.php';</script>";

            }
        }
    }
}