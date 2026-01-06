<?php
session_start();
include("conn.php");

if(isset($_POST["submit"])){
    $user_name= $_POST['username'];
    $user_id= $_POST['id'];
    $email= $_POST['email'];
    $user_type= $_POST['type'];
    $subject = trim($_POST["subject"]);
    $desc    = trim($_POST["desc"]);

    $stmt = $con->prepare("
        INSERT INTO support_tickets (user_type, user_id, user_name, email, subject, description)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sissss", $user_type, $user_id, $user_name, $email, $subject, $desc);

    if($stmt->execute()){
        echo "<script>
            setTimeout(function(){
                Swal.fire({
                    icon: 'success',
                    title: 'Ticket Submitted!',
                    text: 'Your issue has been reported. Support team will contact you.',
                    confirmButtonColor: '#4CAF50'
                });
            }, 200);
        </script>";
    } else {
        echo "<script>
            setTimeout(function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Failed!',
                    text: 'There was an error submitting your ticket.',
                    confirmButtonColor: '#d33'
                });
            }, 200);
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Support Ticket</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #4f8df9, #6cd4ff);
    margin: 0;
    padding: 0;
}

.support-box {
    width: 450px;
    margin: 60px auto;
    background: rgba(255,255,255,0.15);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    backdrop-filter: blur(10px);
    color: #fff;
    animation: fadeIn 0.8s ease-in-out;
}

.support-box h3, 
.support-box h2{
    color: red;
    text-align: center;
    margin-bottom: 20px;
    font-weight: 600;
    letter-spacing: 1px;
}

.support-box input,
.support-box textarea {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 15px;
    background: rgba(255,255,255,0.25);
    color: black;
    backdrop-filter: blur(5px);
    transition: all .3s ease;
}
.support-box select{
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 15px;
    background: rgba(255,255,255,0.25);
    color: black;
    backdrop-filter: blur(5px);
    transition: all .3s ease;
}

.support-box input:focus,
.support-box textarea:focus,
.support-box select:focus {
    background: rgba(255,255,255,0.35);
    box-shadow: 0 0 8px rgba(255,255,255,0.4);
}

.support-box textarea {
    resize: none;
}

.support-box button {
    width: 100%;
    padding: 12px;
    margin-top: 18px;
    background: #1fdc5e;
    color: #fff;
    font-size: 17px;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all .3s ease;
    letter-spacing: 0.5px;
}

.support-box button:hover {
    background: #0fb84a;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-15px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 520px){
    .support-box {
        width: 90%;
        margin-top: 40px;
    }
}
</style>
</head>

<body>

<div class="support-box">
    <h2>Repair 360 Support Center</h2>
    <h3>Contact Us</h3>

    <form method="post">
        <input type="text" name="username" placeholder="Enter your full name" required>

        <input type="email" name="email" placeholder="Enter your email" required>

        <input type="number" name="id" placeholder="Enter your user ID" required>

        <select name="type" required>
            <option value="" disabled selected>Select User Type</option>
            <option value="customer">Customer</option>
            <option value="mechanic">Mechanic</option>
        </select>

        <input type="text" name="subject" placeholder="Problem Subject" required>

        <textarea name="desc" placeholder="Describe your issue..." rows="5" required></textarea>

        <button type="submit" name="submit">Send Ticket</button>
    </form>
</div>

</body>
</html>
