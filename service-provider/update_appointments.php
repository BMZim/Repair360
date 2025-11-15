<?php
session_start();
include("connection.php");

 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];
    $customer_id = $_POST['customer_id'];
    $mechanic_id = $_SESSION['id'];
    $message1 = "Service Provider confirmed your service request!!";
    $message2 = "Service Provider cancelled your service request!!";
    $message3 = "You have confirmed a service request, check details on My Schedule";
    $message4 = "You cancelled a service request!!";

    $sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $status, $appointment_id);

    if ($stmt->execute()) {
        if(isset($_POST['accept'])){
            $sql1 = "INSERT INTO customer_notifications (customer_id, message) VALUES ('$customer_id', '$message1')";
            mysqli_query($con, $sql1);
            $sql2 = "INSERT INTO mechanic_notifications (mechanic_id, message) VALUES ('$mechanic_id', '$message3')";
            mysqli_query($con, $sql2);
        }else{
            $sql3 = "INSERT INTO customer_notifications (customer_id, message) VALUES ('$customer_id', '$message2')";
            mysqli_query($con, $sql3);
            $sql4 = "INSERT INTO mechanic_notifications (mechanic_id, message) VALUES ('$mechanic_id', '$message4')";
            mysqli_query($con, $sql4);
        }
    
        header("Location: mechanic-dashboard.php?msg=Job updated");
        exit();
    } else {
        echo "Error updating job.";
    }
}
?>
