<?php
    include("../main.php");
    logout_user();
    echo '<script>alert("Successfully Logged Out!"); window.location.href="User/Login.php";</script>';
?>