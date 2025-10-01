<?php
session_start();
include("connection.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $request_id = $_POST['request_id'];
    $customer_id = $_POST['customer_id'];
    $mechanic_id = $_SESSION['id'];
    $service_type = $_POST['service_type'];
    $description = $_POST['description'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];

    $update = "UPDATE emergency_requests SET status='Confirmed' WHERE id='$request_id'";
    if(mysqli_query($con, $update)){
        
        $insert = "INSERT INTO emergency_appointments 
                   (emergency_request_id, customer_id, mechanic_id, service_type, description, date, time, status)
                   VALUES ('$request_id', '$customer_id', '$mechanic_id', '$service_type', '$description', '$date', '$time', 'Confirmed')";
        
        if(mysqli_query($con, $insert)){
            echo "OK";
        } else {
            echo "Insert Error: ".mysqli_error($con);
        }
    } else {
        echo "Update Error: ".mysqli_error($con);
    }
}
?>
