<?php
session_start();
include("../db.php");

if (!isset($_SESSION['cust_fp_email'])) {
    header("Location: forgot_password.php");
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === "reset_pass") {

    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirm'];

    if ($pass1 !== $pass2) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit();
    }

    $hash = password_hash($pass1, PASSWORD_DEFAULT);
    $email = $_SESSION['cust_fp_email'];

    $stmt = $conn->prepare("UPDATE customer SET password=? WHERE email=?");
    $stmt->bind_param("ss", $hash, $email);
    $stmt->execute();

    session_unset();
    session_destroy();

    echo json_encode(["status" => "success", "redirect" => "../customer-login.php"]);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#ff5f6d,#ff4383);
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
    background:linear-gradient(135deg,#ff5f6d,#ff4383);
    font-weight:700;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="box">
<h2>Reset Password</h2>

<input type="password" id="pass1" placeholder="New Password">
<input type="password" id="pass2" placeholder="Confirm Password">

<button id="resetBtn">Update Password</button>
</div>

<script>
$("#resetBtn").click(function () {

    let p1 = $("#pass1").val();
    let p2 = $("#pass2").val();

    if (!p1 || !p2) {
        Swal.fire("Error", "All fields required", "error");
        return;
    }

    $.post("reset_password.php", {
        action: "reset_pass",
        password: p1,
        confirm: p2
    }, function (res) {
        let data = JSON.parse(res);

        if (data.status === "success") {
            Swal.fire("Success", "Password updated", "success")
            .then(() => window.location.href = data.redirect);
        } else {
            Swal.fire("Error", data.message, "error");
        }
    });
});
</script>

</body>
</html>
