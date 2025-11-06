<?php
session_start();
include("../db.php");
$id = $_POST['rating_id'];
$review = $_POST['review'];

$stmt = $conn->prepare("UPDATE mechanic_rating SET review = ? WHERE rating_id = ?");
$stmt->bind_param("si", $review, $id);
echo $stmt->execute() ? "Review updated!" : "Update failed!";
