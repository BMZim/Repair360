
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Repair360 - Professional Multi-Service Repair Platform</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
/* ---------------- GOOGLE FONTS ---------------- */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* ---------------- RESET ---------------- */
*{
  font-family: "Poppins", sans-serif;
  margin:0; padding:0;
  box-sizing:border-box;
}
body{
  background: linear-gradient(to top, #ffffff 0%, #93c0ed 100%);
  overflow-x:hidden;
}

/* ---------------- PRELOADER ---------------- */
.preloader{
  position:fixed;
  top:50%; left:50%;
  transform:translate(-50%, -50%);
  font-size:3.5rem;
  font-weight:700;
  color:#00ff5e;
  text-shadow:0 0 25px rgba(0,255,140,0.8);
  animation: pulse 1.2s infinite alternate;
  z-index:999999;
}
.preloader::after{
  content:"Repair - 360";
  position:absolute;
  left:0; top:0;
  color:#ff4141;
  width:0%;
  overflow:hidden;
  white-space:nowrap;
  animation: fill 3s forwards;
}

@keyframes pulse{
  from{transform:translate(-50%, -50%) scale(1);}
  to{transform:translate(-50%, -50%) scale(1.1);}
}
@keyframes fill{
  from{width:0%;}
  to{width:100%;}
}

/* ---------------- NAVBAR ---------------- */
.header{
  position:fixed;
  width:92%;
  left:50%;
  transform:translateX(-50%);
  top:20px;
  padding:12px 25px;
  border-radius:45px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  background:rgba(255,255,255,0.25);
  backdrop-filter:blur(10px);
  box-shadow:0 4px 12px rgba(0,0,0,0.2);
  z-index:9999;
}
.header img{
  width:65px;
}
.header .left-nav{
  display:flex;
  align-items:center;
  gap:12px;
}
.header .right-nav ul{
  display:flex;
  gap:25px;
  list-style:none;
}
.header .right-nav ul li a,
.dropbtn{
  text-decoration:none;
  font-weight:500;
  font-size:15px;
  border: none;
  cursor: pointer;
  background-color: transparent;
  color: rgb(12, 12, 12);
  transition:.3s;
}

.header .right-nav li:hover a{
  color:#ffffff;
}

/* DROPDOWN */
.dropdown{
    position:relative;

}
.dropdown-content{
  display: none;
  position: absolute;
  background-color: #177f9e;
  backdrop-filter: blur(7px);
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgb(16, 16, 16);
  z-index: 1;
  border-radius: 10px;
}
.dropdown-content a{
  display:block;
  padding:10px 15px;
  color:black;
  transition: all .3s ease;
}
.dropdown-content a:hover{
    transform: translateX(5px);
}
.dropdown:hover .dropdown-content{
  display:block;
}

/* ---------------- HERO SECTION ---------------- */
.hero{
  margin-top:150px;
  padding:70px 10%;
  display:flex;
  justify-content:space-between;
  align-items:center;
  animation: fadeUp 1.5s ease;
}
.hero img{
  width:350px;
  animation: float 3s infinite alternate ease-in-out;
}
@keyframes fadeUp{
  from{opacity:0; transform:translateY(40px);}
  to{opacity:1; transform:translateY(0);}
}
@keyframes float{
  from{transform:translateY(0px);}
  to{transform:translateY(-20px);}
}

.hero-text p{
  font-size:17px;
  line-height:27px;
}
#re-name{
  font-size:38px;
  font-weight:700;
}
#moto{
  color:red;
  text-shadow:0 0 10px red;
  font-size:20px;
}

/* ---------------- SERVICE SECTIONS ---------------- */
.section-title{
  text-align:center;
  margin-top:80px;
  font-size:34px;
  font-weight:700;
}

.service-box{
  margin:60px auto;
  width:88%;
  padding:30px;
  border-radius:30px;
  background:rgba(0,0,0,0.4) url(img/car-=bg.png);
  background-blend-mode:overlay;
  background-size:cover;
  display:flex;
  gap:30px;
  justify-content:space-between;
  color:white;
  box-shadow:0 8px 20px rgba(0,0,0,0.3);
  animation: fadeUp 1.5s ease;
}
@keyframes fadeUp{
  from{opacity:0; transform:translateY(40px);}
  to{opacity:1; transform:translateY(0);}
}
.service-box img{
  width:340px;
}
.service-box p{
  font-size:16px;
}
.glow-btn{
  display:inline-block;
  padding:10px 22px;
  background:#00ff4c;
  color:black;
  border-radius:12px;
  font-weight:600;
  margin-top:15px;
  box-shadow:0 0 12px #00ff6a;
  text-decoration:none;
  transition:.3s;
}
.glow-btn:hover{
  background:#ff4d4d;
  color:white;
  box-shadow:0 0 15px #ff4d4d;
  transform:translateY(-3px);
}

/* ---------------- FOOTER ---------------- */
.footer{
  background:white;
  margin-top:100px;
  padding:50px 8%;
  box-shadow:0 -4px 15px rgba(0,0,0,0.2);
}
.footer-content{
  display:flex;
  justify-content:space-between;
  flex-wrap:wrap;
}
.footer .quick-links ul{
  list-style:none;
}
.footer .quick-links li a{
  text-decoration:none;
  color:black;
  font-weight:500;
}
.footer .quick-links li:hover a{
  color:#0071ff;
}

.social-media img{
  width:40px;
  margin-right:15px;
  transition:.3s;
}
.social-media img:hover{
  transform:scale(1.15);
}

.contact p{
  display:flex;
  align-items:center;
  margin-top:12px;
  gap:10px;
}

.footer-bottom{
  text-align:center;
  padding:15px;
  margin-top:20px;
  border-top:1px solid #ccc;
  font-size:14px;
}

/* ---------------- RESPONSIVE ---------------- */
@media(max-width:900px){
  .hero{
    flex-direction:column;
    text-align:center;
  }
  .service-box{
    flex-direction:column;
    text-align:center;
  }
  .service-box img{
    margin:auto;
  }
}
</style>
</head>

<body>

<!-- PRELOADER -->
<div class="preloader" id="preloader">Repair - 360</div>

<!-- MAIN CONTENT -->
<div id="content" style="display:none;">

  <!-- NAVBAR -->
  <div class="header">
    <div class="left-nav">
      <img src="img/repairlogo.png">
      <h2>Repair360</h2>
    </div>

    <div class="right-nav">
      <ul>
        <li>
          <div class="dropdown">
            <button class="dropbtn">Login As <i class="fa-solid fa-caret-down"></i></button>
            <div class="dropdown-content">
              <a href="/admin/login-admin.html">Admin</a>
              <a href="/customer/customer-login.php">Customer</a>
              <a href="/service-provider/mechanic_login.php">Mechanic</a>
            </div>
          </div>
        </li>

        <li>
          <div class="dropdown">
            <button class="dropbtn">SignUp As <i class="fa-solid fa-caret-down"></i></button>
            <div class="dropdown-content">
              <a href="/admin/reg-admin.html">Admin</a>
              <a href="/customer/customersignup.html">Customer</a>
              <a href="/service-provider/mechanicsignup.html">Mechanic</a>
            </div>
          </div>
        </li>

        <li><a href="#">Help Center</a></li>
        <li><a href="about.html">About Us</a></li>
      </ul>
    </div>
  </div>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-text">
      <p><strong id="re-name">Welcome to Repair - 360</strong><br><br>
        Your trusted destination for <strong>car, home appliance, and mechanic services</strong> — anytime, anywhere.<br><br>
        We provide expert repair solutions for refrigerators, ACs, TVs, fans, vehicles, and more.<br>
        <strong id="moto">Fast • Reliable • Professional</strong>
      </p>
    </div>

    <img src="img/mechanic.png">
  </section>

  <h1 class="section-title">Our Services <i class="fa-solid fa-screwdriver-wrench"></i></h1>

  <div class="service-box">
    <div>
      <p><strong>Top-Notch Car Repair Services</strong><br><br>
        Engine problems? Brake issues? Battery dead?<br>
        Repair360 provides fast, reliable, and affordable car servicing anytime. <br>
        Whether it's engine trouble, brake issues, battery replacement, or regular servicing — we provide fast, reliable, and affordable car repair solutions.
                      Our experienced mechanics are ready to diagnose and fix your vehicle with precision and care.
                      Stay safe on the road — trust Repair360 for professional automotive repair services anytime, anywhere!
      </p>
      <a href="/customer/customer-login.php" class="glow-btn">Book Now</a>
    </div>
    <img src="img/car-re.png">
  </div>

  <div class="service-box">
    <div>
      <p><strong>Appliance Repair Made Easy</strong><br><br>
        Refrigerator, AC, TV, fan, washing machine — our expert technicians fix everything at your doorstep. <br>
        From refrigerators that won't cool, TVs that won't turn on, noisy fans, faulty washing machines, to air conditioners that need servicing — Repair360 has you covered.
                      Our team of certified technicians brings fast, reliable, and affordable repair services right to your doorstep. <br><br>
                      <strong>Enjoy hassle-free service and extend the life of your appliances with Repair360!</strong>
      </p>
      <a href="/customer/customer-login.php" class="glow-btn">Book Now</a>
    </div>
    <img src="img/all.png">
  </div>

  <div class="service-box">
    <div>
      <p><strong>Easily book top-rated mechanics at the best rates No matter what kind of repair help you need, </strong> <br> <br>
                  — car, appliances, or home services — get instant quotes from trusted local experts through the professionals at Repair360. <br><br>

                  ✔️ Simple, upfront pricing. <br>
                  ✔️ Excellent customer support. <br>
                  ✔️ Guaranteed professional service.
      </p>
      <a href="/customer/customer-login.php" class="glow-btn">Hire Now</a>
    </div>
    <img src="img/booking.png">
  </div>

  <footer class="footer">
    <div class="footer-content">

      <div class="quick-links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="about.html">About Us</a></li>
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Developers</a></li>
        </ul>
      </div>

      <div>
        <div class="social-media">
          <a href="#"><img src="img/fb.png"></a>
          <a href="#"><img src="img/insta.png"></a>
          <a href="#"><img src="img/twitter.png"></a>
          <a href="#"><img src="img/in.png"></a>
        </div>
        <div class="contact">
          <?php
            include('conn.php');

            $sql = "select * from contact";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
          ?>
          <p><img src="img/call.png" width="25"><?= $row['phone'] ?></p>
          <p><img src="img/gmail.png" width="25"> <?= $row['email'] ?></p>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      &copy; 2025 Repair - 360. All rights reserved.
    </div>
  </footer>

</div>

<script>
setTimeout(() => {
  document.getElementById('preloader').style.display = "none";
  document.getElementById('content').style.display = "block";
}, 3400);

const faders = document.querySelectorAll(".service-box");
const appear = new IntersectionObserver((entries)=>{
  entries.forEach(e=>{
    if(e.isIntersecting){
      e.target.style.opacity=1;
      e.target.style.transform="translateY(0px)";
    }
  })
},{threshold:0.3});

faders.forEach(f=>{
  f.style.opacity=0;
  f.style.transform="translateY(60px)";
  appear.observe(f);
});
</script>

</body>
</html>
