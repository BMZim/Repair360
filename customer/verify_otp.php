<?php
session_start();
include 'db.php';

if (!isset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['signup_data'])) {
    $error = "Session expired. Please sign up again.";
    $status = "error";
} 
elseif (time() > $_SESSION['otp_expire']) {
    $error = "OTP has expired. Please request a new OTP.";
    $status = "error";
} 
elseif (!isset($_POST['otp']) || $_POST['otp'] != $_SESSION['otp']) {
    $error = "Invalid OTP. Please enter the correct OTP.";
    $status = "error";
} 
else {
    $data = $_SESSION['signup_data'];

    $cusid    = $data['customer-id'];
    $name     = $data['full-name'];
    $nid      = $data['nid'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);
    $dob      = $data['dob'];
    $gender   = $data['gender'];
    $division = $data['division'];
    $address  = $data['address'];
    $phone    = $data['phone'];
    $city     = $data['city'];
    $postal   = $data['pin-code'];
    $email    = $data['email'];
    $status_user = "Active";

    $checkEmail = mysqli_query($conn, "SELECT customer_id FROM customer WHERE email='$email'");
    $checkNID   = mysqli_query($conn, "SELECT customer_id FROM customer WHERE nid='$nid'");

    if (mysqli_num_rows($checkEmail) > 0) {
        $error = "Email already exists.";
        $status = "error";
    } 
    elseif (mysqli_num_rows($checkNID) > 0) {
        $error = "NID already exists.";
        $status = "error";
    } 
    else {
        $sql = "INSERT INTO customer 
        (customer_id, full_name, nid, password, dob, gender, division, address, phone, city, postal_code, email, status)
        VALUES 
        ('$cusid','$name','$nid','$password','$dob','$gender','$division',
         '$address','$phone','$city','$postal','$email','$status_user')";

        if (mysqli_query($conn, $sql)) {

            unset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['signup_data']);

            $status = "success";
            $message = "Your customer account has been created successfully!";
        } else {
            $error = "Database error. Please try again.";
            $status = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OTP Verification</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #007bff, #00c6ff);
    font-family: 'Inter', sans-serif;
    margin: 0;
}
</style>
</head>
<body>

<script>
<?php if ($status === "success"): ?>
Swal.fire({
    title: 'Registration Successful!',
    text: '<?php echo $message; ?>',
    icon: 'success',
    confirmButtonText: 'Go to Login',
    allowOutsideClick: false,
    allowEscapeKey: false
}).then(() => {
    window.location.href = 'customer-login.php';
});
<?php else: ?>
Swal.fire({
    title: 'Verification Failed',
    text: '<?php echo $error; ?>',
    icon: 'error',
    confirmButtonText: 'Back to Signup',
    allowOutsideClick: false,
    allowEscapeKey: false
}).then(() => {
    window.location.href = 'customersignup.html';
});
<?php endif; ?>
</script>

</body>
</html>
