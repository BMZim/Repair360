<?php
include("../connection.php");
session_start();
$mechanic_id = $_SESSION['id'] ?? 0;

$sql = "SELECT COUNT(*) AS total FROM mechanic_notifications WHERE mechanic_id=? AND status='unread'";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $mechanic_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo $result['total'] ?? 0;
?>
