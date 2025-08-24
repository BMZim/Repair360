<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      background-size: 400% 400%;
      animation: gradient 15s ease infinite;
    }
    @keyframes gradient {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .login-container {
      backdrop-filter: blur(12px);
      background: rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      padding: 40px 30px;
      width: 400px;
      color: #fff;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
    }
    .form-group {
      margin-bottom: 20px;
      position: relative;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }
    input[type="text"],
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      backdrop-filter: blur(4px);
    }
    input::placeholder {
      color: #e0e0e0;
    }
    input:focus {
      outline: none;
      box-shadow: 0 0 5px #00d4ff;
      background: rgba(255, 255, 255, 0.2);
    }
    .toggle-password {
      position: absolute;
      top: 40px;
      right: 12px;
      cursor: pointer;
      user-select: none;
      font-size: 16px;
      color: #00d4ff;
    }
    .login-btn {
      width: 100%;
      padding: 12px;
      background: #00d4ff;
      color: #000;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .login-btn:hover {
      background: #00aacc;
    }
    .link {
      text-align: center;
      margin-top: 15px;
    }
    .link a {
      color: #00d4ff;
      text-decoration: none;
      font-size: 14px;
    }
    .link a:hover {
      text-decoration: underline;
    }
    @media (max-width: 500px) {
      .login-container {
        width: 90%;
        padding: 30px 20px;
      }
    }
  </style>
</head>
<?php
require 'connection.php';

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];

    $sql = "SELECT mechanic_id, email, password, phone FROM mechanic WHERE email = '$id' OR phone = '$id'";
    
    $result = mysqli_query($con, $sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hashedPass = $user['password'];
        if (password_verify($password, $hashedPass)) {
            $_SESSION['id'] = $user['mechanic_id'];
            header("Location: mechanic-dashboard.php");
            exit();
        } 
    } else {
        header('Location: mechanic_login.php?error=Invalid Login Information');
        exit();
    }
}
?>

<body>
        <div class="login-container">
          <h2>Login</h2>
          <form  method="POST">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="text" name="id" id="id" placeholder="Enter your email or phone no" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" name="password" id="password" placeholder="Enter your password" required>
              <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>
            <button type="submit" name="submit" class="login-btn">Login</button>
            <?php 
                if(isset($_GET['error'])){  
                    echo '<p style="color:red;">'.$_GET['error'].'</p>';
                }

            ?>
            <div class="link">
              Don't have an account? <a href="mechanicsignup.html">Sign Up Here</a>
            </div>
          </form>
        </div>
        <script>
          function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggle = document.querySelector(".toggle-password");
            if (passwordInput.type === "password") {
              passwordInput.type = "text";
              toggle.textContent = "🙈";
            } else {
              passwordInput.type = "password";
              toggle.textContent = "👁️";
            }
          }
        </script>
      </body>
</html>