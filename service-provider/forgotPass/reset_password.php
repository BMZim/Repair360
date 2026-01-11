<?php
session_start();
require '../connection.php';

$error = "";

if (!isset($_SESSION['fp_email'])) {
    header("Location: mechanic_login.php");
    exit();
}

if (isset($_POST['reset'])) {
    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirm'];

    if ($pass1 !== $pass2) {
        $error = "Passwords do not match.";
    } elseif (strlen($pass1) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        $hash = password_hash($pass1, PASSWORD_BCRYPT);
        $email = $_SESSION['fp_email'];

        mysqli_query($con, "UPDATE mechanic SET password='$hash' WHERE email='$email'");

        session_destroy();
        echo "<script>alert('Password changed successfully!'); 
                  window.location.href='../mechanic_login.php';</script>";
            exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#007bff,#00c6ff);
    font-family:'Inter',sans-serif;
}
.box{
    background:#111;
    padding:40px;
    border-radius:16px;
    width:400px;
    color:#fff;
}
input,button{
    width:100%;
    padding:14px;
    margin-top:12px;
    border-radius:10px;
}
input{background:#1b1b1b;color:#fff;border:1px solid #333}
button{
    background:linear-gradient(135deg,#007bff,#00c6ff);
    font-weight:700;
}
</style>
</head>
<body>

<div class="box">
<h2>Reset Password</h2>
<form method="POST">
<input type="password" name="password" placeholder="New Password" required>
<input type="password" name="confirm" placeholder="Confirm Password" required>
<button type="submit" name="reset">Update Password</button>
</form>
</div>

<?php if($error): ?>
<script>
Swal.fire({ icon:'error', text:'<?php echo $error; ?>' });
</script>
<?php endif; ?>

</body>
</html>
