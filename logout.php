<?php
session_start();
session_destroy();
//This page responsible for logout from the account when user request this.
    setcookie('username', '', time() - 3600);
    header("Location: login.php");
    exit();

?>