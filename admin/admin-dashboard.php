<?php 
session_start();
$valid =$_SESSION['admin_id'];
if($valid == true){

}else{
  header("location:login-admin.html");
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
    <link rel="stylesheet" href="admin-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />   
</head>
<body>
  <div class="grid-container">
    <div class="item1">
      <div class="head-left">
        <img src="img/repairlogo.png" alt="">
        <h1>Repair-360 <sup style="text-transform: none; font-weight: 300; font-size: 15px;"> Admin Panel</sup></h1><br>
        
      </div>
      <div class="head-right">
        <a href="#"><img src="img/bell.png" alt=""></a>
        <a href="#"><img src="img/ChatGPT Image May 1, 2025, 11_29_51 AM.png" alt=""></a>
      </div>
      

    </div>
  
    <div class="item2">
        <button onclick="showPanel(0, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-screwdriver-wrench"></i> User Management</button>
        <button onclick="showPanel(1, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-calendar-check"></i> Service Management</button>
        <button onclick="showPanel(2, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-check-to-slot"></i></i> Booking Overview</button>
        <button onclick="showPanel(3, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-chart-simple"></i> Analytics Dashboard</button>
        <button onclick="showPanel(4, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-money-check-dollar"></i> Payments & Invoices</button>
        <button onclick="showPanel(5, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-pen-to-square"></i> Reviews</button>
        <button onclick="showPanel(6, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-gears"></i> Platform Settings</button>
        <button onclick="showPanel(7, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-headset"></i>Support Tickets</button>
        <button onclick="logOut()"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
      </div>
  
    <div class="item3">
      <div class="tabPanel" id="tab1">
        <div class="manage-user-head">
                <h2>Manage User</h2>
                <form class="search-bar">
                    <input type="text" placeholder="Search an user" />
                    <button type="submit">&#128269;</button>
                </form>
            
        </div>
        <div class="manage-user-content">
  <table class="user-table">
    <thead style="background:#f5f5f5;">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Role</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="userTableBody">
      <?php
      include("config.php");

      $sql = "
        SELECT customer_id AS id, full_name, email, phone, status, 'Customer' AS role
        FROM customer
        UNION ALL
        SELECT mechanic_id AS id, full_name, email, phone, status, 'Mechanic' AS role
        FROM mechanic
        ORDER BY 
          CASE 
            WHEN status = 'Not Verified' THEN 1 
            WHEN status = 'Verified' THEN 2 
            ELSE 3 
          END,
          role ASC,
          full_name ASC
      ";

      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $status = htmlspecialchars($row['status']);
              $isNotVerified = ($status === 'Not Verified');

              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['full_name']}</td>
                      <td>{$row['role']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['phone']}</td>
                      <td style='font-weight:600; color:" . 
                        ($status === 'Verified' ? 'green' : ($status === 'Blocked' ? 'red' : 'orange')) . ";'>
                        $status
                      </td>
                      <td>";

              if ($isNotVerified) {
                  echo "<button class='action-btn approve-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Approve</button>";
              }
              
              echo "<button class='action-btn block-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Block</button>
                    <button class='action-btn view-btn' data-id='{$row['id']}' data-role='{$row['role']}'>View</button>
                    <button class='action-btn delete-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Delete</button>
                  </td>
                </tr>";
          }
      } else {
          echo "<tr><td colspan='7' style='text-align:center; color:#999;'>No Users Found</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<script>
$(document).on('click', '.approve-btn', function(){
    const id = $(this).data('id');
    const role = $(this).data('role');
    $.post('user_action.php', {action:'approve', id:id, role:role}, function(res){
        if(res.trim() === 'OK'){
            Swal.fire('Approved!', 'User verified successfully.', 'success').then(()=>location.reload());
        } else Swal.fire('Error', res, 'error');
    });
});

$(document).on('click', '.block-btn', function(){
    const id = $(this).data('id');
    const role = $(this).data('role');
    $.post('user_action.php', {action:'block', id:id, role:role}, function(res){
        if(res.trim() === 'OK'){
            Swal.fire('Blocked!', 'User has been blocked.', 'warning').then(()=>location.reload());
        } else Swal.fire('Error', res, 'error');
    });
});

$(document).on('click', '.delete-btn', function(){
    const id = $(this).data('id');
    const role = $(this).data('role');
    Swal.fire({
        title:'Delete User?',
        text:'This will permanently delete the account.',
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#d33',
        confirmButtonText:'Delete'
    }).then(result=>{
        if(result.isConfirmed){
            $.post('user_action.php', {action:'delete', id:id, role:role}, function(res){
                if(res.trim()==='OK'){
                    Swal.fire('Deleted!','User account removed.','success').then(()=>location.reload());
                } else Swal.fire('Error', res, 'error');
            });
        }
    });
});

$(document).on('click', '.view-btn', function(){
    const id = $(this).data('id');
    const role = $(this).data('role');
    window.open('view_user.php?id=' + id + '&role=' + role, '_blank');
});
</script>


        </div>
      <div class="tabPanel" id="tab2">
        <div class="service-manage-head">
          <h2>Service Management Details</h2>
        </div>
        <div class="service-manage-content">
               <form id="serviceFormSearch">
                <input type="text" id="searchService" placeholder="Search a service" required />
      <button type="submit">Search</button>
    </form>
    <form action="#" id="serviceForm">
      <select id="service_type" name="service_type">
        <option value="">Home</option>
        <option value="">Vehicle</option>
        <option value="">Tech</option>
      </select>
      <input type="text" id="serviceName" placeholder="Service Name (e.g., AC Repair)" required />
      <button type="submit">Add Service</button>
    </form>

    <!-- Service Table -->
    <table class="service-table">
      <thead>
        <tr>
          <th>Service ID</th>
          <th>Service Type</th>
          <th>Service Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="serviceTableBody">
        <!-- Service items will appear here -->
         <td>12345</td>
         <td>Home</td>
      <td>TV Repair</td>
      <td>
        <button class="action-btn edit-btn">Edit</button>
        <button class="action-btn delete-btn">Delete</button>
      </td>
      </tbody>
    </table>
        </div>
        
      </div>
      <div class="tabPanel">
        <div class="booking-head">
          <h2>Booking Overview Details</h2>
        </div>
        <div class="booking-content">
           <table class="booking-table">
      <thead>
        <tr>
          <th>Service ID</th>
          <th>Date</th>
          <th>Customer</th>
          <th>Service</th>
          <th>Provider</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="bookingTableBody">
        <!-- Bookings will appear here -->
         <td>12345</td>
          <td>17 July 2025</td>
      <td>Zim</td>
      <td>Tv Repair</td>
      <td>Chinmoy</td>
      <td><span class="badge">Pending</span></td>
      <td>
        <button class="action-btn view-btn" >View</button>
        <button class="action-btn cancel-btn">Cancel</button>
      </td>
      </tbody>
    </table>
          
        </div>
      </div>
      <div class="tabPanel">
        <div class="analytics-head">
          <h2>Analytics Dashboard</h2>
        </div>
        <div class="analytics-content">
           <div class="overview-cards">
      <div class="card">
        <h3>Total Bookings</h3>
        <p id="totalBookings">0</p>
      </div>
      <div class="card">
        <h3>Total Revenue</h3>
        <p id="totalRevenue">৳ 0</p>
      </div>
      <div class="card">
        <h3>Total Users</h3>
        <p id="totalUsers">0</p>
      </div>
    </div>

    <div class="charts">
      <div class="chart-card">
        <h4>Monthly Bookings</h4>
        <canvas id="bookingChart"></canvas>
      </div>
      <div class="chart-card">
        <h4>Monthly Revenue</h4>
        <canvas id="revenueChart"></canvas>
      </div>
    </div>
      
        </div>

      </div>
      <div class="tabPanel">
        <div class="payment-head">
          <h2>Payments & Invoices</h2>
        </div>
        <div class="payment-content">
            <table class="payment-table">
      <thead>
        <tr>
          <th>Invoice #</th>
          <th>Customer</th>
          <th>Service</th>
          <th>Amount (৳)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="paymentTableBody">
        <td>2345434</td>
      <td>Zim</td>
      <td>AC Repair</td>
      <td>1500</td>
      <td><span class="status">Paid</span></td>
      <td><button class="download-btn">Download</button></td>
      </tbody>
    </table>

        </div>
        </div>
      <div class="tabPanel">
        <div class="reviews-head">
          <h2>Reviews History</h2>
        </div>
        <div class="review-content">
          <div id="reviewList" class="review-list">
      <!-- Reviews will be injected here -->
       <h4>Zim → Chinmoy</h4>
      <div class="stars">★☆</div>
      <p>Good</p>
      <div class="action-buttons">
        <button class="flag-btn" >OK</button>
        <button class="delete-btn">Delete</button>
      </div>
          </div>

        </div>
      </div>
      <div class="tabPanel">
        <div class="profile-head">
            <h2>Profile Information</h2>
        </div>
        <div class="profile-content">
    <form class="settings-form">

      <label>
        Support Email:
        <input type="email" placeholder="support@repair360.com" />
      </label>

      <label>
        Support Phone:
        <input type="tel" placeholder="+8801XXXXXXXXX" />
      </label>

      <button type="submit">Save Settings</button>
    </form>
                           
              </div>
            </div>
      <div class="tabPanel">
        <div class="support-head">
          <h2>User Support Center</h2>
        </div>
        <div class="support-content">
        <table class="ticket-table">
      <thead>
        <tr>
          <th>Ticket ID</th>
          <th>User</th>
          <th>Subject</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>#TCK-101</td>
          <td>Hasan Karim</td>
          <td>Payment not processing</td>
          <td><span class="status open">Open</span></td>
          <td>
            <button class="btn view">View</button>
            <button class="btn resolve">Mark Resolved</button>
          </td>
        </tr>
        <tr>
          <td>#TCK-102</td>
          <td>Tania Akter</td>
          <td>Technician arrived late</td>
          <td><span class="status pending">Pending</span></td>
          <td>
            <button class="btn view">View</button>
            <button class="btn resolve">Mark Resolved</button>
          </td>
        </tr>
        <tr>
          <td>#TCK-103</td>
          <td>Rahim Uddin</td>
          <td>Unable to login</td>
          <td><span class="status resolved">Resolved</span></td>
          <td>
            <button class="btn view">View</button>
            <button class="btn resolve" disabled>Resolved</button>
          </td>
        </tr>
      </tbody>
    </table>

            </div>
        </div>
      <div class="tabPanel">
        <script>
           function logOut(){
            window.location.href = "#";
           }
        </script>
      </div>
    </div>
  
    <div class="item4">
        <p id="copyright">&copy; <span id="year"></span> Repair360. All rights reserved.</p>
    </div>
  </div>
  <script src="admin-dashboard.js"></script>
  <script>
    // Set the current year dynamically
    document.getElementById('year').textContent = new Date().getFullYear();
  </script>
</body>
</html>