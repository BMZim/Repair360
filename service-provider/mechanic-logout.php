<?php 
    session_start();

    session_unset();
    header("location:mechanic_login.php");

?>