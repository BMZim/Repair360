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

if($action === 'approve'){
    $sql = "UPDATE $table SET status='Verified' WHERE {$table}_id=?";
} elseif($action === 'block'){
    $sql = "UPDATE $table SET status='Blocked' WHERE {$table}_id=?";
} elseif($action === 'delete'){
    $sql = "DELETE FROM $table WHERE {$table}_id=?";
} else {
    echo "Invalid action";
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
if($stmt->execute()){
    echo "OK";
} else {
    echo "DB Error";
}
$stmt->close();
$conn->close();
?>
