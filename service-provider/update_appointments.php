<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];

    $sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $status, $appointment_id);

    if ($stmt->execute()) {
        header("Location: mechanic-dashboard.php?msg=Job updated");
        exit();
    } else {
        echo "Error updating job.";
    }
}
?>
