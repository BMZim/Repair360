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
                    <button id="searchuser" type="submit">&#128269;</button>
                </form>
                <script>
// LIVE SEARCH
$(".search-bar input").on("keyup", function () {
    let keyword = $(this).val().trim();

    $.post("search_user.php", { search: keyword }, function (data) {
        $("#userTableBody").html(data);
    });
});

// Prevent form submission
$(".search-bar").on("submit", function(e){
    e.preventDefault();
});
</script>
      
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
      WHEN status = 'New User' THEN 2
      ELSE 3 
    END,
    role ASC,
    full_name ASC
";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = htmlspecialchars($row['status']);

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

        // Show Approve only if Not Verified
        if ($status === 'Not Verified') {
            echo "<button class='action-btn approve-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Approve</button>";
        }

        // If Blocked → Show Unblock only
        if ($status === 'Blocked') {
            echo "<button class='action-btn unblock-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Unblock</button>";
        } 
        // If NOT Blocked → Show Block button
        else {
            echo "<button class='action-btn block-btn' data-id='{$row['id']}' data-role='{$row['role']}'>Block</button>";
        }

        echo "
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

// BLOCK USER
$(document).on('click', '.block-btn', function(){
    const id = $(this).data('id');
    const role = $(this).data('role');
    $.post('user_action.php', {action:'block', id:id, role:role}, function(res){
        if(res.trim() === 'OK'){
            Swal.fire('Blocked!', 'User has been blocked.', 'warning')
                 .then(()=>location.reload());
        } else Swal.fire('Error', res, 'error');
    });
});

// UNBLOCK USER
$(document).on('click', '.unblock-btn', function(){
    const id = $(this).data('id');
    const role = $(this).data('role');

    $.post('user_action.php', {action:'unblock', id:id, role:role}, function(res){
        if(res.trim() === 'OK'){
            Swal.fire('Unblocked!', 'User has been unblocked.', 'success')
                 .then(()=>location.reload());
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
        
<div class="service-manage-content" style="font-family:Arial; padding:20px;">
  <!-- Search Form -->
  <form id="serviceFormSearch" style="margin-bottom:15px;">
    <input type="text" id="searchService" placeholder="Search by ID, Type or Name">
    <button type="submit">Search</button>
  </form>

  <!-- Add Form -->
  <form id="serviceForm" style="margin-bottom:20px;">
    <select id="service_type" name="service_type" required 
            style="padding:8px; border:1px solid #ccc; border-radius:4px;">
      <option value="" disabled selected>Select Type</option>
      <option value="Home">Home</option>
      <option value="Vehicle">Vehicle</option>
      <option value="Tech">Tech</option>
    </select>
    <input type="text" id="serviceName" placeholder="Service Name (e.g., AC Repair)" required />
    <button type="submit">Add Service</button>
  </form>

  <table class="service-table">
    <thead>
      <tr>
        <th >ID</th>
        <th >Service Type</th>
        <th >Service Name</th>
        <th >Actions</th>
      </tr>
    </thead>
    <tbody id="serviceTableBody">
      
    </tbody>
  </table>
</div>
<script>

function loadServices(query='') {
  $.get('service_manage_action.php', {action:'fetch', query}, function(data){
    $('#serviceTableBody').html(data);
  });
}
loadServices(); // initial load

// Add Service
$('#serviceForm').on('submit', function(e){
  e.preventDefault();
  const type = $('#service_type').val();
  const name = $('#serviceName').val();
  if(!type || !name.trim()) return alert('Please fill all fields');

  $.post('service_manage_action.php', {action:'add', service_type:type, service_name:name}, function(res){
    if(res.trim() === 'OK'){
      Swal.fire({
  title: "Good job!",
  text: "Service Added",
  icon: "success"
});
      $('#serviceName').val('');
      loadServices();
    } else alert(res);
  });
});

// Search Service
$('#serviceFormSearch').on('submit', function(e){
  e.preventDefault();
  const q = $('#searchService').val().trim();
  loadServices(q);
});

// Edit Service
$(document).on('click','.edit-btn-ser',function(){
  const id = $(this).data('id');
  const currentType = $(this).data('type');
  const currentName = $(this).data('name');
  const newType = prompt('Edit Service Type:', currentType);
  if(newType === null) return;
  const newName = prompt('Edit Service Name:', currentName);
  if(newName === null) return;
  Swal.fire({
  title: "Good job!",
  text: "Service Updated",
  icon: "success"
});
  $.post('service_manage_action.php',{action:'edit', service_id:id, service_type:newType, service_name:newName},function(res){
    if(res.trim()==='OK'){
      
      loadServices();
    } else alert(res);
  });
});

// Delete Service
$(document).on('click','.delete-btn-ser',function(){
  const id = $(this).data('id');
  if(confirm('Are you sure you want to delete this service?')){
    $.post('service_manage_action.php',{action:'delete', service_id:id},function(res){
      if(res.trim()==='OK'){
        Swal.fire({
      title: "Good job!",
      text: " Deleted Successfully",
      icon: "success"
    });
        loadServices();
      } else alert(res);
    });
  }
});
</script>

        
      </div>
      <div class="tabPanel">
        <div class="booking-head">
          <h2>Booking Overview Details</h2>
        </div>
        <div class="booking-content">
  <!-- Search Box -->
  <form id="searchBookingForm" style="margin-bottom:15px;">
    <input type="text" id="searchBooking" placeholder="Search by Customer, Mechanic, or Service ID..." 
           style="padding:8px 12px; width:250px; border:1px solid #ccc; border-radius:5px;">
    <button type="submit" style="padding:8px 14px; border:none; background:#007bff; color:white; border-radius:5px; cursor:pointer;">Search</button>
  </form>

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
      <?php
      include("config.php");

      $sql = "SELECT 
                a.appointment_id, a.appointment_date, a.appointment_time, a.status, a.description,
                c.customer_id, c.full_name AS customer_name,
                m.mechanic_id, m.full_name AS mechanic_name,
                s.service_id, s.skills AS service_name
              FROM appointments a
              JOIN customer c ON a.customer_id = c.customer_id
              JOIN mechanic m ON a.mechanic_id = m.mechanic_id
              JOIN service s ON a.service_id = s.service_id
              ORDER BY a.appointment_date DESC";

      $result = $conn->query($sql);
      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>" . htmlspecialchars($row['service_id']) . "</td>
                  <td>" . date('d M Y', strtotime($row['appointment_date'])) . "</td>
                  <td>" . htmlspecialchars($row['customer_name']) . "</td>
                  <td>" . htmlspecialchars($row['service_name']) . "</td>
                  <td>" . htmlspecialchars($row['mechanic_name']) . "</td>
                  <td><span class='badge ".strtolower($row['status'])."'>".htmlspecialchars($row['status'])."</span></td>
                  <td>
                    <button class='action-btn view-btn' onclick=\"window.open('view_booking.php?id={$row['appointment_id']}', '_blank')\">View</button>
                    <button class='action-btn cancel-btn' data-id='{$row['appointment_id']}'>Cancel</button>
                  </td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='7' style='text-align:center;'>No bookings found</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script>
//  Handle search
$('#searchBookingForm').on('submit', function(e){
  e.preventDefault();
  const query = $('#searchBooking').val().trim();

  $.ajax({
    url: 'search_booking.php',
    method: 'GET',
    data: { q: query },
    success: function(res){
      $('#bookingTableBody').html(res);
    }
  });
});

//  Cancel button
$(document).on('click', '.cancel-btn', function(){
  const id = $(this).data('id');
  if(confirm('Are you sure you want to cancel this booking?')){
    $.post('booking_action.php', {action: 'cancel', id: id}, function(res){
      if(res.trim() === 'OK'){
        alert('Booking cancelled successfully.');
        location.reload();
      } else {
        alert('Error: ' + res);
      }
    });
  }
});
</script>

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
      <h3>Total Users</h3>
      <p id="totalUsers">0</p>
    </div>

    <div class="card">
      <h3>Total Revenue</h3>
      <p id="totalRevenue">0</p>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

  fetch("admin_analytics_data.php")
    .then(res => res.json())
    .then(data => {

      // Update Overview Boxes
      document.getElementById("totalBookings").innerText = data.totalBookings;
      document.getElementById("totalUsers").innerText = data.totalUsers;
      document.getElementById("totalRevenue").innerText = "BDT " + parseFloat(data.totalRevenue).toFixed(2);

      // Prepare Monthly Booking Chart
      const bookingLabels = data.monthlyBookings.map(x => x.month);
      const bookingValues = data.monthlyBookings.map(x => x.total);

      new Chart(document.getElementById("bookingChart"), {
        type: "line",
        data: {
          labels: bookingLabels,
          datasets: [{
            label: "Bookings",
            data: bookingValues,
            fill: true,
            borderColor: "#007bff",
            backgroundColor: "rgba(0, 123, 255, 0.2)",
            tension: 0.3
          }]
        }
      });

      // Prepare Monthly Revenue Chart
      const revenueLabels = data.monthlyRevenue.map(x => x.month);
      const revenueValues = data.monthlyRevenue.map(x => x.revenue);

      new Chart(document.getElementById("revenueChart"), {
        type: "bar",
        data: {
          labels: revenueLabels,
          datasets: [{
            label: "Revenue (BDT)",
            data: revenueValues,
            backgroundColor: "#28a745",
            borderColor: "#1e7e34",
            borderWidth: 1
          }]
        }
      });

    });

});
</script>


      </div>
      <div class="tabPanel">
        <div class="payment-head">
          <h2>Payments & Invoices</h2>
        </div>
        <div class="payment-content">

  <!--Live Search Bar -->
  <div style="text-align:right; margin-bottom:15px;">
    <input type="text" id="searchInput" placeholder="Search payments…" 
           style="padding:8px; width:250px; border:1px solid #ccc; border-radius:5px;">
  </div>

  <table class="payment-table">
    <thead>
      <tr>
        <th>Payment ID</th>
        <th>Customer</th>
        <th>Mechanic</th>
        <th>Service</th>
        <th>Amount (৳)</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>

    <tbody id="paymentTableBody">
      <!-- AJAX data loads here -->
    </tbody>
  </table>

</div>

<script>
function loadPayments(search = "") {
    $.ajax({
        url: "invoice_search.php",
        type: "POST",
        data: { search: search },
        success: function(data) {
            $("#paymentTableBody").html(data);
        }
    });
}

// Load all payments initially
loadPayments();

// Live search while typing
$("#searchInput").on("keyup", function () {
    let value = $(this).val();
    loadPayments(value);
});
</script>


        </div>
      <div class="tabPanel">
        <div class="reviews-head">
          <h2>Reviews History</h2>
        </div>
        <div class="review-content">

  <h2 style="margin-bottom:15px;">All Ratings & Reviews</h2>

  <div id="reviewList" class="review-list">
      
  </div>

</div>

<script>
// Load reviews on page load
function loadReviews() {
    $.ajax({
        url: "load_reviews.php",
        type: "POST",
        success: function(data) {
            $("#reviewList").html(data);
        }
    });
}

loadReviews();

// Delete Review
$(document).on("click", ".delete-btn", function() {
    let id = $(this).data("id");
    let type = $(this).data("type");

    Swal.fire({
        title: "Delete Review?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        confirmButtonText: "Delete"
    }).then((res) => {
        if (res.isConfirmed) {
            $.post("delete_reviews.php", { id: id, type: type }, function(response) {
                if (response.trim() === "OK") {
                    Swal.fire("Deleted!", "Review removed.", "success");
                    loadReviews();
                } else {
                    Swal.fire("Error", response, "error");
                }
            });
        }
    });
});


// Flag Review
$(document).on("click", ".flag-btn", function() {
    Swal.fire("Flagged!", "Marked as reviewed by admin.", "success");
});
</script>

      </div>
      <div class="tabPanel">
        <div class="profile-head">
            <h2>Profile Information</h2>
        </div>
        <div class="profile-content">
  <div class="support-d">
<div style="display: flex; justify-content:center; gap:10px; color:red; ">
  <?php 

  include('config.php');

  $sql = "select * from service_charge";
  $result = mysqli_query($conn, $sql);

  $row = mysqli_fetch_assoc($result);




  ?>
<p>Old VAT Amount: <?= $row['vat_amount'] ?> %</p>
<p>-----</p>
      <p>Old Platform Fee: <?= $row['platform_fee'] ?> %</p>
    <!-- SUPPORT INFO FORM -->
</div>
<div class="settings-data">
  <form class="settings-form" id="supportForm">
      <label>
        Support Email:
        <input type="email" id="support_email" placeholder="support@repair360.com" required />
      </label>

      <label>
        Support Phone:
        <input type="tel" id="support_phone" placeholder="+8801XXXXXXXXX" required />
      </label>

      <button type="submit">Save Settings</button>
    </form>

    <!-- SERVICE CHARGE FORM -->
    <form class="settings-form" id="chargeForm">
      <label>
        New VAT Amount:
        <input type="number" id="vat_amount" required />
      </label>

      <label>
        New Platform Fee:
        <input type="number" id="platform_fee" required />
      </label>

      <button type="submit">Update</button>
    </form>
</div>
  </div>
</div>

<!-- AJAX LOGIC -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// SAVE SUPPORT INFO
$("#supportForm").on("submit", function(e){
    e.preventDefault();

    let email = $("#support_email").val().trim();
    let phone = $("#support_phone").val().trim();

    $.post("update_settings.php", {
        action: "update_contact",
        email: email,
        phone: phone
    }, function(res){
        if(res.trim() === "OK"){
            Swal.fire("Success!", "Support details updated.", "success");
        } else {
            Swal.fire("Error!", res, "error");
        }
    });
});


// UPDATE VAT & PLATFORM FEE
$("#chargeForm").on("submit", function(e){
    e.preventDefault();

    let vat = $("#vat_amount").val().trim();
    let fee = $("#platform_fee").val().trim();

    $.post("update_settings.php", {
        action: "update_charges",
        vat: vat,
        fee: fee
    }, function(res){
        if(res.trim() === "OK"){
            Swal.fire("Updated!", "VAT & Platform fee updated.", "success");
        } else {
            Swal.fire("Error!", res, "error");
        }
    });
});

</script>

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