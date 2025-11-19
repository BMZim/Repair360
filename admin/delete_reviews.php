<?php
include("config.php");

$id   = intval($_POST['id'] ?? 0);
$type = $_POST['type'] ?? '';

if (!$id || !$type) {
    echo "Invalid Request";
    exit;
}

$table = ($type === "customer") ? "customer_rating" : "mechanic_rating";

$sql = "DELETE FROM $table WHERE rating_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

echo ($stmt->execute()) ? "OK" : "DB Error";

$stmt->close();
$conn->close();
?>
