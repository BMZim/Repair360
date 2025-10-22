<?php
include("../db.php");
session_start();
$customer_id = $_SESSION['customer_id'] ?? 0;

$sql = "SELECT COUNT(*) AS total FROM customer_notifications WHERE customer_id=? AND status='unread'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo $result['total'] ?? 0;
?>
