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
            die("<script>alert('Password details are incorrect');window.location.href='Signup.php';</script>");
        } else if (mysqli_num_rows($resultPass) >= 1) {
            die("<script>alert('Username details are incorrect');window.location.href='Signup.php';</script>");
        } else {
            die("<script>alert('$username');window.location.href='Signup.php';</script>");
        }
    }
     else {
        // If username and password match, set session variables
        if ($rows = mysqli_fetch_array($result)) {

            // Store user data in session variables
            $_SESSION['userID'] = $rows['id'];
            $_SESSION['userName'] = $rows['name'];
            $_SESSION['userEmail'] = $rows['email'];
            $_SESSION['userPassword'] = $rows['password'];
            $_SESSION['usertype'] = $rows['usertype'];


            // Redirect based on user role
            if (intval($rows['usertype']) === 0) {
                //  '/' means available across whole website
                setcookie('id',$rows['userID'], time() + 3600, '/');
                echo "<script>alert('Welcome User');window.location.href='Homepage.php';</script>";
                

            } else if (intval($rows['usertype']) === 1) {
                setcookie('userID',$rows['id'], time() + 3600, '/');
                echo "<script>alert('Welcome Admin');window.location.href='Homepage.php';</script>";
            }
        }
    }
}
?>

