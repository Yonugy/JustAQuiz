<?php
    // session_start();
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login page if session is not set
        echo "<script>alert('Please login!');window.location.href='../User/Login.php';</script>";
        exit();
    }
?>