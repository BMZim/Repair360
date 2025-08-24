<?php 
session_start();
$valid =$_SESSION['customers_id'];
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
                <!-- Service Card 1 -->
                <div class="service-card">
                    <div class="card-header">
                        <div class="avatar"><img src="img/re.png" alt=""></div>
                        <div>
                            <div class="name">GearUp Mechanics <span class="stars">★★★★★</span></div>
                            <div class="category">Car Mechanic</div>
                        </div>
                    </div>
                    <div class="expertise">
                        <strong>Expert At:</strong>
                        <ul>
                            <li>Engine Specialist</li>
                            <li>Transmission Technician</li>
                            <li>Brake and Suspension Expert</li>
                            <li>Electrical System Technician</li>
                            <li>Air Conditioning and Heating Specialist</li>
                        </ul>
                    </div>
                    <div class="hire">
                        <button class="hire-btn">Hire Now</button>
                        <button class="hire-btn">Location</button>
                    </div>
                    
                    <div class="footer">
                        <img src="img/repairlogo.png" alt="Repair360"/>
                        Expert Mechanics by Repair360
                    </div>
                </div>
                <!-- Service Card 2 -->
                <div class="service-card">
                    <div class="card-header">
                        <div class="avatar"><img src="img/re.png" alt=""></div>
                        <div>
                            <div class="name">FridgeMedix <span class="stars">★★★★★</span></div>
                            <div class="category">Refrigerator Mechanic</div>
                        </div>
                    </div>
                    <div class="expertise">
                        <strong>Expert At:</strong>
                        <ul>
                            <li>Certified Refrigerator Technician</li>
                            <li>Cooling System Specialist</li>
                            <li>Refrigeration Appliance Expert</li>
                            <li>Fridge Repair Engineer</li>
                            <li>Domestic Refrigeration Mechanic</li>
                        </ul>
                    </div>
                    <div class="hire">
                        <button class="hire-btn">Hire Now</button>
                        <button class="hire-btn">Location</button>
                    </div>
                    
                    <div class="footer">
                        <img src="img/repairlogo.png" alt="Repair360" />
                        Expert Mechanic by Repair360
                    </div>
                </div>
            </div>
           
          
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
                    <tr> 
                      <td>Refrigerator Repair</td> 
                      <td>2025-07-20</td> 
                      <td>10:30 AM</td> 
                      <td class="status confirmed">Confirmed</td> 
                    </tr> <tr> <td>AC Maintenance</td> 
                      <td>2025-07-22</td> <td>02:00 PM</td> 
                      <td class="status pending">Pending</td> 
                    </tr> 
                    <tr> 
                      <td>Smart TV Diagnosis</td> 
                      <td>2025-07-25</td> 
                      <td>04:15 PM</td> 
                      <td class="status completed">Completed</td> 
                    </tr> 
                  </tbody> 
                </table>
        </div>
        
      </div>
      <div class="tabPanel">
        <div class="track-service-head">
          <h2>Service History</h2>
        </div>
        <div class="track-service-content">
           <div class="status-card">
  <div class="status-header">
    <strong>Service:</strong> Washing Machine Repair
  </div>
  <div class="status-info">
    <p><strong>Technician:</strong> Mahfuz Rahman</p>
    <p><strong>Estimated Arrival:</strong> 11:00 AM, July 20, 2025</p>
    <p><strong>Current Status:</strong> <span class="status in-progress">In Progress</span></p>
  </div>
  <div class="status-steps">
    <ul>
      <li class="done">Booked</li>
      <li class="done">Assigned</li>
      <li class="active">On the Way</li>
      <li>Work Started</li>
      <li>Completed</li>
    </ul>
  </div>
</div>
<div class="fa-location">
    
</div>

          
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