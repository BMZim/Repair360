<?php
session_start();
include("db.php");

$customer_id = $_SESSION['customer_id'];

/* ----------------- UPDATE PHOTO ----------------- */
if (isset($_POST['action']) && $_POST['action'] == "update_photo") {

    if (!isset($_FILES['photo'])) {
        echo "error_photo";
        exit;
    }

    $photo = $_FILES['photo'];
    $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($ext, $allowed)) {
        echo "invalidfile";
        exit;
    }

    // Get old photo
    $q = $conn->prepare("SELECT avatar FROM customer WHERE customer_id = ?");
    $q->bind_param("i", $customer_id);
    $q->execute();
    $q->bind_result($oldPhoto);
    $q->fetch();
    $q->close();

    $newName = "cust_" . $customer_id . "_" . time() . "." . $ext;
    $path = "../uploads/" . $newName;

    if (move_uploaded_file($photo['tmp_name'], $path)) {

        // Delete old photo (except default)
        if ($oldPhoto && $oldPhoto != "default.png" && file_exists("../uploads/" . $oldPhoto)) {
            unlink("../uploads/" . $oldPhoto);
        }

        $stmt = $conn->prepare("UPDATE customer SET avatar = ? WHERE customer_id = ?");
        $stmt->bind_param("si", $newName, $customer_id);
        $stmt->execute();

        echo "successphoto";
        exit;
    }

    echo "errorphoto";
    exit;
}


/* ----------------- UPDATE PROFILE INFO ----------------- */
if (isset($_POST['action']) && $_POST['action'] == "update_info") {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $old      = $_POST['oldpassword'];
    $new      = $_POST['newpassword'];

    // Fetch hashed password
    $q = $conn->prepare("SELECT password FROM customer WHERE customer_id = ?");
    $q->bind_param("i", $customer_id);
    $q->execute();
    $q->bind_result($db_pass);
    $q->fetch();
    $q->close();

    // Check old password only if new was entered
    if (!empty($new)) {
        if (!password_verify($old, $db_pass)) {
            echo "wrongpassword";
            exit;
        }

        $new_hash = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE customer SET full_name=?, email=?, phone=?, address=?, password=? WHERE customer_id=?");
        $stmt->bind_param("sssssi", $name, $email, $phone, $address, $new_hash, $customer_id);
    }
    else {
        $stmt = $conn->prepare("UPDATE customer SET full_name=?, email=?, phone=?, address=? WHERE customer_id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $customer_id);
    }

    if ($stmt->execute()) {
        echo "successinfo";
    } else {
        echo "updatefailed";
    }

    exit;
}

echo "invalid";
?>
