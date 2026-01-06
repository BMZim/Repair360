<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Repair360 - Your Trusted Repair Partner</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body {
    background: linear-gradient(135deg, #e3f2ff, #ffffff);
    color: #222;
}

.navbar {
    width: 100%;
    padding: 15px 6%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    border-bottom-left-radius: 30px;
    border-bottom-right-radius: 30px;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.55);
    box-shadow: 0px 4px 15px rgba(0,0,0,0.08);
    z-index: 100;
}

.nav-left {
    display: flex;
    gap: 10px;
    align-items: center;
}

.nav-left img {
    width: 86px;
}

.nav-left h2 {
    font-size: 26px;
    font-weight: 700;
    color: #073b6d;
}

.nav-right ul {
    display: flex;
    gap: 20px;
    list-style: none;
    align-items: center;
}

.nav-right ul a {
    color: #073b6d;
    text-decoration: none;
    font-weight: 500;
    padding: 7px 14px;
    border-radius: 10px;
    transition: 0.3s;
}

.nav-right ul a:hover {
    background: #0b64eb;
    color: white;
}

.dropdown {
    position: relative;
}

.dropbtn {
    background: transparent;
    border: none;
    font-size: 15px;
    padding: 8px 15px;
    cursor: pointer;
    color: #073b6d;
    transition: 0.3s;
}

.dropbtn:hover {
    color: white;
    background: #0b64eb;
    border-radius: 10px;
}

.dropdown-content {
    display: none;
    position: absolute;
    background: white;
    border-radius: 10px;
    margin-top: 2px;
    width: 150px;
    box-shadow: 0px 5px 20px rgba(0,0,0,0.15);
}

.dropdown-content a {
    padding: 10px 15px;
    display: block;
    color: #073b6d;
}

.dropdown-content a:hover {
    background: #e8f2ff;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.hero-section {
    display: flex;
    justify-content: space-between;
    padding: 140px 6% 50px;
    align-items: center;
}

.hero-text {
    max-width: 550px;
}

.hero-text h1 {
    font-size: 48px;
    font-weight: 800;
    color: #073b6d;
}

.hero-text p {
    margin-top: 10px;
    font-size: 17px;
    line-height: 1.7;
}

.cta-btn {
    margin-top: 25px;
    padding: 13px 28px;
    background: #0b64eb;
    color: white;
    text-decoration: none;
    border-radius: 14px;
    font-size: 17px;
    transition: 0.3s;
    box-shadow: 0px 4px 14px rgba(0,0,0,0.18);
}

.cta-btn:hover {
    background: #0946a0;
}

/* Hero Image */
.hero-img img {
    width: 420px;
    filter: drop-shadow(0px 10px 20px rgba(0,0,0,0.3));
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-12px); }
    100% { transform: translateY(0px); }
}

.services {
    margin-top: 40px;
    text-align: center;
}

.services h2 {
    font-size: 32px;
    color: #073b6d;
}

.service-container {
    margin-top: 35px;
    padding: 0px 6%;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
    gap: 25px;
}

.service-card {
    background: white;
    padding: 25px;
    border-radius: 18px;
    text-align: left;
    box-shadow: 0px 4px 22px rgba(0,0,0,0.1);
    transition: 0.35s;
}

.service-card:hover {
    transform: translateY(-8px);
}

.service-card img {
    width: 80px;
}

.service-card h3 {
    margin-top: 12px;
    font-size: 22px;
    color: #073b6d;
}

.service-card p {
    margin-top: 7px;
    line-height: 1.6;
}
/* Base button styling */
.bookservice.glow-btn {
    display: inline-block;
    padding: 12px 28px;
    background-color: #007bff; /* Professional Blue */
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    border-radius: 50px; /* Rounded pill shape */
    transition: all 0.3s ease;
    border: 2px solid transparent;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
    position: relative;
    overflow: hidden;
}

/* The Hover Effect */
.bookservice.glow-btn:hover {
    background-color: #0056b3;
    transform: translateY(-3px); /* Lifts button up */
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.5); /* Stronger glow */
    color: #fff;
}

/* The "Glow" Shine Animation */
.bookservice.glow-btn::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255, 255, 255, 0.3),
        transparent
    );
    transition: all 0.5s;
}

.bookservice.glow-btn:hover::after {
    left: 100%;
}

/* Active/Click state */
.bookservice.glow-btn:active {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
}

.footer {
    margin-top: 60px;
    padding: 35px 6%;
    background: #073b6d;
    color: white;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.footer .col h3 {
    margin-bottom: 10px;
}
.footer .col p{
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
}

.footer .col a {
    display: block;
    color: #dfeaff;
    margin-top: 6px;
    text-decoration: none;
}

.footer .col a:hover {
    color: white;
}
.footer .social {
    display: flex;
}
.footer .social img {
    width: 34px;
    margin-right: 14px;
    transition: 0.3s;
}

.footer .social img:hover {
    transform: translateY(-4px);
}

.footer p {
    margin-top: 20px;
    text-align: center;
    color: #d0e6ff;
    font-size: 14px;
}

</style>
</head>
<body>

<div class="navbar">
    <div class="nav-left">
        <img src="img/repairlogo.png">
        <h2>Repair360</h2>
    </div>

    <div class="nav-right">
        <ul>
            <li class="dropdown">
                <button class="dropbtn">Login As <i class="fa-solid fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href="/admin/login-admin.html">Admin</a>
                    <a href="/customer/customer-login.php">Customer</a>
                    <a href="/service-provider/mechanic_login.php">Mechanic</a>
                </div>
            </li>
            <li class="dropdown">
                <button class="dropbtn">SignUp As <i class="fa-solid fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href="/admin/reg-admin.html">Admin</a>
                    <a href="/customer/customersignup.html">Customer</a>
                    <a href="/service-provider/mechanicsignup.html">Mechanic</a>
                </div>
            </li>
            <li><a href="about.html">About Us</a></li>
          <li><a href="support.php">Help Center</a></li>
        </ul>
    </div>
</div>

<div class="hero-section">
    <div class="hero-text">
        <h1>Your Trusted Repair & Service Partner</h1>
        <p>
            From vehicle repair to home appliances, we bring expert service  
            right to your doorstep. Reliable, fast & professional technicians  
            are always ready to assist — anytime, anywhere.
        </p><br>
        <a href="/customer/customer-dashboard.php" class="cta-btn">Book a Service</a>
    </div>

    <div class="hero-img">
        <img src="img/mechanic.png">
    </div>
</div>

<section class="services">
    <h2>Our Services</h2>

    <div class="service-container">

        <div class="service-card">
            <img src="img/car-re.png">
            <h3>Vehicle Repair</h3>
            <p>Engine, battery, brake, AC & full servicing by expert mechanics.</p><br>
            <a  href="/customer/customer-dashboard.php" class="bookservice glow-btn">Book Now</a>
        </div>

        <div class="service-card">
            <img src="img/all.png">
            <h3>Home Appliances</h3>
            <p>Fridge, AC, TV, fan, washing machine & more — we fix everything!</p><br>
            <a  href="/customer/customer-dashboard.php" class="bookservice glow-btn">Book Now</a>
        </div>

        <div class="service-card">
            <img src="img/booking.png">
            <h3>Instant Booking</h3>
            <p>Book a mechanic anytime with smooth, quick, and easy scheduling.</p><br>
            <a  href="/customer/customer-dashboard.php" class="bookservice glow-btn">Hire Now</a>
        </div>
        <div class="service-card">
            <img src="img/mechanic.png"> <h3>Become a Partner</h3>
            <p>Flexible hours, steady work, and the freedom to grow your own repair business.</p><br>
            <a href="/service-provider/mechanic-dashboard.php" class="bookservice glow-btn">Join Now</a>
        </div>

    </div>
</section>

<div class="footer">
    <div class="footer-content">

        <div class="col">
            <h3>Quick Links</h3>
            <a href="about.html">Developers</a>
            <a href="support.php">Help Center</a>
        </div>

        <div class="col">
            <h3>Contact Us</h3>
             <?php
            include('conn.php');

            $sql = "select * from contact";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
          ?>
          <p><img src="img/call.png" width="25"><?= $row['phone'] ?></p>
          <p><img src="img/gmail.png" width="25"> <?= $row['email'] ?></p>
        </div>

        <div class="col">
            <h3>Follow Us</h3>
            <div class="social">
                <a href="#"><img src="img/fb.png"></a>
                <a href="#"><img src="img/insta.png"></a>
                <a href="#"><img src="img/twitter.png"></a>
                <a href="#"><img src="img/in.png"></a>
            </div>
        </div>

    </div>

    <p>© 2025 Repair360. All Rights Reserved.</p>
</div>

</body>
</html>
