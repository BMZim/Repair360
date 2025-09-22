<?php
include("connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $mechanic_id = $_POST['mechanic_id'];
    $estimated_arrival = !empty($_POST['estimated_arrival']) ? date('Y-m-d H:i:s', strtotime($_POST['estimated_arrival'])) : null;
    $status = $_POST['status'];
    $current_status = $_POST['current_status'];

    // Check if track_status already exists for this appointment
    $check = $con->prepare("SELECT track_id FROM track_status WHERE appointment_id = ?");
    $check->bind_param("i", $appointment_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        // Update
        $sql = "UPDATE track_status 
                SET status = ?
                WHERE appointment_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $status,  $appointment_id);
    } else {
        // Insert new record
        $sql = "INSERT INTO track_status (appointment_id, mechanic_id, estimated_arrival, status) 
                VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("isss", $appointment_id, $mechanic_id, $estimated_arrival, $status);
    }

    if ($stmt->execute()) {
        echo "Done";
        exit();
    } else {
        echo "Error updating status!";
    }
}
?>
