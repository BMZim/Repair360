<?php
session_start();
require '../connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

/* ======================================
   AJAX REQUEST HANDLER
====================================== */
if (isset($_POST['action'])) {

    /* ---------- SEND OTP ---------- */
    if ($_POST['action'] === 'send_otp') {
        $email = trim($_POST['email']);

        $check = mysqli_query($con, "SELECT mechanic_id FROM mechanic WHERE email='$email'");
        if (mysqli_num_rows($check) !== 1) {
            echo json_encode([
                "status" => "error",
                "message" => "Account does not exist with this email."
            ]);
            exit();
        }

        $otp = rand(100000, 999999);
        $_SESSION['fp_otp'] = $otp;
        $_SESSION['fp_email'] = $email;
        $_SESSION['fp_expire'] = time() + 300;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'otprepair360@gmail.com';
            $mail->Password = 'ilyz znlw nqpz uxxj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('otprepair360@gmail.com', 'Repair360');
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = "Your OTP is: $otp\nValid for 5 minutes.";

            $mail->send();

            echo json_encode([
                "status" => "success",
                "message" => "OTP sent to your email."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to send OTP."
            ]);
        }
        exit();
    }

    /* ---------- VERIFY OTP ---------- */
    if ($_POST['action'] === 'verify_otp') {

        if (!isset($_SESSION['fp_otp'], $_SESSION['fp_expire'])) {
            echo json_encode(["status" => "error", "message" => "Session expired."]);
            exit();
        }

        if (time() > $_SESSION['fp_expire']) {
            echo json_encode(["status" => "error", "message" => "OTP expired."]);
            exit();
        }

        if ($_POST['otp'] != $_SESSION['fp_otp']) {
            echo json_encode(["status" => "error", "message" => "Invalid OTP."]);
            exit();
        }

        echo json_encode([
            "status" => "success",
            "redirect" => "reset_password.php"
        ]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#ff9800,#ff5722);
    font-family:'Inter',sans-serif;
}
.box{
    background:#111;
    padding:40px;
    border-radius:16px;
    width:420px;
    color:#fff;
    box-shadow:0 20px 50px rgba(0,0,0,0.7);
}
h2{text-align:center;margin-bottom:20px}
input,button{
    width:100%;
    padding:14px;
    border-radius:10px;
    border:none;
    margin-top:12px;
}
input{background:#1b1b1b;color:#fff;border:1px solid #333}
button{
    background:linear-gradient(135deg,#ff9800,#ff5722);
    font-weight:700;
    cursor:pointer;
}
#otp-section{display:none;}
</style>
</head>

<body>

<div class="box">
<h2>Forgot Password</h2>

<input type="email" id="email" placeholder="Enter registered email" required>

<button id="sendOtpBtn">Get Code</button>

<div id="otp-section">
    <input type="number" id="otp" placeholder="Enter OTP">
    <button id="verifyOtpBtn">Verify OTP</button>
</div>

</div>

<script>
/* =============================
   SEND OTP
============================= */
$("#sendOtpBtn").click(function () {
    const email = $("#email").val().trim();

    if (email === "") {
        Swal.fire("Error", "Please enter email", "error");
        return;
    }

    $.post("forgot_password.php", {
        action: "send_otp",
        email: email
    }, function (res) {
        const data = JSON.parse(res);

        if (data.status === "success") {
            Swal.fire("Success", data.message, "success");
            $("#otp-section").slideDown();
            $("#sendOtpBtn").text("Send Code Again");
        } else {
            Swal.fire("Error", data.message, "error");
        }
    });
});

/* =============================
   VERIFY OTP
============================= */
$("#verifyOtpBtn").click(function () {
    const otp = $("#otp").val().trim();

    if (otp === "") {
        Swal.fire("Error", "Enter OTP", "error");
        return;
    }

    $.post("forgot_password.php", {
        action: "verify_otp",
        otp: otp
    }, function (res) {
        const data = JSON.parse(res);

        if (data.status === "success") {
            window.location.href = data.redirect;
        } else {
            Swal.fire("Error", data.message, "error");
        }
    });
});
</script>

</body>
</html>
