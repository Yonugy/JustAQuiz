<?php
include("../../main.php");
$id = user_profile($conn, "user_id");
$status=delete_user($id,$conn);
if ($status){
    echo '<script>alert("Successfully Deleted Account!"); window.location.href="../User/Login.php";</script>';
}
?>