<?php 
session_start();
require 'connection.php';

$error = ""; // Variable to store login error

if (isset($_POST['submit'])) {
    $id = trim($_POST['id']);
    $password = trim($_POST['password']);

    $sql = "SELECT mechanic_id, email, password, phone FROM mechanic WHERE email = '$id' OR phone = '$id'";
    $result = mysqli_query($con, $sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hashedPass = $user['password'];
        if (password_verify($password, $hashedPass)) {
            $_SESSION['id'] = $user['mechanic_id'];
            header("Location: mechanic-dashboard.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "Email or phone not found. Please check your input.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Mechanic Login</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
      linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
      url("img/LS.jpeg");
    background-size: cover;
    background-position: center;
}
.login-container {
    width: 420px;
    background: #111;
    border-radius: 16px;
    padding: 40px 35px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.7);
    border: 1px solid rgba(255,255,255,0.08);
    color: #fff;
}
.login-container h2 { text-align: center; margin-bottom: 10px; font-size: 26px; font-weight: 700; letter-spacing: 1px; }
.login-container p { text-align: center; color: #aaa; margin-bottom: 30px; font-size: 14px; }
.form-group { margin-bottom: 20px; position: relative; }
label { font-size: 14px; margin-bottom: 8px; display: block; color: #ccc; }
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 13px 14px;
    border-radius: 10px;
    border: 1px solid #333;
    background: #1b1b1b;
    color: #fff;
    font-size: 15px;
    transition: all 0.3s ease;
}
input::placeholder { color: #777; }
input:focus {
    outline: none;
    border-color: #ff9800;
    box-shadow: 0 0 0 2px rgba(255,152,0,0.2);
    background: #1f1f1f;
}
.toggle-password {
    position: absolute;
    right: 12px;
    top: 42px;
    cursor: pointer;
    font-size: 16px;
    color: #ff9800;
    user-select: none;
}
.login-btn {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #ff9800, #ff5722);
    color: #000;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255,152,0,0.4);
}
.link { text-align: center; margin-top: 20px; font-size: 14px; color: #aaa; }
.link a { color: #ff9800; text-decoration: none; font-weight: 600; }
.link a:hover { text-decoration: underline; }
@media (max-width: 500px) { .login-container { width: 90%; padding: 30px 25px; } }
</style>
</head>
<body>
<div class="login-container">
    <h2>Mechanic Login</h2>
    <p>Access your garage dashboard</p>

    <form method="POST">
        <div class="form-group">
            <label>Email or Phone</label>
            <input type="text" name="id" placeholder="Enter email or phone number" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <button type="submit" name="submit" class="login-btn">Login</button>
        <div class="link">
            <a href="forgotPass/forgot_password.php">Forgot Password?</a>
        </div>
        <div class="link">
            Don’t have an account? <a href="mechanicsignup.html">Create one</a>
        </div>
    </form>
</div>

<script>
function togglePassword() {
    const passwordInput = document.querySelector("input[name='password']");
    const toggle = document.querySelector(".toggle-password");
    if (passwordInput.type === "password") {
        passwordInput.type = "text"; toggle.textContent = "🙈";
    } else {
        passwordInput.type = "password"; toggle.textContent = "👁️";
    }
}

// Show error with SweetAlert if $error is set
<?php if(!empty($error)): ?>
Swal.fire({
    title: 'Login Failed',
    text: '<?php echo $error; ?>',
    icon: 'error',
    confirmButtonText: 'Try Again',
    allowOutsideClick: false
});
<?php endif; ?>
</script>
</body>
</html>
