<?php
session_start();
include("../db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../PHPMailer/src/Exception.php";
require "../PHPMailer/src/PHPMailer.php";
require "../PHPMailer/src/SMTP.php";


if (isset($_POST['action'])) {

    if ($_POST['action'] === "send_otp") {

        $email = trim($_POST['email']);

        $stmt = $conn->prepare("SELECT customer_id FROM customer WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            echo json_encode(["status" => "error", "message" => "Account does not exist on this email."]);
            exit();
        }

        $otp = rand(100000, 999999);

        $_SESSION['cust_fp_otp'] = $otp;
        $_SESSION['cust_fp_email'] = $email;
        $_SESSION['cust_fp_expire'] = time() + 300;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "otprepair360@gmail.com";
            $mail->Password = "ilyz znlw nqpz uxxj";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom("otprepair360@gmail.com", "Repair360");
            $mail->addAddress($email);
            $mail->Subject = "Customer Password Reset OTP";
            $mail->Body = "Your OTP is: $otp\nValid for 5 minutes.";

            $mail->send();

            echo json_encode(["status" => "success", "message" => "OTP sent to your email."]);
            exit();
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Failed to send OTP."]);
            exit();
        }
    }

    /* ---- VERIFY OTP ---- */
    if ($_POST['action'] === "verify_otp") {

        $otp = $_POST['otp'];

        if (!isset($_SESSION['cust_fp_otp'])) {
            echo json_encode(["status" => "error", "message" => "Session expired. Try again."]);
            exit();
        }

        if (time() > $_SESSION['cust_fp_expire']) {
            echo json_encode(["status" => "error", "message" => "OTP expired."]);
            exit();
        }

        if ($otp != $_SESSION['cust_fp_otp']) {
            echo json_encode(["status" => "error", "message" => "Invalid OTP."]);
            exit();
        }

        echo json_encode(["status" => "success", "redirect" => "reset_password.php"]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#4b47ff,#8057ff);
    font-family:Segoe UI;
}
.box{
    background:#111;
    padding:40px;
    width:420px;
    border-radius:16px;
    color:#fff;
}
input,button{
    width:100%;
    padding:14px;
    border-radius:10px;
    border:none;
    margin-top:12px;
}
input{background:#1b1b1b;color:#fff;border:1px solid #333;}
button{
    background:linear-gradient(135deg,#4b47ff,#8057ff);
    font-weight:700;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="box">
<h2>Forgot Password</h2>

<input type="email" id="email" placeholder="Enter registered email">

<button id="sendOtpBtn">Get Code</button>

<div id="otpBox" style="display:none;">
    <input type="number" id="otp" placeholder="Enter OTP">
    <button id="verifyOtpBtn">Verify OTP</button>
</div>
</div>

<script>
$("#sendOtpBtn").click(function () {

    let email = $("#email").val().trim();
    if (email === "") {
        Swal.fire("Error", "Email is required", "error");
        return;
    }

    $.post("forgot_password.php", {
        action: "send_otp",
        email: email
    }, function (res) {
        let data = JSON.parse(res);

        if (data.status === "success") {
            Swal.fire("Success", data.message, "success");
            $("#sendOtpBtn").text("Send Code Again");
            $("#otpBox").slideDown();
        } else {
            Swal.fire("Error", data.message, "error");
        }
    });
});

$("#verifyOtpBtn").click(function () {
    let otp = $("#otp").val().trim();

    if (otp === "") {
        Swal.fire("Error", "Enter OTP", "error");
        return;
    }

    $.post("forgot_password.php", {
        action: "verify_otp",
        otp: otp
    }, function (res) {
        let data = JSON.parse(res);

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
