<?php
include("connection.php");

if (isset($_POST['mechanic_id'])) {
    $mechanic_id = $_POST['mechanic_id'];
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    if ($latitude && $longitude) {
        // Insert or Update live location
        $sql = "INSERT INTO live_location (mechanic_id, latitude, longitude) 
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE latitude = VALUES(latitude), longitude = VALUES(longitude)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sdd", $mechanic_id, $latitude, $longitude);
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
