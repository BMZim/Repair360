<?php
include("config.php");

if ($_POST['action'] === 'cancel') {
  $id = intval($_POST['id']);
  $stmt = $conn->prepare("UPDATE appointments SET status='Cancelled' WHERE appointment_id=?");
  $stmt->bind_param("i", $id);
  echo $stmt->execute() ? "OK" : "Error updating booking!";
}
?>
