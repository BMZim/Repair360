<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['customer_id']; // logged in customer
    $mechanic_id = $_POST['mechanic_id'];
    $service_id = $_POST['service_id'];
    $description = $_POST['description'];
    $fee = $_POST['fee'];
    $appointment_date = date('Y-m-d', strtotime($_POST['appointment_date'])); // DATE format
    $appointment_time = date('H:i:s', strtotime($_POST['appointment_time'])); // TIME format
    $message1 = 'Appointment booked successfully!';
    $message2= "You have an appoinment!!";

    $status = "Pending"; // default

    $sql = "INSERT INTO appointments (customer_id, mechanic_id, service_id, appointment_date, appointment_time, status, description, fee)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissssi", $customer_id, $mechanic_id, $service_id, $appointment_date, $appointment_time, $status, $description, $fee);

    if ($stmt->execute()) {
        $sql1 = "INSERT INTO customer_notifications (customer_id, message) VALUES ('$customer_id', '$message1')";
        mysqli_query($conn, $sql1);
        $sql2 = "INSERT INTO mechanic_notifications (mechanic_id, message) VALUES ('$mechanic_id', '$message2')";
        mysqli_query($conn, $sql2);
        echo "<script>alert('Appointment booked successfully!'); window.location='customer-dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to book appointment. Try again!'); window.history.back();</script>";
    }
}
?>
