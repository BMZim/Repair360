<?php 
session_start();
$valid =$_SESSION['id'];
if($valid == true){

}else{
  header("location:mechanic_login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="map.js" defer></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="mechanic-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />   
</head>
<body onload="getLocation()">
  <div class="grid-container">
    <div class="item1">
      <div class="head-left">
        <img src="img/repairlogo.png" alt="">
        <h1>Repair-360 <sup style="text-transform: none; font-weight: 300; font-size: 15px;"> Service Provider</sup></h1><br>
        
      </div>
      <div class="head-right">
        <a href="#"><img src="img/bell.png" alt=""></a>
        <a href="#"><img src="img/ChatGPT Image May 1, 2025, 11_29_51 AM.png" alt=""></a>
      </div>
      

    </div>
  
    <div class="item2">
        <button onclick="showPanel(0, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-screwdriver-wrench"></i> Assigned Jobs</button>
        <button onclick="showPanel(1, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-calendar-check"></i> My Schedule</button>
        <button onclick="showPanel(2, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-check-to-slot"></i> Job History</button>
        <button onclick="showPanel(3, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-route"></i> Route & Map</button>
        <button onclick="showPanel(4, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-message"></i> Messages</button>
        <button onclick="showPanel(5, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-money-bill"></i> Earnings & Payments</button>
        <button onclick="showPanel(6, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-gears"></i> My Profile</button>
        <button onclick="showPanel(7, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-star"></i> Ratings & Reviews</button>
        <button onclick="showPanel(8, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-gear"></i> Service Settings</button>
        <button onclick="showPanel(9, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-truck"></i> Emergency Services</button>
        <button onclick="logOut()"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
      </div>
  
    <div class="item3">
      <div class="tabPanel" id="tab1">
        <div class="job-head">
                <h2>Service List</h2>
                <form class="search-bar">
                    <input type="text" placeholder="Search a Service" />
                    <button type="submit">&#128269;</button>
                </form>
            
        </div>
        <div class="job-content">
          <div class="job-card">
      <h3>Refrigerator Repair</h3>
      <p><strong>Customer:</strong> Mr. Arif Rahman</p>
      <p><strong>Address:</strong> House 12, Road 5, Dhanmondi, Dhaka</p>
      <p><strong>Date & Time:</strong> 20 July 2025, 11:00 AM</p>
      <p><strong>Description:</strong> Fridge not cooling properly</p>
      <div class="action-buttons">
        <button class="accept-btn">Accept</button>
        <button class="decline-btn">Decline</button>
      </div>
    </div>

    <div class="job-card">
      <h3>AC Maintenance</h3>
      <p><strong>Customer:</strong> Ms. Nusrat Jahan</p>
      <p><strong>Address:</strong> Bashundhara R/A, Block C, Dhaka</p>
      <p><strong>Date & Time:</strong> 21 July 2025, 3:00 PM</p>
      <p><strong>Description:</strong> Annual AC service requested</p>
      <div class="action-buttons">
        <button class="accept-btn">Accept</button>
        <button class="decline-btn">Decline</button>
      </div>
    </div>
        </div>
      </div>
      <div class="tabPanel" id="tab2">
        <div class="appointment-head">
          <h2>Appointments Details</h2>
        </div>
        <div class="appointment-content">
          <table class="schedule-table">
      <thead>
        <tr>
          <th>Service</th>
          <th>Date</th>
          <th>Time</th>
          <th>Customer</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>AC Maintenance</td>
          <td>2025-07-21</td>
          <td>03:00 PM</td>
          <td>Nusrat Jahan</td>
          <td class="status confirmed">Confirmed</td>
        </tr>
        <tr>
          <td>Fridge Repair</td>
          <td>2025-07-22</td>
          <td>10:00 AM</td>
          <td>Tanvir Alam</td>
          <td class="status pending">Pending</td>
        </tr>
        <tr>
          <td>TV Wall Mount</td>
          <td>2025-07-23</td>
          <td>01:00 PM</td>
          <td>Raihan Islam</td>
          <td class="status completed">Completed</td>
        </tr>
      </tbody>
    </table>
        </div>
        
      </div>

      <div class="tabPanel" id="tab3">
        <div class="track-service-head">
          <h2>Service History</h2>
        </div>
        <div class="track-service-content">
            <table class="history-table">
      <thead>
        <tr>
          <th>Service</th>
          <th>Date</th>
          <th>Customer</th>
          <th>Status</th>
          <th>Feedback</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>AC Servicing</td>
          <td>2025-07-12</td>
          <td>Nazmul Hasan</td>
          <td><span class="badge completed">Completed</span></td>
          <td>Quick service, very satisfied!</td>
        </tr>
        <tr>
          <td>Fan Repair</td>
          <td>2025-07-09</td>
          <td>Jannat Ara</td>
          <td><span class="badge canceled">Canceled</span></td>
          <td>Service canceled by customer.</td>
        </tr>
        <tr>
          <td>Fridge Maintenance</td>
          <td>2025-07-05</td>
          <td>Rifat Karim</td>
          <td><span class="badge completed">Completed</span></td>
          <td>Excellent work and polite behavior.</td>
        </tr>
      </tbody>
    </table>
          
        </div>
      </div>
      <div class="tabPanel" id="tab4">
        <div class="location-head">
          <h2>Service Location</h2>
        </div>
        <div class="location-content">
          <div id="map"></div>
      
        </div>

      </div>
      <div class="tabPanel" id="tab5">
        <div class="chat-head">
          <h2>Chat With Customer</h2>
        </div>
        <div class="chat-content">
            <div class="chat-box" id="chatBox">
      <div class="message received">Hello! My fridge isn't cooling properly.</div>
      <div class="message sent">Thanks for contacting Repair360. When did it stop working?</div>
    </div>

    <div class="input-area">
      <input type="text" id="messageInput" placeholder="Type your message..." />
      <button onclick="sendMessage()">Send</button>
    </div>

        </div>
        </div>
      <div class="tabPanel" id="tab6">
        <div class="payment-head">
          <h2>Payment Information</h2>
        </div>
        <div class="payment-content">
           <div class="summary-boxes">
      <div class="summary-box">
        <h3>Total Earnings</h3>
        <p>৳ 18,250</p>
      </div>
      <div class="summary-box">
        <h3>Pending Payments</h3>
        <p>৳ 2,000</p>
      </div>
    </div>

    <table class="payments-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Service</th>
          <th>Amount</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>2025-07-15</td>
          <td>AC Maintenance</td>
          <td>৳ 1,500</td>
          <td><span class="badge paid">Paid</span></td>
        </tr>
        <tr>
          <td>2025-07-14</td>
          <td>Refrigerator Repair</td>
          <td>৳ 2,000</td>
          <td><span class="badge pending">Pending</span></td>
        </tr>
        <tr>
          <td>2025-07-13</td>
          <td>TV Mounting</td>
          <td>৳ 1,200</td>
          <td><span class="badge paid">Paid</span></td>
        </tr>
      </tbody>
    </table>
    <div class="online-withdraw">
      <h3>Withdraw Your Money Via</h3>
      <div class="money">
        <div class="bkash">
          <a href="#">Bkash</a>
        </div>
        <div class="rocket">
          <a href="#">Rocket</a>
        </div>
      </div>
        
    </div>

        </div>
      </div>
      <div class="tabPanel" id="tab7">
        <div class="profile-head">
            <h2>Profile Information</h2>
        </div>
        <div class="profile-content">
             <form class="profile-form" id="profileForm">
      <div class="profile-pic-section">
        <img src="https://via.placeholder.com/120" alt="Profile Photo" id="profilePhoto">
        <input type="file" id="uploadPhoto" accept="image/*">
      </div>

      <label for="name">Full Name</label>
      <input type="text" id="name" placeholder="e.g. Hasan Kabir" required>

      <label for="email">Email</label>
      <input type="email" id="email" placeholder="e.g. hasan@example.com" required>

      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" placeholder="01XXXXXXXXX" required>

      <label for="location">Location</label>
      <input type="text" id="location" placeholder="e.g. Bashundhara, Dhaka" required>

      <label for="services">Service Specialization</label>
      <select id="services" required>
        <option value="">-- Select --</option>
        <option value="AC Repair">AC Repair</option>
        <option value="Refrigerator Repair">Refrigerator Repair</option>
        <option value="TV Installation">TV Installation</option>
        <option value="Washing Machine Repair">Washing Machine Repair</option>
        <option value="Fan Repair">Fan Repair</option>
      </select>

      <button type="submit">Save Changes</button>
    </form>
                           
              </div>
            </div>
      <div class="tabPanel" id="tab8">
        <div class="review-head">
          <h2>My Reviews</h2>
        </div>
        <div class="review-content">
        
          <div class="review-list" id="reviewList">
      <div class="review-item">
        <div class="stars">★★★★★</div>
        <p><strong>Arif Rahman:</strong> Very punctual and professional. Highly recommended!</p>
      </div>
      <div class="review-item">
        <div class="stars">★★★★☆</div>
        <p><strong>Nusrat Jahan:</strong> Good service, but arrived 15 minutes late.</p>
      </div>
    </div>

    <!-- Submit Review -->
    <div class="review-form">
      <h3>Leave a Review</h3>
      <label for="reviewer">Your Name:</label>
      <input type="text" id="reviewer" placeholder="e.g. Hasan Karim" required />

      <label for="rating">Rating:</label>
      <select id="rating">
        <option value="5">★★★★★ (5)</option>
        <option value="4">★★★★☆ (4)</option>
        <option value="3">★★★☆☆ (3)</option>
        <option value="2">★★☆☆☆ (2)</option>
        <option value="1">★☆☆☆☆ (1)</option>
      </select>

      <label for="comment">Review:</label>
      <textarea id="comment" rows="4" placeholder="Write your feedback..." required></textarea>

      <button onclick="submitReview()">Submit Review</button>
       <script src="rating.js"></script>
    </div>

            </div>
        </div>
        <div class="tabPanel" id="tab9">
        <div class="service-setting-head">
          <h2>Service Settings</h2>
        </div>
        <div class="service-setting-content">
          <p style="color: red;">"Please add or update your service settings while you are at your shop; otherwise, customers will not be able to see your shop location."</p>
          <form class="settings-form" method="POST">
      <h3>Select Services Offered:</h3>

      <div class="service-option">
        <label>Shop Name:</label>
        <input type="text" name="shopName" id="shopName" placeholder="Enter your shop name"><br>
        
      </div>

      <div class="service-option">
        <label>Mechanic Type:</label>
        <select id="mechanic_type" name="mechanic_type">
        
         <?php
          include('connection.php');

          $id = $_SESSION['id'];

          $sql = "select mechanic_type from mechanic_skills where mechanic_id = '$id'";
          $result = mysqli_query($con, $sql);

          if(mysqli_num_rows($result)>0){
                  $row = $result->fetch_assoc();
                  echo '<option value="'.$row['mechanic_type'].'">'.$row['mechanic_type'].'</option>';
                }else{
                  echo '0';
                }

          ?>  
        
      </select>
      </div>
      <div class="service-option">
        <label for="specific">Specify Category:</label>

        
         <?php
          include('connection.php');

          $id = $_SESSION['id'];

          $sql = "select skill_name from mechanic_skills where mechanic_id = '$id'";
          $result = mysqli_query($con, $sql);

          $mechanic = $result->fetch_assoc();
          $skills = explode(",", $mechanic['skill_name']);

          ?>  

        <select name="skills" id="skills">
        <?php foreach ($skills as $skill): ?>
        <option value="<?= htmlspecialchars(trim($skill)); ?>">
            <?= htmlspecialchars(trim($skill)); ?>
        </option>
        <?php endforeach; ?>
      </select>

      </div>

      <div class="service-option">
        <label for="skills">Expert Areas:</label>
        <input type="text" id="expert" name="expert" placeholder="e.g. Certified Refrigerator Technician, Cooling System Specialist (Max 5 Skils)" required>
      </div>

      <div>
        <label for="shoplocation">Shop Location:</label>
        <p id="shoplocation"></p>
          
          
          <script>

             function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
      } else {
        Swal.fire({
                                title: "Opps!!",
                                text: "Geolocation is not supported by this browser.",
                                icon: "error"
                                });
        document.getElementById("shoplocation").innerHTML = "Geolocation is not supported by this browser.";
      }
    }

    function showPosition(position) {
      const latitude = position.coords.latitude;
      const longitude = position.coords.longitude;

      document.getElementById("shoplocation").innerHTML = + latitude + "," + longitude;
    }

    function showError(error) {
      switch(error.code) {
        case error.PERMISSION_DENIED:
          Swal.fire({
                                title: "Opps!!",
                                text: "User denied the request for Shop Location. Please allow the location and refresh the page",
                                icon: "error"
                                });
                                alert("User denied the request for Shop Location.");
          break;
        case error.POSITION_UNAVAILABLE:
          Swal.fire({
                                title: "Opps!!",
                                text: "Location information is unavailable.",
                                icon: "error"
                                });
          alert("Location information is unavailable.");
          break;
        case error.TIMEOUT:
          Swal.fire({
                                title: "Opps!!",
                                text: "The request to get user location timed out.",
                                icon: "error"
                                });
          alert("The request to get user location timed out.");
          break;
        case error.UNKNOWN_ERROR:
          Swal.fire({
                                title: "Opps!!",
                                text: "An unknown error occurred.",
                                icon: "error"
                                });
          alert("An unknown error occurred.");
          break;
      }
    }


          </script>

      </div>
      <label for="location">Coverage Area:</label>
      <input type="text" id="coverage" placeholder="e.g. Uttara, Dhaka" required />
      <button type="submit" name="submitService" id="submitService">Save Settings</button>
    </form>
              <script>
                $(document).ready(function () {
    function serviceSetting() {

        var sname = $('#shopName').val();
        var mechanic_type = $('select[name="mechanic_type"]').val();
        var skills = $('select[name="skills"]').val();
        var expert = $('#expert').val();

        // remove spaces after commas
        expert = expert.replace(/,\s+/g, ',');

        // trim leading/trailing spaces
        expert = expert.trim();

        var shoplocation = $('#shoplocation').text();
        var coverage = $('#coverage').val();
        if (sname &&  expert && coverage  !== "") {
                        $.ajax({
                            url: "service.php",
                            method: "POST",
                            data: { sname: sname,
                                    mechanic_type: mechanic_type,
                                    skills: skills,
                                    expert: expert,
                                    shoplocation: shoplocation,
                                    coverage: coverage,
                             },
                            success: function(data) {
                              if(data.trim() ==  "1"){
                                Swal.fire({
                                title: "Nice!",
                                text: "Service Posted",
                                icon: "success"
                                });
                              }else{
                                alert(data);
                                Swal.fire({
                                title: "Opps!!",
                                text: "Error occured",
                                icon: "error"
                                });
                              }
                              
                           
                }
            });
        } else {
            Swal.fire({
                                title: "Opps!!",
                                text: "Fill all fields!!",
                                icon: "error"
                                });
        }
    }


    $("#submitService").on("click", function (e) {
        e.preventDefault(); // Prevent form submission
        serviceSetting();
    });
});
              </script>



            </div>
        </div>
    <div class="tabPanel" id="tab10">
        <div class="emergency-head">
          <h2>Emergency Service List</h2>
        </div>
        <div class="emergency-content">

        </div>
      </div>


      <div class="tabPanel" id="tab11">
        <script>
           function logOut(){
            window.location.href = "mechanic-logout.php";
           }
        </script>
      </div>
    </div>
  
    <div class="item4">
        <p id="copyright">&copy; <span id="year"></span> Repair360. All rights reserved.</p>
    </div>
  </div>
  <script src="mechanic-dashboard.js"></script>
  <script>
    // Set the current year dynamically
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
</body>
</html>