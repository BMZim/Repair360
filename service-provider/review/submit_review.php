<?php
session_start();
include("../connection.php");

if (!isset($_SESSION['id'])) {
  echo "Not logged in!";
  exit;
}

$mechanic_id = $_SESSION['id'];
$customer_id = $_POST['customer_id'];
$service_id = $_POST['service_id'];
$rating = $_POST['rating'];
$review = $_POST['review'];

$stmt = $con->prepare("INSERT INTO customer_rating (mechanic_id, customer_id, service_id, rating, review) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiis", $mechanic_id, $customer_id, $service_id, $rating, $review);

if ($stmt->execute()) {
  echo "Review submitted successfully!";
} else {
  echo "Error: " . $stmt->error;
}
