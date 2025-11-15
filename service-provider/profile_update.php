<?php
session_start();
include("connection.php");

$mechanic_id = $_SESSION['id'];

/* ---UPDATE PHOTO -- */
if (isset($_POST['action']) && $_POST['action'] == "update_photo") {

    if (!isset($_FILES['photo'])) {
        echo "error_photo";
        exit;
    }

    // Fetch old photo
    $oldPhotoQuery = $con->prepare("SELECT avatar FROM mechanic WHERE mechanic_id = ?");
    $oldPhotoQuery->bind_param("i", $mechanic_id);
    $oldPhotoQuery->execute();
    $oldPhotoQuery->bind_result($oldPhoto);
    $oldPhotoQuery->fetch();
    $oldPhotoQuery->close();

    // Validate new file
    $photo = $_FILES['photo'];
    $ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ["jpg", "jpeg", "png", "webp"])) {
        echo "invalidfile";
        exit;
    }

    $newName = "mech_" . $mechanic_id . "_" . time() . "." . $ext;
    $newPath = "../uploads/" . $newName;

    // Upload new file
    if (move_uploaded_file($photo['tmp_name'], $newPath)) {

        // Delete old photo (if exists and is not default)
        if (!empty($oldPhoto) && $oldPhoto !== "default.png") {
            $oldPath = "../uploads/" . $oldPhoto;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Update DB
        $sql = "UPDATE mechanic SET avatar = ? WHERE mechanic_id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $newName, $mechanic_id);
        $stmt->execute();

        echo "successphoto";
        exit;
    }

    echo "errorphoto";
    exit;
}



/* --UPDATE PROFILE INFO --- */
if (isset($_POST['action']) && $_POST['action'] == "update_info") {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $old   = $_POST['oldpassword'];
    $new   = $_POST['newpassword'];

    // Fetch hashed password
    $q = $con->prepare("SELECT password FROM mechanic WHERE mechanic_id = ?");
    $q->bind_param("i", $mechanic_id);
    $q->execute();
    $q->store_result();
    $q->bind_result($db_pass);
    $q->fetch();

    // Old password verification
    if (!password_verify($old, $db_pass)) {
        echo "wrongpassword";
        exit;
    }

    // If new password provided → update password also
    if (!empty($new)) {
        $new_hash = password_hash($new, PASSWORD_DEFAULT);

        $update = $con->prepare("UPDATE mechanic 
                                 SET full_name = ?, email = ?, phone = ?, password = ?
                                 WHERE mechanic_id = ?");
        $update->bind_param("ssssi", $name, $email, $phone, $new_hash, $mechanic_id);

    } else {
        // Update without password
        $update = $con->prepare("UPDATE mechanic 
                                 SET full_name = ?, email = ?, phone = ?
                                 WHERE mechanic_id = ?");
        $update->bind_param("sssi", $name, $email, $phone, $mechanic_id);
    }

    if ($update->execute()) {
        echo "successinfo";
    } else {
        echo "updatefailed";
    }

    exit;
}

echo "invalid";
?>
