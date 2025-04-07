<!-- '/RWD_Assignment/FocusFlow/AdminPage/AdminDashboard/AdminDashboard.php' -->
<!-- '/RWD_Assignment/FocusFlow/ModeratorPage/Dashboard/ModDashboard.php' -->

<?php

// if (isset($_COOKIE['UID']) && isset($_COOKIE['USERNAME']) && isset($_COOKIE['USERTYPE'])) {
//     $username = htmlspecialchars($_COOKIE['USERNAME'], ENT_QUOTES, 'UTF-8');
//     $usertype = $_COOKIE['USERTYPE'];
//     $currentPage = basename($_SERVER['PHP_SELF']); // Get current file name

//     switch ($usertype) {
//         case 0:
//             echo "<script>console.log('homepage2')</script>";

//             $redirectPage = 'Homepage.php';
//             break;
//         case 1:
//             $redirectPage = '/RWD_Assignment/FocusFlow/AdminPage/AdminDashboard/AdminDashboard.php';
//             break;
//         case 2:
//             $redirectPage = '/RWD_Assignment/FocusFlow/ModeratorPage/Dashboard/ModDashboard.php';
//             break;
//     }

//     // ✅ Prevent redirect if already on the right page
//     if ($currentPage !== $redirectPage) {
//         echo "<script>
//             alert('Welcome back, $username');
//             window.location.href='$redirectPage';
//         </script>";
//         exit(); // Always stop execution after redirect
//     }
// }
