<?php
include("../db.php");

if($_SERVER['REQUEST_METHOD']=="POST"){
    $appointment_id = $_POST['appointment_id'];
    $sender_type = $_POST['sender_type']; // customer or mechanic
    $sender_id = $_POST['sender_id'];
    $message = $_POST['message'];

    $sql = "INSERT INTO chat_messages (appointment_id, sender_type, sender_id, message) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isis", $appointment_id, $sender_type, $sender_id, $message);
    $stmt->execute();
}
?>
