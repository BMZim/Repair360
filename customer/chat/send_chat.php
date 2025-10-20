<?php
include("../db.php");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $appointment_id = intval($_POST['appointment_id']);
    $sender_type = $_POST['sender_type']; // customer or mechanic
    $sender_id = intval($_POST['sender_id']);
    $message = trim($_POST['message']);

    if (!empty($appointment_id) && !empty($message)) {
        $sql = "INSERT INTO chat_messages (appointment_id, sender_type, sender_id, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isis", $appointment_id, $sender_type, $sender_id, $message);
        if ($stmt->execute()) {
            echo "OK";
        } else {
            echo "DB Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Missing data";
    }
}
?>
