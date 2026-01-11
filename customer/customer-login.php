<?php
session_start();
include("db.php");

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT customer_id, full_name, email, phone, password FROM customer WHERE email=? OR phone=? LIMIT 1");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Password verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];

            header("Location: customer-dashboard.php");
            exit();
        } else {
            header('Location: customer-login.php?error=Invalid Login Information');
        exit();
        }
    } else {
         header('Location: customer-login.php?error=User not found!');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Customer Login</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(45deg, #1e0054, #370068, #6a0093, #c4008c);
      background-size: 400% 400%;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .geometric-shapes {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .shape {
      position: absolute;
      border: 2px solid rgba(255, 255, 255, 0.1);
      border-radius: 5px;
    }

    .shape1 {
      width: 400px;
      height: 400px;
      transform: rotate(45deg);
      top: 50%;
      left: 35%;
      margin-top: -200px;
      margin-left: -200px;
    }

    .shape2 {
      width: 500px;
      height: 500px;
      transform: rotate(45deg);
      top: 50%;
      right: 25%;
      margin-top: -250px;
      margin-right: -250px;
    }

    .neon-line {
      position: absolute;
      background: linear-gradient(90deg, rgba(255,255,255,0), #ff00dd, rgba(255,255,255,0));
      height: 2px;
      width: 300px;
      animation: neonAnimation 4s linear infinite;
    }

    .neon-line1 { top: 20%; left: 10%; transform: rotate(25deg); }
    .neon-line2 { bottom: 30%; right: 15%; transform: rotate(-15deg); }
    .neon-line3 { top: 70%; left: 30%; transform: rotate(55deg); }

    @keyframes neonAnimation {
      0%, 100% { opacity: 0.2; }
      50% { opacity: 1; }
    }

    .accent-bar {
      position: absolute;
      width: 10px;
      height: 80px;
      background-color: #ffb800;
      border-radius: 2px;
    }

    .accent-bar1 { left: 20%; top: 30%; }
    .accent-bar2 { right: 20%; bottom: 30%; }

    .container {
      display: flex;
      width: 900px;
      max-width: 95%;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      z-index: 10;
    }

    .login-form {
      flex: 1;
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .welcome-side {
      flex: 1;
      background: linear-gradient(135deg, #ff5f6d, #ff4383);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      padding: 50px;
      text-align: center;
    }

    h2 {
      font-size: 28px;
      margin-bottom: 30px;
      color: white;
    }

    .form-group {
      margin-bottom: 25px;
      position: relative;
    }

    label {
      display: block;
      color: #ccc;
      font-size: 12px;
      margin-bottom: 5px;
      text-transform: uppercase;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      border: none;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 30px;
      color: white;
      font-size: 16px;
      outline: none;
      transition: all 0.3s ease;
    }

    input:focus {
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    input::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    .btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 30px;
      background: linear-gradient(90deg, #4b47ff, #8057ff);
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 15px;
    }

    .btn:hover {
      background: linear-gradient(90deg, #3934ff, #6846ff);
      box-shadow: 0 0 20px rgba(117, 79, 254, 0.5);
      transform: translateY(-2px);
    }
    .signup {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 30px;
      background: linear-gradient(90deg, #4b47ff, #8057ff);
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 15px;
      text-decoration: none;
      color: white;
    }

    .signup:hover {
      background: linear-gradient(90deg, #3934ff, #6846ff);
      box-shadow: 0 0 20px rgba(117, 79, 254, 0.5);
      transform: translateY(-2px);
    }

    .checkbox-container {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .checkbox-container input {
      margin-right: 10px;
    }

    .checkbox-container label {
      color: rgba(255, 255, 255, 0.8);
      font-size: 14px;
      text-transform: none;
      margin: 0;
    }

    .forgot-password {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      font-size: 14px;
      margin-bottom: 10px;
      display: inline-block;
    }

    .forgot-password:hover {
      color: white;
      text-decoration: underline;
    }

    .toggle-password {
      position: absolute;
      right: 15px;
      top: 34px;
      color: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      font-size: 18px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .welcome-side {
        padding: 30px;
      }

      .login-form {
        padding: 30px;
      }
    }
  </style>
</head>




<body>
  <div class="geometric-shapes">
    <div class="shape shape1"></div>
    <div class="shape shape2"></div>
    <div class="neon-line neon-line1"></div>
    <div class="neon-line neon-line2"></div>
    <div class="neon-line neon-line3"></div>
    <div class="accent-bar accent-bar1"></div>
    <div class="accent-bar accent-bar2"></div>
  </div>

  <div class="container">
    <!-- Login Form -->
    <form class="login-form" onsubmit="return validateForm()" method="POST">
      <h2>Log In</h2>

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="email" id="username" placeholder="Enter your email or phone no" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required />
        <span class="toggle-password" onclick="togglePassword()">👁️</span>
      </div>

      <a href="forgotPass/forgot_password.php" class="forgot-password">Forgot Password?</a>
      <button type="submit" name="submit" id="submit" value="submit" class="btn">Sign In</button>
    </form>

    <!-- Welcome Side -->
    <div class="welcome-side">
      <h2>Welcome Back</h2>
      <p>Don't have an account?</p>
      <a href="customersignup.html" class="signup">Sign Up</a>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById("password");
      input.type = input.type === "password" ? "text" : "password";
    }

    function validateForm() {
      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value.trim();

      if (!username || !password) {
        alert("Please fill in all fields.");
        return false;
      }

    }
  </script>
  <?php if (isset($_GET['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Login Failed',
    text: '<?php echo htmlspecialchars($_GET['error']); ?>',
    confirmButtonText: 'Try Again',
    allowOutsideClick: false
});
</script>
<?php endif; ?>

</body>
</html>

