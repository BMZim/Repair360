<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: customersignup.html");
    exit();
}

$_SESSION['signup_data'] = $_POST;

$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_expire'] = time() + 300; // 5 minutes

$email = $_POST['email'];

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'otprepair360@gmail.com';
    $mail->Password   = 'ilyz znlw nqpz uxxj'; // App password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

   
    $mail->setFrom('otprepair360@gmail.com', 'Customer Registration');
    $mail->addAddress($email);

    $mail->Subject = 'Your OTP Verification Code';
    $mail->Body    = "Your OTP is: $otp\n\nThis OTP is valid for 5 minutes.\n\nRepair360 Team";

    $mail->send();

} catch (Exception $e) {
    die("OTP sending failed: " . $mail->ErrorInfo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OTP Verification</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    min-height: 100vh;
    background:
        linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)),
        url('https://img.freepik.com/free-photo/instruments-carpenter-wooden-desk_23-2148180571.jpg?semt=ais_hybrid&w=740&q=80') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.otp-container {
    background: #111;
    border-radius: 18px;
    padding: 40px;
    width: 100%;
    max-width: 420px;
    text-align: center;
    color: #fff;
    box-shadow: 0 25px 60px rgba(0,0,0,0.8);
    border: 1px solid rgba(255,255,255,0.08);
}

.otp-container h2 {
    font-size: 28px;
    margin-bottom: 10px;
    font-weight: 700;
}

.otp-container p {
    font-size: 14px;
    color: #aaa;
    margin-bottom: 25px;
}

.otp-input {
    width: 100%;
    padding: 14px;
    font-size: 20px;
    text-align: center;
    letter-spacing: 6px;
    border-radius: 12px;
    border: 1px solid #333;
    background: #1a1a1a;
    color: #fff;
    margin-bottom: 20px;
}

.otp-input:focus {
    outline: none;
    border-color: #ff9800;
    box-shadow: 0 0 0 2px rgba(255,152,0,0.25);
}

.verify-btn {
    width: 100%;
    padding: 14px;
    font-size: 16px;
    font-weight: 700;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    background: linear-gradient(135deg, #ff9800, #ff5722);
    color: #000;
}

.timer {
    margin-top: 18px;
    font-size: 13px;
    color: #ff4d4d;
}

.footer-text {
    margin-top: 25px;
    font-size: 12px;
    color: #777;
}
</style>
</head>

<body>

<div class="otp-container">
    <h2>Email Verification</h2>
    <p>An OTP has been sent to <strong><?php echo htmlspecialchars($email); ?></strong></p>

    <form method="POST" action="verify_otp.php">
        <input type="number" name="otp" class="otp-input" placeholder="••••••" required>
        <button type="submit" class="verify-btn">Verify OTP</button>
    </form>

    <div class="timer">OTP valid for 5 minutes</div>

    <div class="footer-text">
        © <?php echo date('Y'); ?> Repair360 • Secure Verification
    </div>
</div>

</body>
</html>
