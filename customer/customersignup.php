<?php
session_start();
include("db.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name   = $_POST['fname'];
    $nid         = $_POST['nid'];
    $password    = $_POST['password'];
    $dob         = $_POST['date'];
    $gender      = $_POST['gender'];
    $division    = $_POST['division'];
    $address     = $_POST['address'];
    $phone       = $_POST['phone'];
    $city        = $_POST['city'];
    $postal_code = $_POST['pin'];
    $email       = $_POST['email'];
    $cusid         = $_POST['cusid'];
    $status = "Not Verified";

    // Hash password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $email_valid = "select * from customers where email ='$email'";
    $result = mysqli_query($conn, $email_valid);
    $nid_valid = "select * from customers where nid ='$nid'";
    $result1 = mysqli_query($conn, $nid_valid);
    if($rows = mysqli_num_rows($result)>0){
        echo "1";
    }else if($rows = mysqli_num_rows($result1)>0){
        echo "2";
    }else{
        $stmt = $conn->prepare("INSERT INTO customer (customer_id, full_name, nid, password, dob, gender, division, address, phone, city, postal_code, email, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssssss", 
        $cusid, $full_name, $nid, $password_hash, $dob, $gender, $division, $address, $phone, $city, $postal_code, $email, $status);

        if ($stmt->execute()) {
            echo "0"   ;
        } else {
        echo "❌ Error: " . $stmt->error;
        }
        $stmt->close();
        
    }

    
}
?>
