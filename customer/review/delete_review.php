<?php
session_start();
include("../db.php");
$id = $_POST['rating_id'];

$stmt = $conn->prepare("DELETE FROM mechanic_rating WHERE rating_id = ?");
$stmt->bind_param("i", $id);
echo $stmt->execute() ? "Review deleted!" : "Failed to delete!";
