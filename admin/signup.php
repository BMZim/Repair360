<?php
include ('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $jobid = $_POST['jobid'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['date'];
    $security = $_POST['security'];
    $answer = $_POST['answer'];
    $gender = $_POST['gender'];
    $division = $_POST['division'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO admins 
        (fname, jobid, email, password, dob, security_question, security_answer, gender, division, address, phone)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssssssss", $fname, $jobid, $email, $password, $dob, $security, $answer, $gender, $division, $address, $phone);

    if ($stmt->execute()) {
        header("Location: login-admin.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
