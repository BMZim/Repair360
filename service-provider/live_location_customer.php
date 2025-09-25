<?php
include("connection.php");

header('Content-Type: application/json');

if (!isset($_GET['customer_id'])) {
    echo json_encode(["success"=>false, "msg"=>"Missing customer_id"]);
    exit;
}

$customer_id = intval($_GET['customer_id']);

$sql = "SELECT latitude, longitude FROM live_location_customer 
        WHERE customer_id = ? ";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($lat, $lng);

if ($stmt->fetch()) {
    echo json_encode([
        "success" => true,
        "latitude" => $lat,
        "longitude" => $lng
    ]);
} else {
    echo json_encode(["success"=>false, "msg"=>"No location found"]);
}
$stmt->close();
