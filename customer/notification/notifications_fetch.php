<?php
include("../db.php");
session_start();

$customer_id = $_SESSION['customer_id'] ?? 0;

// Example table: notifications (id, customer_id, message, created_at, status)
$sql = "SELECT message, created_at FROM notifications 
        WHERE customer_id = ? ORDER BY id DESC LIMIT 10";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<div style='padding:8px 10px; border-bottom:1px solid #f1f1f1;'>
            <div style='font-size:14px; color:#333;'>".htmlspecialchars($row['message'])."</div>
            <div style='font-size:11px; color:#777;'>".date('M d, h:i A', strtotime($row['created_at']))."</div>
          </div>";
  }
} else {
  echo "<p style='text-align:center; color:#777;'>No notifications yet.</p>";
}
?>
