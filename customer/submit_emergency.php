<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION['customer_id'];
    $name =  $_POST['sname'];
    $contact =  $_POST['contact'];
    $location =  $_POST['servicelocation'];
    $serviceType =  $_POST['service_type'];
    $description =  $_POST['description'];

    if (!empty($name) && !empty($contact) && !empty($location) && !empty($serviceType)) {
        $sql = "INSERT INTO emergency_requests 
                VALUES ('', '$id', '$name', '$contact', '$location', '$serviceType', '$description', 'Pending')";

        if (mysqli_query($conn, $sql)) {
            echo "OK";
        } else {
            echo "Data Type Error: ";
           
        }
    } else {
        echo "All fields are required!";
    }
}
?>
