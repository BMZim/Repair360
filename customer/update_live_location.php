<?php
include("db.php");

if (isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    if ($latitude && $longitude) {
        // Insert or Update live location
        $sql = "INSERT INTO live_location_customer (customer_id, latitude, longitude) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE latitude = VALUES(latitude), longitude = VALUES(longitude)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdd", $customer_id, $latitude, $longitude);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "no_location";
    }
} else {
    echo "not_logged_in";
}
?>
