<?php
// send_chat_mechanic.php
session_start();
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!isset($_SESSION['id'])) { echo "Not logged in"; exit; }

$mechanic_id = intval($_SESSION['id']);
$appointment_id = isset($_POST['appointment_id']) ? intval($_POST['appointment_id']) : 0;
$message = trim($_POST['message'] ?? '');

if ($appointment_id <= 0 || $message === '') { echo "Invalid input"; exit; }

// Insert message as sender_type = 'mechanic'
$sql = "INSERT INTO chat_messages (appointment_id, sender_type, sender_id, message) VALUES (?, 'mechanic', ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("iis", $appointment_id, $mechanic_id, $message);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "ERR";
}
$stmt->close();
