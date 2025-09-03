<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['customer_id']; // logged in customer
    $mechanic_id = $_POST['mechanic_id'];
    $service_id = $_POST['service_id'];
    $appointment_date = date('Y-m-d', strtotime($_POST['appointment_date'])); // DATE format
    $appointment_time = date('H:i:s', strtotime($_POST['appointment_time'])); // TIME format

    $status = "Pending"; // default

    $sql = "INSERT INTO appointments (customer_id, mechanic_id, service_id, appointment_date, appointment_time, status)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisss", $customer_id, $mechanic_id, $service_id, $appointment_date, $appointment_time, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!'); window.location='customer-dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to book appointment. Try again!'); window.history.back();</script>";
    }
}
?>
