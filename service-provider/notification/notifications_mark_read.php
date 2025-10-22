<?php
include("../connection.php");
session_start();

$mechanic_id = $_SESSION['id'] ?? 0;

if ($mechanic_id > 0) {
    $sql = "UPDATE mechanic_notifications SET status='read' WHERE mechanic_id=? AND status='unread'";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $mechanic_id);
    $stmt->execute();
}
?>
