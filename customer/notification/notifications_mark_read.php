<?php
include("../db.php");
session_start();

$customer_id = $_SESSION['customer_id'] ?? 0;

if ($customer_id > 0) {
    $sql = "UPDATE notifications SET status='read' WHERE customer_id=? AND status='unread'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
}
?>
