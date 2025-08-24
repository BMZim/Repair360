<?php 
    session_start();

    session_unset();
    header("location:customer-login.php");

?>