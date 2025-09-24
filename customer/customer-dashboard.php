<?php 
session_start();
$valid =$_SESSION['customer_id'];
if($valid == true){

}else{
  header("location:customer-login.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="customer-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />   
</head>
<body>
  <div class="grid-container">
    <div class="item1">
      <div class="head-left">
        <img src="img/repairlogo.png" alt="">
        <h1>Repair-360</h1>
      </div>
      <div class="head-right">
        <a href="#"><img src="img/bell.png" alt=""></a>
        <a href="#"><img src="img/ChatGPT Image May 1, 2025, 11_29_51 AM.png" alt=""></a>
      </div>
      

    </div>
  
    <div class="item2">
        <button onclick="showPanel(0, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-screwdriver-wrench"></i> Book a Service</button>
        <button onclick="showPanel(1, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-calendar-check"></i> My Appointments</button>
        <button onclick="showPanel(2, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-check-to-slot"></i></i> Track Service Status</button>
        <button onclick="showPanel(3, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-headset"></i> Chat Support</button>
        <button onclick="showPanel(4, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-money-check-dollar"></i> Payment Invoices</button>
        <button onclick="showPanel(5, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-money-check-dollar"></i> Pay Service Bill</button>
        <button onclick="showPanel(6, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-pen-to-square"></i> My Reviews</button>
        <button onclick="showPanel(7, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-gears"></i> Profile Settings</button>
        <button onclick="showPanel(8, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-truck"></i> Emergency Request</button>
        <button onclick="logOut()"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
      </div>
  
    <div class="item3">
      <div class="tabPanel" id="tab1">
        <div class="booking-head">
                <h2>Find a Service</h2>
                <form class="search-bar">
                    <input type="text" placeholder="Search a Service" />
                    <button type="submit">&#128269;</button>
                </form>
            
        </div>
        <div class="booking-content">
            <div class="service-cards">
<?php
include("db.php");

$sql = "SELECT 
            s.service_id,
            m.mechanic_id,
            m.full_name,
            m.avatar,
            s.shop_name, 
            s.skills,
            s.expert_area,
            s.shop_location,
            COALESCE(AVG(r.rating), 0) AS avg_rating
        FROM service s
        JOIN mechanic m ON s.mechanic_id = m.mechanic_id
        LEFT JOIN mechanic_rating r ON m.mechanic_id = r.mechanic_id
        GROUP BY m.mechanic_id, s.service_id";


$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert comma-separated string into array
        $skillsArray = explode(",", $row['skills']); 
        $expertArray = explode(",", $row['expert_area']); 

        // Handle shop location safely
        $latitude = $longitude = null;
        if (!empty($row['shop_location']) && strpos($row['shop_location'], ',') !== false) {
            list($latitude, $longitude) = explode(',', $row['shop_location']);
            $latitude = trim($latitude);
            $longitude = trim($longitude);
        }
        ?>
        
        <div class="service-card">
            <div class="card-header">
                <div class="avatar">
                    <img src="uploads/<?= htmlspecialchars($row['avatar']); ?>" alt="avatar">
                </div>
                <div>
                    <div class="name">
                        <?= htmlspecialchars($row['shop_name']); ?>
                        <span class="stars">
                            <?= str_repeat("★", round($row['avg_rating'])); ?>
                        </span>
                    </div>
                    <div class="category"><?= htmlspecialchars($row['skills']); ?></div>
                </div>
            </div>

            <div class="expertise">
                <strong>Expert At:</strong>
                <ul>
                    <?php foreach ($expertArray as $expert): ?>
                        <li><?= htmlspecialchars(trim($expert)); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="hire">
                <button class="hire-btn" onclick="openBookingModal('<?= $row['mechanic_id'] ?>', '<?= $row['service_id'] ?? '' ?>')">Hire Now</button>
                <?php if ($latitude && $longitude): ?>
                    <button class="hire-btn" onclick="showLocation(<?= $latitude ?>, <?= $longitude ?>)">Location</button>
                <?php endif; ?>
            </div>
            
            <div class="footer">
                <img src="img/repairlogo.png" alt="Repair360"/>
                Expert Mechanics by Repair360
            </div>
        </div>

        <?php
    }
} else {
    echo "<p>No services available right now.</p>";
}
?>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>Book Appointment</h2>
    <form id="bookingForm" method="POST" action="save_appointment.php">
      <input type="hidden" name="mechanic_id" id="mechanic_id">
      <input type="hidden" name="service_id" id="service_id">
      
      <label>Select Date:</label>
      <input type="date" name="appointment_date" required><br>
      
      <label>Select Time:</label>
      <input type="time" name="appointment_time" required><br>

      <label>Description:</label>
      <input type="text" name="description" placeholder="write a short description about your problem" required><br>
      
      <button type="submit" class="confirm-btn">Confirm Booking</button>
    </form>
  </div>
</div>

<style>
.modal {
  display: none; 
  position: fixed; 
  z-index: 1000; 
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
}
.modal-content {
  background: white;
  padding: 20px;
  width: 400px;
  margin: 10% auto;
  border-radius: 10px;
}
#bookingForm{
  display: flex;
  flex-direction: column;
}
.close {
  float: right;
  font-size: 22px;
  cursor: pointer;
}
.confirm-btn {
  background: #28a745; 
  color: white; 
  padding: 10px 15px;
  border: none; border-radius: 5px;
  cursor: pointer;
}
.confirm-btn:hover { background: #218838; }
</style>
<script>
function openBookingModal(mechanicId, serviceId) {
    document.getElementById("bookingModal").style.display = "block";
    document.getElementById("mechanic_id").value = mechanicId;
    document.getElementById("service_id").value = serviceId;
}
function closeModal() {
    document.getElementById("bookingModal").style.display = "none";
}

// Close when clicking outside
window.onclick = function(event) {
  if (event.target.classList.contains('modal')) {
    closeModal();
  }
}
</script>

<script>
function showLocation(lat, lng) {
    window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
}
</script>    
            </div>
        </div>
      <div class="tabPanel" id="tab2">
        <div class="appointment-head">
          <h2>Appointments Details</h2>
        </div>
        <div class="appointment-content">
    <table class="appointments-table">
        <thead>
            <tr>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include("db.php");
            
            $customer_id = $_SESSION['customer_id']; // Assuming logged-in customer

            $sql = "SELECT a.appointment_id, s.skills AS service_name, 
                           a.appointment_date, a.appointment_time, a.status
                    FROM appointments a
                    JOIN service s ON a.service_id = s.service_id
                    WHERE a.customer_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $statusClass = strtolower($row['status']); // e.g. confirmed, pending
                    echo "<tr>
                            <td>" . htmlspecialchars($row['service_name']) . "</td>
                            <td>" . htmlspecialchars($row['appointment_date']) . "</td>
                            <td>" . date("h:i A", strtotime($row['appointment_time'])) . "</td>
                            <td class='status {$statusClass}'>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No Appointments Found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

        
      </div>
      <div class="tabPanel">
        <div class="track-service-head">
          <h2>Service History</h2>
        </div>
        <div class="track-service-content">
<?php
include("db.php");

$customer_id = $_SESSION['customer_id'];

// Get only Pending or Confirmed appointments
$sql1 = "SELECT appointment_id, status 
         FROM appointments 
         WHERE customer_id = ? AND status IN ('Pending', 'Confirmed')";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $customer_id);
$stmt1->execute();
$res1 = $stmt1->get_result();

if ($res1->num_rows > 0) {
    while($row1 = $res1->fetch_assoc()) {
        $appointment_id = $row1['appointment_id'];
        $appt_status    = $row1['status'];

        $sql = "SELECT 
                    s.skills AS service_name,
                    m.full_name AS mechanic_name,
                    ts.estimated_arrival,
                    ts.current_status,
                    ts.status AS track_status,
                    ts.mechanic_id 
                FROM appointments a
                JOIN service s ON a.service_id = s.service_id
                JOIN mechanic m ON a.mechanic_id = m.mechanic_id
                LEFT JOIN track_status ts ON a.appointment_id = ts.appointment_id
                WHERE a.appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $service = htmlspecialchars($row['service_name']);
            $mechanic = htmlspecialchars($row['mechanic_name']);
            $arrival = $row['estimated_arrival'] ?? "Not Set";
            $current_status = $row['current_status'] ?? "Not Updated";
            $track_status = $row['track_status'] ?? "";
            $mechanic_id = $row['mechanic_id'];
            ?>
            
            <div class="track-data">
                <div class="status-card">
                  <div class="status-header">
                    <strong>Service:</strong> <?= $service; ?>
                  </div>
                  <div class="status-info">
                    <p><strong>Technician:</strong> <?= $mechanic; ?></p>
                    <p><strong>Estimated Arrival:</strong> <?= $arrival; ?></p>
                    <p><strong>Current Status:</strong> 
                        <span class="status in-progress"><?= htmlspecialchars($current_status); ?></span>
                    </p>
                  </div>
                  <div class="status-steps">
                    <ul>
                      <li class="<?= ($appt_status == 'Pending' ? 'done' : '') ?>">Booked</li>
                      <li class="<?= ($appt_status == 'Confirmed' ? 'done' : '') ?>">Assigned</li>
                      <li class="<?= ($track_status == 'On the Way' ? 'active' : 
                                      ($track_status == 'Work Started' || $track_status == 'Completed' ? 'done' : '')) ?>">On the Way</li>
                      <li class="<?= ($track_status == 'Work Started' ? 'active' : 
                                      ($track_status == 'Completed' ? 'done' : '')) ?>">Work Started</li>
                      <li class="<?= ($track_status == 'Completed' ? 'active' : '') ?>">Completed</li>
                    </ul>
                  </div>
                </div>

                <div class="fa-location" id="fa-location-<?= $appointment_id ?>">
                    <iframe id="mapFrame-<?= $appointment_id ?>" 
                            src="" 
                            width="100%" height="200" 
                            style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    
                    <button id="mapBtn-<?= $appointment_id ?>" 
                            style="margin-top:10px; padding:8px 15px; border:none; border-radius:6px; background:#3182ce; color:white; font-weight:bold; cursor:pointer;">
                        See Location in Map
                    </button>
                </div>
            </div>

            <script>
                function loadLocation<?= $appointment_id ?>() {
                    fetch("get_location.php?mechanic_id=<?= $mechanic_id ?>")
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            let lat = data.latitude;
                            let lng = data.longitude;
                            let iframe = document.getElementById("mapFrame-<?= $appointment_id ?>");
                            iframe.src = "https://www.google.com/maps?q=" + lat + "," + lng + "&hl=es;z=14&output=embed";

                            let btn = document.getElementById("mapBtn-<?= $appointment_id ?>");
                            btn.onclick = function() {
                                window.open("https://www.google.com/maps?q=" + lat + "," + lng, "_blank");
                            }
                        }
                    });
                }

                // Load immediately + refresh every 10s
                loadLocation<?= $appointment_id ?>();
                setInterval(loadLocation<?= $appointment_id ?>, 10000);
            </script>
            <?php
        }
    }
} else {
    echo "<p>No active appointments found.</p>";
}
?>
</div>
      </div>


      <div class="tabPanel">
        <div class="chat-head">
          <h2>Support Center</h2>
        </div>
        <div class="chat-content">
          <div class="chat-box">
  <div class="chat-messages" id="chatMessages">
    <div class="message left">Hello! How can we assist you today?</div>
    <div class="message right">I need help tracking my appointment.</div>
    <div class="message left">Sure! Please provide your booking ID.</div>
  </div>

  <div class="chat-input">
    <input type="text" id="messageInput" placeholder="Type your message..." />
    <button onclick="sendMessage()">Send</button>
  </div>
</div>

      
        </div>

      </div>
      <div class="tabPanel">
        <div class="payment-head">
          <h2>Payment Invoices</h2>
        </div>
        <div class="payments-content">
           <div class="payments-container"> 
             <table class="payments-table"> 
              <thead> 
                <tr> 
                  <th>Service ID</th>
                  <th>Service</th> 
                  <th>Date</th> 
                  <th>Amount</th> 
                  <th>Status</th> 
                  <th>Invoice</th> 
                </tr> 
              </thead> 
              <tbody> 
                <tr>
                  <td>012345</td> 
                  <td>AC Repair</td> 
                  <td>2025-07-10</td> 
                  <td>৳1500</td> 
                  <td class="paid">Paid</td> 
                  <td><a href="#">Download</a></td> 
                </tr>
                 <tr> 
                  <td>012345</td> 
                  <td>Fan Replacement</td> 
                  <td>2025-07-14</td> 
                  <td>৳850</td> 
                  <td class="unpaid">Unpaid</td> 
                  <td><a href="#">Generate</a></td> 
                </tr> 
                <tr> 
                  <td>012345</td> 
                  <td>TV Wall Mount</td> 
                  <td>2025-07-16</td> 
                  <td>৳1200</td> 
                  <td class="paid">Paid</td> 
                  <td><a href="#">Download</a>
                  </td> 
                </tr> 
              </tbody> 
            </table> 
          </div> 

        </div>
        </div>
        <div class="tabPanel">
        <div class="paybill-head">
          <h2>Pay Bill</h2>
        </div>
        <div class="paybill-content">


        </div>
      </div>
      <div class="tabPanel">
        <div class="reviews-head">
          <h2>My Reviews</h2>
        </div>
        <div class="review-content">
            <div class="reviews-container">
          <h2>Leave a Review</h2>

    <!-- Existing reviews list -->
    <div id="reviewList" class="review-list">
      <!-- Reviews will be added here by JavaScript -->
    </div>

    <!-- Add new review -->
    <div class="review-form">

      <label for="service">Select Service:</label>
      <select id="service">
        <option value="">-- Choose Service --</option>
        <option value="Refrigerator Repair">Refrigerator Repair</option>
        <option value="AC Maintenance">AC Maintenance</option>
        <option value="TV Setup">TV Setup</option>
      </select>

      <div class="rating">
        <span class="star" data-value="1">&#9734;</span>
        <span class="star" data-value="2">&#9734;</span>
        <span class="star" data-value="3">&#9734;</span>
        <span class="star" data-value="4">&#9734;</span>
        <span class="star" data-value="5">&#9734;</span>
      </div>

      <textarea id="reviewText" rows="4" placeholder="Write your feedback..."></textarea>
      <button onclick="submitReview()">Submit Review</button>
    </div>
  </div>

  <script src="review.js"></script>

        </div>
      </div>
      
      <div class="tabPanel">
        <div class="profile-head">
            <h2>Profile Information</h2>
        </div>
        <div class="profile-content">
            <div class="profile-container">
    <h2>Profile Settings</h2>
    <form id="profileForm" onsubmit="updateProfile(event)">
      <label for="name">Full Name</label>
      <input type="text" id="name" placeholder="Your full name" required>

      <label for="email">Email Address</label>
      <input type="email" id="email" placeholder="you@example.com" required>

      <label for="phone">Phone Number</label>
      <input type="tel" id="phone" placeholder="01XXXXXXXXX" required>

      <label for="address">Address</label>
      <textarea id="address" placeholder="Your full address" rows="3" required></textarea>

      <label for="password">New Password</label>
      <input type="password" id="password" placeholder="Leave blank to keep current password">

      <div class="button-group">
        <button type="submit">Update Profile</button>
        <input type="reset">
      </div>
    </form>
  </div>

  <script src="script.js"></script>
                           
              </div>
            </div>
      <div class="tabPanel">
        <div class="emergency-content">
             <div class="emergency-container">
    <h2>🚨 Emergency Repair Request</h2>
    <p>If you're facing a critical issue that needs immediate attention, please fill out this form. Our nearest technician will respond as quickly as possible.</p>

    <form id="emergencyForm" onsubmit="submitEmergency(event)">
      <label for="name">Your Name</label>
      <input type="text" id="name" placeholder="Full Name" required />

      <label for="contact">Phone Number</label>
      <input type="tel" id="contact" placeholder="01XXXXXXXXX" required />

      <label for="location">Service Location</label>
      <input type="text" id="location" placeholder="Full Address" required />

      <label for="serviceType">Problem Type</label>
      <select id="serviceType" required>
        <option value="">-- Choose --</option>
        <option value="AC Not Working">Veichle</option>
        <option value="Refrigerator Breakdown">Home Appliances</option>
        <option value="Fan/Light Not Running">Home Tech</option>
        <option value="Other">Other</option>
      </select>

      <label for="description">Describe the Issue</label>
      <textarea id="description" rows="4" placeholder="Provide additional details..."></textarea>

      <button type="submit">Submit Emergency Request</button>
    </form>
  </div>


            </div>
        </div>
      <div class="tabPanel">
        <script>
           function logOut(){
            window.location.href = "customer-logout.php";
           }
        </script>
      </div>
    </div>
  
    <div class="item4">
        <p id="copyright">&copy; <span id="year"></span> Repair360. All rights reserved.</p>
    </div>
  </div>
  <script src="customer-dashboard.js"></script>
  <script>
    // Set the current year dynamically
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
</body>
</html>