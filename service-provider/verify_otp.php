<?php
session_start();
include 'connection.php';

// Check session
if (!isset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['signup_data'])) {
    $error = "Session expired. Please start again.";
    $status = "error";
} 
// Check OTP expiry
elseif (time() > $_SESSION['otp_expire']) {
    $error = "OTP has expired. Please request a new one.";
    $status = "error";
} 
// Check OTP match
elseif ($_POST['otp'] != $_SESSION['otp']) {
    $error = "Invalid OTP. Please enter the correct OTP.";
    $status = "error";
} 
else {
    // OTP valid, insert user
    $data = $_SESSION['signup_data'];

    $name = $data['name'];
    $mecid = $data['mechanicid'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);
    $dob = $data['dob'];
    $gender = $data['gender'];
    $nid = $data['nid'];
    $division = $data['division'];
    $address = $data['address'];
    $phone = $data['phone'];
    $city = $data['city'];
    $type = $data['mechanicType'];
    $experience = $data['experience'];

    // Check duplicate email
    $check = mysqli_query($con, "SELECT * FROM mechanic WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already exists.";
        $status = "error";
    } else {
        // Insert into database
        $sql = "INSERT INTO mechanic 
        VALUES ('$mecid','$name','$nid','$password','$dob','$gender',
                '$division','$address','$phone','$city','$email',
                'New User','$type','$experience','')";
        mysqli_query($con, $sql);

        // Cleanup session
        unset($_SESSION['otp'], $_SESSION['otp_expire']);

        $status = "success";
        $message = "Your account has been created successfully!";
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
    background: linear-gradient(135deg, #ff9800, #ff5722);
    font-family: 'Inter', sans-serif;
    margin: 0;
}
</style>
</head>
<body>

<script>
<?php if(isset($status) && $status === "success"): ?>
Swal.fire({
    title: 'Registration Successful!',
    text: '<?php echo $message; ?>',
    icon: 'success',
    confirmButtonText: 'Proceed to Skills',
    allowOutsideClick: false,
    allowEscapeKey: false
}).then(() => {
    const type = "<?php echo $type; ?>";
    const mecid = "<?php echo $mecid; ?>";
    if (type && mecid) {
                window.location.href = `skills.php?type=${encodeURIComponent(type)}&id=${encodeURIComponent(mecid)}`;
            } else {
                Swal.fire({ icon: "error", title: "Error", text: "Mechanic type or ID missing!" });
            }
});
<?php else: ?>
Swal.fire({
    title: 'Oops!',
    text: '<?php echo $error; ?>',
    icon: 'error',
    confirmButtonText: 'Back to Signup',
    allowOutsideClick: false,
    allowEscapeKey: false
}).then(() => {
    window.location.href = 'mechanicsignup.html';
});
<?php endif; ?>
</script>

</body>
</html>
