<?php
include("config.php");

$action = $_POST["action"] ?? "";

if($action === "update_contact"){

    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    if(empty($email) || empty($phone)){
        echo "Missing fields";
        exit;
    }

    $sql = "UPDATE contact SET email=?, phone=? WHERE id=1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $phone);

    echo $stmt->execute() ? "OK" : "DB Error";
    exit;
}

if($action === "update_charges"){

    $vat = floatval($_POST["vat"]);
    $fee = floatval($_POST["fee"]);

    $sql = "UPDATE service_charge SET vat_amount=?, platform_fee=? WHERE id=1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dd", $vat, $fee);

    echo $stmt->execute() ? "OK" : "DB Error";
    exit;
}

echo "Invalid Action";
?>
