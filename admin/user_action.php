<?php
include("config.php");

$action = $_POST['action'] ?? '';
$id = intval($_POST['id'] ?? 0);
$role = $_POST['role'] ?? '';

if(!$id || !$role){
    echo "Invalid data";
    exit;
}

$table = ($role === 'Customer') ? 'customer' : 'mechanic';
$id_column = ($role === 'Customer') ? 'customer_id' : 'mechanic_id';

if($action === 'approve'){
    $sql = "UPDATE $table SET status='Verified' WHERE $id_column=?";
}
elseif($action === 'block'){
    $sql = "UPDATE $table SET status='Blocked' WHERE $id_column=?";
}
elseif($action === 'unblock'){
    $sql = "UPDATE $table SET status='New User' WHERE $id_column=?";
}
elseif($action === 'delete'){
    $sql = "DELETE FROM $table WHERE $id_column=?";
}
else {
    echo "Invalid action";
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

echo ($stmt->execute()) ? "OK" : "DB Error";

$stmt->close();
$conn->close();
?>
