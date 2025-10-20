<?php
include("../connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = intval($_POST['appointment_id']);
    $mechanic_id = intval($_SESSION['id']);

    // Check if the work is completed
    $check = $con->prepare("SELECT status FROM track_status WHERE appointment_id = ? ORDER BY track_id DESC LIMIT 1");
    $check->bind_param("i", $appointment_id);
    $check->execute();
    $result = $check->get_result();
    $row = $result->fetch_assoc();

    if (!$row || strtolower($row['status']) !== 'completed') {
        echo "NOT_COMPLETED";
        exit;
    }

    // Delete all chat messages
    $stmt = $con->prepare("DELETE FROM chat_messages WHERE appointment_id = ?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();

    // Record the deletion
    $stmt = $con->prepare("INSERT IGNORE INTO deleted_chats (appointment_id, mechanic_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $appointment_id, $mechanic_id);
    $stmt->execute();

    echo "OK";
}
?>
