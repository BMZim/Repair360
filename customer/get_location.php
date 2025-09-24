<?php
include("db.php");

header('Content-Type: application/json');

if (isset($_GET['mechanic_id'])) {
    $mechanic_id = intval($_GET['mechanic_id']);

    // Get the latest location for this mechanic
    $sql = "SELECT latitude, longitude 
            FROM live_location 
            WHERE mechanic_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mechanic_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "latitude" => $row['latitude'],
            "longitude" => $row['longitude']
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No location found"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Mechanic ID missing"
    ]);
}
?>
