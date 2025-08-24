<?php
session_start();
include('connection.php');

$id = $_SESSION['id'];
$sname = $_POST['sname'];
$mechanic_type = $_POST['mechanic_type'];
$skills = $_POST['skills'];
$expert = $_POST['expert'];
$location = $_POST['shoplocation'];
$coverage = $_POST['coverage'];


$sql = "insert into service values('', '$id', '$sname', '$mechanic_type', '$skills', '$expert', '$location', '$coverage')";

$result = mysqli_query($con, $sql);

            if($result){
                  echo  '1';
                }else{
                  echo '0';
                }



?>