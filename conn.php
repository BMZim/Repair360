<?php
$host = "localhost";  
$user = "root";       
$pass = "";           
$db   = "repair360";  

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
