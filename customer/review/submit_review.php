<?php
session_start();
include("../db.php");
$customer_id = $_SESSION['customer_id'];

$service_id = $_POST['service_id'];
$mechanic_id = $_POST['mechanic_id'];
$rating = $_POST['rating'];
$review = $_POST['review'];

$stmt = $conn->prepare("INSERT INTO mechanic_rating (service_id, mechanic_id, customer_id, rating, review) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiis", $service_id, $mechanic_id, $customer_id, $rating, $review);

if ($stmt->execute()) echo "Review submitted successfully!";
else echo "Failed: " . $stmt->error;
