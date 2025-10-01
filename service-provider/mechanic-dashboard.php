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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="mechanic-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />   
</head>
<body>
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
        <button onclick="showPanel(3, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-check-to-slot"></i> Track service Confirmation</button>
        <button onclick="showPanel(4, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-circle-check"></i> Apply for Verification</button>
        <button onclick="showPanel(5, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-message"></i> Messages</button>
        <button onclick="showPanel(6, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-money-bill"></i> Earnings & Payments</button>
        <button onclick="showPanel(7, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-gears"></i> My Profile</button>
        <button onclick="showPanel(8, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-star"></i> Ratings & Reviews</button>
        <button onclick="showPanel(9, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)'), getLocation()"><i class="fa-solid fa-gear"></i> Service Settings</button>
        <button onclick="showPanel(10, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)')"><i class="fa-solid fa-truck"></i> Emergency Services</button>
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
<?php
include("connection.php");

$mechanic_id = $_SESSION['id']; // Logged-in mechanic

$verify = "select status from mechanic where mechanic_id = '$mechanic_id'";
$is_verified = mysqli_query($con, $verify);
        $status = mysqli_fetch_assoc($is_verified);
        if($status['status'] === "Verified"){
            $sql = "SELECT 
            a.appointment_id,
            a.appointment_date,
            a.appointment_time,
            a.description,
            c.customer_id,
            c.full_name AS customer_name,
            c.address AS customer_address,
            s.shop_name AS service_name
        FROM appointments a
        JOIN customers c ON a.customer_id = c.customer_id
        JOIN service s ON a.service_id = s.service_id
        WHERE a.mechanic_id = ? AND a.status = 'Pending'
        ORDER BY a.appointment_date, a.appointment_time ASC";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $mechanic_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dateTime = date("d M Y, h:i A", strtotime($row['appointment_date']." ".$row['appointment_time']));
        $appointment_id = $row['appointment_id'];
        $customer_id = $row['customer_id'];
        ?>
        <div class="job-list">
          <div class="job-card">
            <h3><?= htmlspecialchars($row['service_name']); ?></h3>
            <p><strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($row['customer_address']); ?></p>
            <p><strong>Date & Time:</strong> <?= $dateTime; ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($row['description']); ?></p>
            <div class="action-buttons">
                <form method="post" action="update_appointments.php" style="display:inline;">
                    <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
                    <input type="hidden" name="status" value="Confirmed">
                    <button type="submit" class="accept-btn">Accept</button>
                </form>
                <form method="post" action="update_appointments.php" style="display:inline;">
                    <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
                    <input type="hidden" name="status" value="Cancelled">
                    <button type="submit" class="decline-btn">Decline</button>
                </form>
            </div>
          </div>
          <div style="display: flex; flex-direction:column; text-align:center; justify-content:center;">
            <span style='font-size:50px;'>&#8594;</span>
            <p>See customer location</p>
          </div>
          

          <!-- Customer live location -->
          <div class="job-location" style="margin-top:10px;">
              <iframe id="map-<?= $appointment_id ?>" src="" width="100%" height="225" style="border:0;" allowfullscreen></iframe>
          </div>
        </div>

        <script>
        function updateCustomerLocation<?= $appointment_id ?>() {
            fetch("live_location_customer.php?customer_id=<?= $customer_id ?>")
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const lat = data.latitude;
                    const lng = data.longitude;
                    document.getElementById("map-<?= $appointment_id ?>").src =
                       "https://www.google.com/maps?q=" + lat + "," + lng + "&hl=es;z=14&output=embed";
                }
            });
        }
        // Run immediately and then every 10s
        updateCustomerLocation<?= $appointment_id ?>();
        setInterval(updateCustomerLocation<?= $appointment_id ?>, 10000);
        </script>
        
        <?php
    }
} else {
    echo "<p>No jobs assigned yet.</p>";
}
        }else{
          echo '<div class="verify">
                <h3 style="color: red; font: weight 300px; text-align:center;">Your account is not verified, Please apply for verification then you can post your service in Service setting option.</h3>
          </div>';
        }

?>
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
      <?php
      include("connection.php");
      $mechanic_id = $_SESSION['id']; // logged in mechanic id

      $sql = "SELECT 
                  s.skills AS service_name,
                  a.appointment_date,
                  a.appointment_time,
                  a.status,
                  c.full_name AS customer_name
              FROM appointments a
              JOIN service s ON a.service_id = s.service_id
              JOIN customers c ON a.customer_id = c.customer_id
              WHERE a.mechanic_id = ?
              ORDER BY a.appointment_date ASC, a.appointment_time ASC";

      $stmt = $con->prepare($sql);
      $stmt->bind_param("s", $mechanic_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $date = date("Y-m-d", strtotime($row['appointment_date']));
              $time = date("h:i A", strtotime($row['appointment_time']));
              $statusClass = strtolower($row['status']); // e.g. confirmed, pending, completed

              echo "<tr>
                      <td>" . htmlspecialchars($row['service_name']) . "</td>
                      <td>" . $date . "</td>
                      <td>" . $time . "</td>
                      <td>" . htmlspecialchars($row['customer_name']) . "</td>
                      <td class='status {$statusClass}'>" . htmlspecialchars($row['status']) . "</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No Appointments Found</td></tr>";
      }
      ?>
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
        <div class="job-details-head">
          <h2>Customer Confirmation</h2>
        </div>
        <div class="job-details-content">
      <?php
include("connection.php");

$mechanic_id = $_SESSION['id'];

$sql = "SELECT 
            a.appointment_id,
            a.appointment_date,
            a.appointment_time,
            a.description,
            c.full_name AS customer_name,
            c.address,
            s.shop_name AS service_name,
            ts.status AS track_status
        FROM appointments a
        JOIN customers c ON a.customer_id = c.customer_id
        JOIN service s ON a.service_id = s.service_id
        LEFT JOIN track_status ts ON a.appointment_id = ts.appointment_id
        WHERE a.mechanic_id = ? AND a.status = 'Confirmed'
        ORDER BY a.appointment_date, a.appointment_time ASC";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $mechanic_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="job-card">
            <h3><?= htmlspecialchars($row['service_name']); ?></h3>
            <p><strong>Customer:</strong> <?= htmlspecialchars($row['customer_name']); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($row['address']); ?></p>
            <p><strong>Date & Time:</strong> <?= date("d M Y, h:i A", strtotime($row['appointment_date']." ".$row['appointment_time'])); ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($row['description']); ?></p>
            
            <!-- Tracking form -->
           <form method="post" class="track-form" 
      style="border:1px solid #ccc; padding:15px; border-radius:8px; background:#f9f9f9; margin-top:15px; width:100%; max-width:420px; font-family:Arial, sans-serif;">
    <input type="hidden" class="appointment_id" name="appointment_id" value="<?= $row['appointment_id']; ?>">
    <input type="hidden" class="mechanic_id" name="mechanic_id" value="<?= $mechanic_id; ?>">

    <?php if ($row['track_status'] == "" || $row['track_status'] == "Pending") { ?>
        <label style="display:block; margin-bottom:6px; font-weight:bold; color:#333;">Estimated Arrival Time:</label>
        <input type="datetime-local" class="estimated_arrival" name="estimated_arrival" required 
               style="width:100%; padding:8px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;">

        <label style="display:block; margin-bottom:6px; font-weight:bold; color:#333;">Current Status:</label>
        <input type="text" class="current_status" name="current_status" placeholder="e.g. Traffic delay, leaving now" required
               style="width:100%; padding:8px; margin-bottom:12px; border:1px solid #ccc; border-radius:4px;">

        <button type="button" class="btn-way" 
                style="width:100%; padding:10px; border:none; border-radius:4px; background-color:#38a169; color:white; font-weight:bold; cursor:pointer; transition:all 0.3s ease;">
            On The Way
        </button>
    <?php } elseif ($row['track_status'] == "On the Way") { ?>
        <button type="button" class="btn-started" 
                style="width:100%; padding:10px; border:none; border-radius:4px; background-color:#f6ad55; color:white; font-weight:bold; cursor:pointer; transition:all 0.3s ease;">
            Work Started
        </button>
    <?php } elseif ($row['track_status'] == "Work Started") { ?>
        <button type="button" class="btn-complete" 
                style="width:100%; padding:10px; border:none; border-radius:4px; background-color:#3182ce; color:white; font-weight:bold; cursor:pointer; transition:all 0.3s ease;">
            Completed
        </button>
    <?php } ?>
</form>



            <script>
               $(document).ready(function () {
    // On The Way
    $(document).on("click", ".btn-way", function () {
        let form = $(this).closest(".track-form");
        let appointment_id = form.find(".appointment_id").val();
        let mechanic_id = form.find(".mechanic_id").val();
        let estimated_arrival = form.find(".estimated_arrival").val();
        let current_status = form.find(".current_status").val();
        let status = "On the Way";

        if (estimated_arrival && current_status !== "") {
            $.post("update_track_status.php", {
                appointment_id, mechanic_id, estimated_arrival, current_status, status
            }, function (data) {
                if (data.trim() === "Done") {
                    Swal.fire("Nice!", "You are on Track!!", "success").then(() => location.reload());
                } else {
                    Swal.fire("Opps!!", "Error occured", "error");
                }
            });
        } else {
            Swal.fire("Opps!!", "Fill all fields!!", "error");
        }
    });

    // Work Started
    $(document).on("click", ".btn-started", function () {
        let form = $(this).closest(".track-form");
        let appointment_id = form.find(".appointment_id").val();
        let mechanic_id = form.find(".mechanic_id").val();
        let status = "Work Started";

        $.post("update_track_status.php", { appointment_id, mechanic_id, status }, function (data) {
            if (data.trim() === "Done") {
                Swal.fire("Nice!", "You can start your work!!", "success").then(() => location.reload());
            } else {
                Swal.fire("Opps!!", "Error occured", "error");
            }
        });
    });

    // Completed
    $(document).on("click", ".btn-complete", function () {
        let form = $(this).closest(".track-form");
        let appointment_id = form.find(".appointment_id").val();
        let mechanic_id = form.find(".mechanic_id").val();
        let status = "Completed";

        $.post("update_track_status.php", { appointment_id, mechanic_id, status }, function (data) {
            if (data.trim() === "Done") {
                Swal.fire("Nice!", "Congratulations you completed your work!!", "success").then(() => location.reload());
            } else {
                Swal.fire("Opps!!", "Error occured", "error");
            }
        });
    });
});

              </script>

        </div>
        <?php
    }
} else {
    echo "<p>No confirmed jobs right now.</p>";
}
?>

      </div>
      </div>

      <div class="tabPanel" id="tab5">
        <div class="verification-head">
          <h2>Verify Your Account</h2>
        </div>
        <div class="verification-content">
          <form method="POST" id="verification-form">
          <?php 
            include ('connection.php');

            $mechanic_id = $_SESSION['id'];
              
            $sql = "select * from mechanic where mechanic_id ='$mechanic_id'";
            $result = mysqli_query($con, $sql);

            $rows = $result->fetch_assoc();

            echo 'Mechanic ID:<p id="mechanic_id" style="color:red; font-weight:600;">'.$rows['mechanic_id'].'</p>';
            echo 'Name: '.$rows['full_name'].'<br><br>';
            echo 'Division: '.$rows['division'].'<br><br>';
            echo 'Address: '.$rows['address'].'<br><br>';
            echo 'Phone: '.$rows['phone'].'<br><br>';
            echo 'City: '.$rows['city'].'<br><br>';
            echo 'Mechanic Type: '.$rows['mechanic_type'].'<br><br>';
            echo 'NID: '.$rows['nid'].'<br><br>';
            echo '<p style="color:red;">⚠️ Please ensure the data you entered is accurate.<br> If it\'s incorrect, update it with the correct information in profile settings.</p><br>';
            

          ?>
           <button type="submit" id="submit" name="submit" value="Apply">Apply</button>
          </form>  
          <script>
          $(document).on("submit", "#verification-form", function(e){
            e.preventDefault();

             var verify = "Verify";
             var mechanic_id = $('#mechanic_id').text();
            $.ajax({
              type: 'POST',
              url: 'verification.php',
              data : {Verify: verify, mechanic_id: mechanic_id},
            success: function(response){
              res = response.trim();
        if (res === "OK"){
          Swal.fire({
                  icon: "success",
                  title: "Verification Request Sent",
                  text: "It will take 1-7 working days!!",
                  showConfirmButton: false,
                  timer: 5000
                });
        }else{
          Swal.fire({
                  icon: "warning",
                  title: "You are Already Verified",
                  text: "Thank You",
                  showConfirmButton: false,
                  timer: 3000
                });
        }
            }
        });
          });
        </script>        
        </div>

      </div>
      <div class="tabPanel" id="tab6">
        <div class="chat-head">
          <h2>Chat With Customer</h2>
        </div>
        <div class="chat-content">
            <?php
        include("connection.php");

    if (!isset($_SESSION['id'])) {
         echo "<p>Please login.</p>";
        return;
      }
      $mechanic_id = intval($_SESSION['id']);
      ?>

        <div style="display:flex; height:560px; border:1px solid #ddd; border-radius:8px; overflow:hidden; font-family:Arial, sans-serif;">
          <div id="mechanic-chat-sidebar" style="width:32%; background:#f7f8fb; border-right:1px solid #e6e6e6; overflow-y:auto;">
            <div style="padding:12px; font-weight:700; border-bottom:1px solid #eee;">Customers (Confirmed)</div>
            <ul id="mechanic-chat-list" style="list-style:none; margin:0; padding:0;">
              <?php
      $sql = "SELECT DISTINCT a.appointment_id, c.customer_id, c.full_name, c.avatar,
                     (SELECT message FROM chat_messages cm WHERE cm.appointment_id = a.appointment_id ORDER BY cm.created_at DESC LIMIT 1) AS last_message,
                     (SELECT created_at FROM chat_messages cm WHERE cm.appointment_id = a.appointment_id ORDER BY cm.created_at DESC LIMIT 1) AS last_time
              FROM appointments a
              JOIN customers c ON a.customer_id = c.customer_id
              WHERE a.mechanic_id = ? AND a.status = 'Confirmed'
              ORDER BY last_time DESC, a.appointment_date DESC";
      $stmt = $con->prepare($sql);
      $stmt->bind_param("i", $mechanic_id);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($appointment_id, $customer_id, $customer_name, $customer_avatar, $last_message, $last_time);

      while ($stmt->fetch()) {
          $avatar = $customer_avatar ? htmlspecialchars($customer_avatar) : 'default-avatar.png';
          $snippet = $last_message ? htmlspecialchars(mb_strimwidth($last_message, 0, 40, '...')) : '';
          $time = $last_time ? date('d M H:i', strtotime($last_time)) : '';
          echo '<li class="mchat-user" data-appointment="'.intval($appointment_id).'" data-customer="'.intval($customer_id).'" style="padding:10px; display:flex; gap:10px; align-items:center; border-bottom:1px solid #f0f0f0; cursor:pointer;">';
          echo '<img src="uploads/'. $avatar .'" style="width:44px; height:44px; border-radius:50%; object-fit:cover;">';
          echo '<div style="flex:1;"><div style="font-weight:600;">'.htmlspecialchars($customer_name).'</div>';
          echo '<div style="font-size:13px; color:#666;">'. $snippet .'</div></div>';
          echo '<div style="font-size:12px; color:#999;">'. $time .'</div>';
          echo '</li>';
      }
      $stmt->close();
      ?>
    </ul>
  </div>

  <!-- Right: Chat window -->
  <div style="flex:1; display:flex; flex-direction:column; background:#fff;">
    <div id="mchat-header" style="padding:12px; border-bottom:1px solid #eee; font-weight:700;">Select a customer to chat</div>
    <div id="mchat-messages" style="flex:1; padding:12px; overflow-y:auto; background:#fefefe;"></div>

    <div style="padding:10px; border-top:1px solid #eee; display:flex; gap:8px;">
      <input id="mchat-input" type="text" placeholder="Type a message..." style="flex:1; padding:10px; border:1px solid #ddd; border-radius:20px;">
      <button id="mchat-send" style="padding:10px 18px; border-radius:20px; border:none; background:#3182ce; color:#fff; font-weight:600; cursor:pointer;">Send</button>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let activeAppointment = null;
let pollInterval = null;

// click a customer in sidebar
$(document).on('click', '.mchat-user', function () {
    $('.mchat-user').css('background','');
    $(this).css('background','#eef7ff');
    activeAppointment = $(this).data('appointment');
    const customerName = $(this).find('div > div:first').text();
    $('#mchat-header').text(customerName);

    loadMechanicChat();
    if (pollInterval) clearInterval(pollInterval);
    pollInterval = setInterval(loadMechanicChat, 3000); // poll every 3s
});

// load messages
function loadMechanicChat() {
    if (!activeAppointment) return;
    $.get('chat/load_chat_mechanic.php', { appointment_id: activeAppointment }, function (html) {
        $('#mchat-messages').html(html);
        $('#mchat-messages').scrollTop($('#mchat-messages')[0].scrollHeight);
    });
}

// send message
$('#mchat-send').on('click', function () {
    const msg = $('#mchat-input').val().trim();
    if (!msg || !activeAppointment) return;
    $.post('chat/send_chat_mechanic.php', {
        appointment_id: activeAppointment,
        message: msg
    }, function (res) {
        // optionally check response
        $('#mchat-input').val('');
        loadMechanicChat();
    });
});

// send on enter
$('#mchat-input').on('keypress', function (e) {
    if (e.which === 13 && !e.shiftKey) {
        e.preventDefault(); $('#mchat-send').click();
    }
});
</script>


        </div>
        </div>

      <div class="tabPanel" id="tab7">
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
      <div class="tabPanel" id="tab8">
        <div class="profile-head">
            <h2>Profile Information</h2>
        </div>
        <div class="profile-content">
          <form action="">
            <div class="profile-pic-section">
        <img src="" alt="Profile Photo" id="profilePhoto">
        <input type="file" id="uploadPhoto" accept="image/*">
        <button>Save</button>
      </div>
          </form>
             <form class="profile-form" id="profileForm">
      

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
      <div class="tabPanel" id="tab9">
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


        <div class="tabPanel" id="tab10">
        <div class="service-setting-head">
          <h2>Service Settings</h2>
        </div>
        <div class="service-setting-content">
         <?php 
          include('connection.php');
          $mechanic_id = $_SESSION['id']; // Logged-in mechanic

          $verify = "SELECT status FROM mechanic WHERE mechanic_id = '$mechanic_id'";
          $is_verified = mysqli_query($con, $verify);
          $status = mysqli_fetch_assoc($is_verified);

          if ($status['status'] === "Verified") { 
          ?>

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
                $id = $_SESSION['id'];
                $sql = "SELECT mechanic_type FROM mechanic_skills WHERE mechanic_id = '$id'";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $row = $result->fetch_assoc();
                    echo '<option value="'.$row['mechanic_type'].'">'.$row['mechanic_type'].'</option>';
                } else {
                    echo '<option value="">No Type Found</option>';
                }
                ?>
            </select>
        </div>

        <div class="service-option">
            <label for="specific">Specify Category:</label>
            <?php
            $sql = "SELECT skill_name FROM mechanic_skills WHERE mechanic_id = '$id'";
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
            <input type="text" id="expert" name="expert" placeholder="e.g. Certified Refrigerator Technician, Cooling System Specialist (Max 5 Skills)" required>
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
                            title: "Oops!!",
                            text: "Geolocation is not supported by this browser.",
                            icon: "error"
                        });
                        document.getElementById("shoplocation").innerHTML = "Geolocation is not supported by this browser.";
                    }
                }

                function showPosition(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    document.getElementById("shoplocation").innerHTML = latitude + "," + longitude;
                }

                function showError(error) {
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            Swal.fire({title: "Oops!!", text: "User denied the request for Shop Location.", icon: "error"});
                            break;
                        case error.POSITION_UNAVAILABLE:
                            Swal.fire({title: "Oops!!", text: "Location information is unavailable.", icon: "error"});
                            break;
                        case error.TIMEOUT:
                            Swal.fire({title: "Oops!!", text: "The request to get user location timed out.", icon: "error"});
                            break;
                        case error.UNKNOWN_ERROR:
                            Swal.fire({title: "Oops!!", text: "An unknown error occurred.", icon: "error"});
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
                expert = expert.replace(/,\s+/g, ',').trim();
                var shoplocation = $('#shoplocation').text();
                var coverage = $('#coverage').val();

                if (sname && expert && coverage !== "") {
                    $.ajax({
                        url: "service.php",
                        method: "POST",
                        data: { sname, mechanic_type, skills, expert, shoplocation, coverage },
                        success: function(data) {
                            if (data.trim() == "1") {
                                Swal.fire({title: "Nice!", text: "Service Posted", icon: "success"});
                            } else {
                                Swal.fire({title: "Oops!!", text: "Error occurred", icon: "error"});
                            }
                        }
                    });
                } else {
                    Swal.fire({title: "Oops!!", text: "Fill all fields!!", icon: "error"});
                }
            }

            $("#submitService").on("click", function (e) {
                e.preventDefault(); 
                serviceSetting();
            });
        });
    </script>

      <?php 
      } else { 
          echo '<div class="verify">
              <h3 style="color: red; text-align:center;">Your account is not verified. Please apply for verification before you can post your service.</h3>
          </div>';
      } 
      ?>

            </div>


        </div>
    <div class="tabPanel" id="tab11">
        <div class="emergency-head">
          <h2>Emergency Service List</h2>
        </div>
        <div class="emergency-content">
          <div class="emergency-toggle">
      <button class="toggle-btn active" id="showRequests">Requests</button>
      <button class="toggle-btn" id="showAppointments">Appointments</button>
  </div>
  <div id="requestsSection">
      <?php
      include("connection.php");
      $mechanic_id = $_SESSION['id']; // logged-in mechanic

      $sql = "SELECT er.id, er.customer_id, er.name, er.contact, er.location, er.service_type, er.description
              FROM emergency_requests er
              JOIN customers c ON er.customer_id = c.customer_id
              WHERE er.status = 'Pending'
              ORDER BY er.id DESC";

      $result = mysqli_query($con, $sql);

      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $loc = explode(",", $row['location']);
              $lat = trim($loc[0]);
              $lng = trim($loc[1]);
              ?>
              <div class="emergency-card">
                  <div class="emergency-details">
                      <h3>🚨 Emergency Request</h3>
                      <p><strong>Customer Name:</strong> <?= htmlspecialchars($row['name']); ?></p>
                      <p><strong>Phone No:</strong> <?= htmlspecialchars($row['contact']); ?></p>
                      <p><strong>Problem Type:</strong> <?= htmlspecialchars($row['service_type']); ?></p>
                      <p><strong>Description:</strong> <?= htmlspecialchars($row['description']); ?></p>
                      <p class="alert-warning">
                        ⚠️ Please only click <strong>Accept</strong> if you are ready to take this job.  
                        If you don’t want to proceed, kindly leave it unaccepted.
                      </p>

                      <label>Select Date:</label>
                      <input type="date" id="date_<?= $row['id']; ?>" required><br>

                      <label>Select Time:</label>
                      <input type="time" id="time_<?= $row['id']; ?>" required><br><br>

                      <button class="accept-btn" 
                          onclick="acceptRequest(<?= $row['id']; ?>, <?= $row['customer_id']; ?>, '<?= $row['service_type']; ?>', '<?= htmlspecialchars(addslashes($row['description'])); ?>')">
                          Accept
                      </button>
                  </div>
                  <div class="emergency-map">
                      <iframe src="https://www.google.com/maps?q=<?= $lat; ?>,<?= $lng; ?>&hl=es;z=14&output=embed"></iframe>
                  </div>
              </div>
          <?php
          }
      } else {
          echo "<p>No emergency requests available.</p>";
      }
      ?>
  </div>
      <!-- Emergency Appointments Section -->
  <div id="appointmentsSection" style="display:none;">
  <?php
  $sql2 = "SELECT ea.id, ea.customer_id, c.full_name, c.phone, ea.service_type, ea.description, ea.date, ea.time
           FROM emergency_appointments ea
           JOIN customers c ON ea.customer_id = c.customer_id
           WHERE ea.mechanic_id = '$mechanic_id' AND ea.status ='Confirmed'
           ORDER BY ea.id DESC";
  $res2 = mysqli_query($con, $sql2);

  if ($res2 && mysqli_num_rows($res2) > 0) {
      while ($row2 = mysqli_fetch_assoc($res2)) {
          $appointmentId = $row2['id'];
          $customerId = $row2['customer_id'];
          ?>
          <div class="appointment-card" style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
                <h3>Emergency Appointment</h3>
              <p><strong>Customer Name:</strong> <?= htmlspecialchars($row2['full_name']); ?></p>
              <p><strong>Phone No:</strong> 📞 <?= htmlspecialchars($row2['phone']); ?></p>
              <p><strong>Problem Type:</strong> <?= htmlspecialchars($row2['service_type']); ?></p>
              <p><strong>Description:</strong> <?= htmlspecialchars($row2['description']); ?></p>
              <p><strong>Appointment Date:</strong> <?= htmlspecialchars($row2['date']); ?></p>
              <p><strong>Appointment Time:</strong> <?= htmlspecialchars($row2['time']); ?></p>
            </div>
              <!-- Map Section -->
              <div class="emergency-amap" style="margin-top:15px;">
                  <iframe id="map_<?= $appointmentId; ?>" 
                          src="" 
                          width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy">
                  </iframe>
              </div>
          </div>
      

          <script>
          function refreshCustomerMap_<?= $appointmentId; ?>(){
              $.getJSON("live_location_customer.php?customer_id=<?= $customerId; ?>", function(loc){
                  if(loc.success){
                      let lat = loc.latitude;
                      let lng = loc.longitude;
                      $("#map_<?= $appointmentId; ?>").attr("src",
                          "https://www.google.com/maps?q="+lat+","+lng+"&hl=es;z=14&output=embed"
                      );
                  } else {
                      console.warn("No live location found for this customer.");
                  }
              });
          }
          
          refreshCustomerMap_<?= $appointmentId; ?>();
          setInterval(refreshCustomerMap_<?= $appointmentId; ?>, 5000);
          </script>
      <?php
      }
  } else {
      echo "<p>No emergency appointments found.</p>";
  }
  ?>
</div>

  
      </div>

<script>
function acceptRequest(requestId, customerId, serviceType, description){
    let date = $("#date_"+requestId).val();
    let time = $("#time_"+requestId).val();

    if(!date || !time){
        Swal.fire("Error!", "Please select date and time!", "error");
        return;
    }

    $.ajax({
        url: "accept_emergency.php",
        type: "POST",
        data: {
            request_id: requestId,
            customer_id: customerId,
            service_type: serviceType,
            description: description,
            appointment_date: date,
            appointment_time: time
        },
        success: function(res){
            if(res.trim() === "OK"){
                Swal.fire("Accepted!", "You have accepted this emergency request.", "success")
                .then(() => { location.reload(); });
            } else {
                Swal.fire("Error!", res, "error");
            }
        }
    });
}

document.getElementById("showRequests").addEventListener("click", function(){
    document.getElementById("requestsSection").style.display = "block";
    document.getElementById("appointmentsSection").style.display = "none";
    this.classList.add("active");
    document.getElementById("showAppointments").classList.remove("active");
});

document.getElementById("showAppointments").addEventListener("click", function(){
    document.getElementById("requestsSection").style.display = "none";
    document.getElementById("appointmentsSection").style.display = "block";
    this.classList.add("active");
    document.getElementById("showRequests").classList.remove("active");
});
</script>




      </div>


      <div class="tabPanel" id="">
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

  <script>
let mechanic_id = "<?= $_SESSION['id']; ?>";

function updateLiveLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            $.ajax({
                url: "update_live_location.php",
                method: "POST",
                data: { mechanic_id: mechanic_id, latitude: latitude, longitude: longitude },
                success: function (response) {
                    console.log("Location update:", response);
                },
                error: function () {
                    console.log("Error updating location.");
                }
            });
        }, function(error) {
            console.log("Location error:", error.message);
        });
    }
}

// Run once on load
updateLiveLocation();

// Repeat every 10 seconds
setInterval(updateLiveLocation, 10000);
</script>

</body>
</html>