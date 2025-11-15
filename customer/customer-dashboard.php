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
        <!-- Notification Button -->
<div class="notification-wrapper" style="position:relative; display:inline-block;">
  <button id="notif-btn" >
    <img src="img/bell.png" alt="Notifications">
    <span id="notif-count" 
          style="position:absolute; top:-5px; right:-5px; background:#e02424; color:#fff; border-radius:50%; font-size:11px; padding:2px 5px; display:none;">
      0
    </span>
  </button>

  <!-- Popup Notification Box -->
  <div id="notif-popup" style="
      position:absolute;
      top:40px;
      right:0;
      width:300px;
      background:#fff;
      border:1px solid #ddd;
      border-radius:10px;
      box-shadow:0 4px 12px rgba(0,0,0,0.1);
      display:none;
      z-index:999;
      overflow:hidden;">
    <div style="background:#f8f9fa; padding:10px; font-weight:bold; border-bottom:1px solid #eee;">
      Notifications
    </div>
    <div id="notif-list" style="max-height:300px; overflow-y:auto; padding:10px;">
      <p style="color:#888; text-align:center;">Loading...</p>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  const popup = $("#notif-popup");
  const count = $("#notif-count");

  $("#notif-btn").on("click", function(e){
  e.stopPropagation();
  popup.toggle();

  if(popup.is(":visible")) {
    loadNotifications();
    markAsRead(); 
  }
});
function markAsRead(){
  $.post("notification/notifications_mark_read.php", function(){
    count.hide(); 
  });
}

  $(document).on("click", function(e){
    if(!$(e.target).closest(".notification-wrapper").length){
      popup.hide();
    }
  });

  function loadNotifications(){
    $("#notif-list").html("<p style='text-align:center; color:#888;'>Loading...</p>");
    $.get("notification/notifications_fetch.php", function(data){
      $("#notif-list").html(data);
    });
  }

  function updateCount(){
    $.get("notification/notifications_count.php", function(num){
      if(num > 0){
        count.text(num).show();
      } else {
        count.hide();
      }
    });
  }

  updateCount();
  setInterval(updateCount, 15000); // every 15s
});
</script>


        <button><img src="img/ChatGPT Image May 1, 2025, 11_29_51 AM.png" alt=""></button>
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
        <button onclick="showPanel(8, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)'), getLocation()"><i class="fa-solid fa-truck"></i> Emergency Request</button>
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
            s.fee,
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
                    <img src="../uploads/<?= htmlspecialchars($row['avatar']); ?>" alt="avatar">
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
            <div class="service-cost">
                <h3 style="color: red;">Service Cost: <?= htmlspecialchars($row['fee']);?> ৳</h3>
                  
            </div>
            
            <div class="hire">
                <button class="hire-btn" onclick="openBookingModal('<?= $row['mechanic_id'] ?>', '<?= $row['service_id'] ?? '' ?>', '<?= $row['fee'] ?>')">Hire Now</button>
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
      <input type="hidden" name="fee" id="fee">
      
      
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
function openBookingModal(mechanicId, serviceId, fee) {
    document.getElementById("bookingModal").style.display = "block";
    document.getElementById("mechanic_id").value = mechanicId;
    document.getElementById("service_id").value = serviceId;
    document.getElementById("fee").value = fee;
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

            $customer_id = $_SESSION['customer_id']; // Logged-in customer

            
            $sql = "SELECT a.appointment_id, s.skills AS service_name, 
                           a.appointment_date, a.appointment_time, a.status,
                           ts.status AS track_status
                    FROM appointments a
                    JOIN service s ON a.service_id = s.service_id
                    LEFT JOIN track_status ts ON a.appointment_id = ts.appointment_id
                    WHERE a.customer_id = ?
                      AND (ts.status IS NULL OR ts.status != 'Completed')
                    ORDER BY a.appointment_date DESC, a.appointment_time DESC";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $statusClass = strtolower($row['status']); //confirmed, pending
                    echo "<tr>
                            <td>" . htmlspecialchars($row['service_name']) . "</td>
                            <td>" . htmlspecialchars($row['appointment_date']) . "</td>
                            <td>" . date('h:i A', strtotime($row['appointment_time'])) . "</td>
                            <td class='status {$statusClass}'>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No Active Appointments Found</td></tr>";
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
$sql1 = "SELECT a.appointment_id, a.status, ts.status AS track_status
         FROM appointments a
         LEFT JOIN track_status ts ON a.appointment_id = ts.appointment_id
         WHERE a.customer_id = ?
           AND a.status IN ('Pending', 'Confirmed')
           AND (ts.status IS NULL OR ts.status != 'Completed')";
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
                    m.phone,
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
            $phone = htmlspecialchars($row['phone']); 
            $arrival = $row['estimated_arrival'] ?? "Not Set";
            $current_status = $row['current_status'] ?? "Not Updated";
            $track_status = $row['track_status'] ?? "";
            $mechanic_id = $row['mechanic_id'];
            ?>
            
            <div class="track-data">
                <div class="status-card">
                  <div class="status-header">
                    Service: <?= $service; ?>
                  </div>
                  <div class="status-info">
                    <p><strong>Technician:</strong> <?= $mechanic; ?></p>
                    <p><strong>Estimated Arrival:</strong> <?= $arrival; ?></p>
                    <p><strong>Technician Phone No:</strong> <?= $phone; ?></p>
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
                            style="border:0; border-radius:8px;" allowfullscreen="" loading="lazy"></iframe>
                    
                    <button id="mapBtn-<?= $appointment_id ?>" 
                            style="margin-top:10px; padding:8px 15px; border:none; border-radius:6px; background:#2563eb; color:white; font-weight:bold; cursor:pointer; transition:0.3s;">
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
<?php
include("db.php");

if (!isset($_SESSION['customer_id'])) {
    echo "<p>Please login.</p>";
    return;
}
$customer_id = intval($_SESSION['customer_id']);
?>

<div style="display:flex; height:560px; border:1px solid #ddd; border-radius:8px; overflow:hidden; font-family:Arial, sans-serif;">

  <!-- LEFT: Mechanics List -->
  <div id="customer-chat-sidebar" style="width:32%; background:#f7f8fb; border-right:1px solid #e6e6e6; overflow-y:auto; position:relative;">
    <div style="padding:12px; font-weight:700; border-bottom:1px solid #eee;">Technicians (Confirmed)</div>
    <ul id="customer-chat-list" style="list-style:none; margin:0; padding:0;">
      <?php
      $sql = "SELECT DISTINCT a.appointment_id, m.mechanic_id, m.full_name, m.avatar,
               (SELECT message FROM chat_messages cm WHERE cm.appointment_id = a.appointment_id ORDER BY cm.created_at DESC LIMIT 1) AS last_message,
               (SELECT created_at FROM chat_messages cm WHERE cm.appointment_id = a.appointment_id ORDER BY cm.created_at DESC LIMIT 1) AS last_time
        FROM appointments a
        JOIN mechanic m ON a.mechanic_id = m.mechanic_id
        WHERE a.customer_id = ?
          AND a.status = 'Confirmed'
          AND a.appointment_id NOT IN (
              SELECT appointment_id FROM deleted_chats WHERE customer_id = ?
          )
        ORDER BY last_time DESC, a.appointment_date DESC";



      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ii", $customer_id, $customer_id);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($appointment_id, $mechanic_id, $mechanic_name, $mechanic_avatar, $last_message, $last_time);

      while ($stmt->fetch()) {
          $avatar = $mechanic_avatar ? htmlspecialchars($mechanic_avatar) : 'default-avatar.png';
          $snippet = $last_message ? htmlspecialchars(mb_strimwidth($last_message, 0, 40, '...')) : '';
          $time = $last_time ? date('d M H:i', strtotime($last_time)) : '';

          echo '<li class="chat-user" data-appointment="'.intval($appointment_id).'" data-mechanic="'.intval($mechanic_id).'" 
                    style="padding:10px; display:flex; gap:10px; align-items:center; border-bottom:2px solid #f0f0f0; cursor:pointer; position:relative;">
                    <img src="uploads/'. $avatar .'" style="width:44px; height:44px; border-radius:50%; object-fit:cover;">
                    <div style="flex:1;">
                      <div style="font-weight:600;">'.htmlspecialchars($mechanic_name).'</div>
                      <div style="font-size:13px; color:#666;">'.$snippet.'</div>
                    </div>
                    <div style="font-size:12px; color:#999;">'.$time.'</div>

                    <!-- 3-dot menu -->
                    <div class="chat-options" style="position:absolute; right:0px; top:0px;">
                      <button class="options-btn" style="background:none; border:none; font-size:18px; color:black; cursor:pointer;">⋮</button>
                      <div class="options-menu" 
                          style="display:none;  position:absolute; right: 0; top:25px; background:white; border:1px solid #ccc; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.15); z-index:999;">
                          <button class="delete-chat" 
                              data-appointment="'.intval($appointment_id).'"
                              style="background:none; display:flex; border:none; padding: 10px 12px;  text-align:left; cursor:pointer; color:#d9534f; font-weight:500;">
                               <i class="fa-solid fa-trash"></i> Delete Chat
                          </button>
                      </div>
                    </div>
                  </li>';
      }
      $stmt->close();
      ?>
    </ul>
  </div>

  <!-- RIGHT: Chat Window -->
  <div style="flex:1; display:flex; flex-direction:column; background:#fff;">
    <div id="chat-header" style="padding:12px; border-bottom:1px solid #eee; font-weight:700;">Select a technician to chat</div>
    <div id="chat-messages" style="flex:1; padding:12px; overflow-y:auto; background:#fefefe;"></div>

    <div class="send-sms" style="padding:10px; border-top:1px solid #eee; display:flex; gap:8px;">
      <input id="chat-input" type="text" placeholder="Type a message..." 
             style="flex:1; padding:10px; border:1px solid #ddd; border-radius:20px;">
      <button id="chat-send" 
              >
        Send
      </button>
    </div>
  </div>
</div>
<script>
let activeAppointment = null;
let pollInterval = null;

$(document).on('click', '.options-btn', function(e){
    e.stopPropagation();
    $('.options-menu').hide(); // hide others
    $(this).siblings('.options-menu').toggle();
});

$(document).on('click', function(){
    $('.options-menu').hide();
});

$(document).on('click', '.delete-chat', function(e){
    e.stopPropagation();
    const appointmentId = $(this).data('appointment');
    Swal.fire({
        title: 'Delete Chat?',
        text: 'This will permanently delete all messages with this technician.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Delete'
    }).then((result)=>{
        if(result.isConfirmed){
            $.post('chat/delete_chat.php', { appointment_id: appointmentId }, function(res){
                res = res.trim();
                if(res === 'OK'){
                    Swal.fire('Deleted!','Chat has been removed.','success')
                    .then(()=>location.reload());
                } else if(res === 'NOT_COMPLETED'){
                    Swal.fire('Cannot Delete Yet!','You can only delete chats after the work is marked as Completed.','info');
                } else {
                    Swal.fire('Error!', res, 'error');
                }
            });
        }
    });
});

$(".chat-user").on("click", function(){
  $('.chat-user').css('background','');
    $(this).css('background','#a8f0c6ff');
    activeAppointment = $(this).data("appointment");
     const mechanicName = $(this).find('div > div:first').text();
    $('#chat-header').text(mechanicName);

    loadMessages();
    setInterval(loadMessages, 3000); // refresh every 3s
});

// Load messages via AJAX
function loadMessages(){
    if(!activeAppointment) return;
    $.get("chat/load_chat.php", {appointment_id: activeAppointment}, function(data){
        $("#chat-messages").html(data);
        $("#chat-messages").scrollTop($("#chat-messages")[0].scrollHeight);
    });
}

// Send message
$("#chat-send").on("click", function(){
    let msg = $("#chat-input").val().trim();  
    if(msg === "" || !activeAppointment) return;

    $.post("chat/send_chat.php", {
        appointment_id: activeAppointment,
        sender_type: "customer",
        sender_id: <?= $customer_id ?>,
        message: msg
    }, function(res){
        $("#chat-input").val(""); 
        loadMessages();
    });
});

// Send with Enter key
$('#chat-input').on('keypress', function(e){
    if(e.which === 13 && !e.shiftKey){
        e.preventDefault();
        $('#chat-send').click();
    }
});
</script>
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
  <?php
include("db.php");
if (!isset($_SESSION['customer_id'])) {
  echo "<p>Please login first.</p>";
  exit;
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT a.appointment_id, s.skills AS service_name, ts.status AS track_status,
               a.fee, m.full_name AS mechanic_name, m.mechanic_id, 
               COALESCE(p.status, 'Unpaid') AS payment_status
        FROM appointments a
        JOIN track_status ts ON a.appointment_id = ts.appointment_id
        JOIN service s ON a.service_id = s.service_id
        JOIN mechanic m ON a.mechanic_id = m.mechanic_id
        LEFT JOIN payments p ON a.appointment_id = p.appointment_id
        WHERE a.customer_id = ? 
          AND ts.status = 'Completed'
          AND (p.status = 'Unpaid' OR p.status IS NULL)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    ?>
    <div class="payable-card"
         data-appointment="<?= $row['appointment_id']; ?>"
         data-service="<?= htmlspecialchars($row['service_name']); ?>"
         data-fee="<?= $row['fee']; ?>"
         data-mechanic="<?= htmlspecialchars($row['mechanic_name']); ?>"
         data-mechanicid="<?= $row['mechanic_id']; ?>">
      <p><strong>Service:</strong> <?= htmlspecialchars($row['service_name']); ?></p>
      <p><strong>Mechanic:</strong> <?= htmlspecialchars($row['mechanic_name']); ?></p>
      <p><strong>Fee:</strong> BDT <?= number_format($row['fee'],2); ?></p>
      <button class="btn-pay">Pay Now</button>
    </div>
    <?php
  }
} else {
  echo "<p>No services pending payment.</p>";
}
?>
</div>

<!-- Popup modal -->
<div id="paymentModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background: rgba(0,0,0,0.5); justify-content:center; align-items:center;">
  <div style="background:#fff; padding:20px; border-radius:8px; max-width:400px; width:90%;">
    <h3>Confirm Payment</h3>
    <p id="modalService"></p>
    <p id="modalMechanic"></p>
    <p id="modalFee"></p>
    <button id="btnProceedPay">Proceed to Pay</button>
    <button id="btnCancelPay">Cancel</button>
  </div>
</div>
<script>
$(function(){
  let selected = {};

  $(".btn-pay").on("click", function(){
    let card = $(this).closest(".payable-card");
    selected.appointment = card.data("appointment");
    selected.service = card.data("service");
    selected.fee = card.data("fee");
    selected.mechanic = card.data("mechanic");
    selected.mechanicid = card.data("mechanicid");

    $("#modalService").text("Service: " + selected.service);
    $("#modalMechanic").text("Mechanic: " + selected.mechanic);
    $("#modalFee").text("Payable Amount: BDT " + parseFloat(selected.fee).toFixed(2));

    $("#paymentModal").css("display", "flex");
  });

  $("#btnCancelPay").on("click", function(){
    $("#paymentModal").hide();
  });

  $("#btnProceedPay").on("click", function(){
    const customer_id = <?= json_encode($customer_id) ?>;
    const url = `pay_gateway.php?appointment_id=${selected.appointment}`
              + `&customer_id=${customer_id}`
              + `&mechanic_id=${selected.mechanicid}`
              + `&amount=${selected.fee}`;
    window.open(url, "_blank");
    $("#paymentModal").hide();
  });
});
</script>


      </div>
      <div class="tabPanel">
        <div class="reviews-head">
          <h2>My Reviews</h2>
        </div>
        <?php

        include("db.php");

          

        $customer_id = $_SESSION['customer_id'];
        ?>

<div class="review-content" style="display:flex; gap:40px; padding:40px; background:#f9fafb; border-radius:10px;">
  
  <!-- Review Form (Left Side) -->
  <div class="review-form" style="flex:1; background:white; padding:30px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom:20px;">Leave a Review</h2>

    <label for="service">Select Service:</label>
    <select id="service" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; margin-bottom:15px;">
      <option value="">-- Choose Service --</option>
      <?php
      $sql = "SELECT DISTINCT s.service_id, s.skills, m.mechanic_id, m.full_name
              FROM appointments a
              JOIN service s ON a.service_id = s.service_id
              JOIN mechanic m ON a.mechanic_id = m.mechanic_id
              JOIN track_status ts ON a.appointment_id = ts.appointment_id
              JOIN payments p ON a.appointment_id = p.appointment_id
              WHERE a.customer_id = ?
                AND ts.status = 'Completed'
                AND p.status = 'Paid'
                AND s.service_id NOT IN (
                    SELECT service_id FROM mechanic_rating WHERE customer_id = ?
                )";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ii", $customer_id, $customer_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<option value='{$row['service_id']}' data-mechanic='{$row['mechanic_id']}'>{$row['skills']} - {$row['full_name']}</option>";
        }
      } else {
        echo "<option value=''>No services available for review</option>";
      }
      ?>
    </select>

    <label>Rate this service:</label>
    <div class="rating" style="margin:10px 0;">
      <span class="star" data-value="1">&#9734;</span>
      <span class="star" data-value="2">&#9734;</span>
      <span class="star" data-value="3">&#9734;</span>
      <span class="star" data-value="4">&#9734;</span>
      <span class="star" data-value="5">&#9734;</span>
    </div>

    <textarea id="reviewText" rows="4" placeholder="Write your feedback..." style="width:100%; border-radius:6px; padding:10px; border:1px solid #ccc;"></textarea>
    <button id="submitReview" style="margin-top:15px; background:#10b981; color:white; border:none; padding:10px 18px; border-radius:8px; font-weight:600; cursor:pointer;">Submit Review</button>
  </div>

  <!-- Review List (Right Side) -->
  <div class="review-list" style="flex:1; background:white; padding:30px; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.1);">
    <h2>Your Reviews</h2>
    <div id="reviewList">
      <?php
      
      $review_sql = "SELECT r.rating_id, r.rating, r.review, s.skills AS service_name, m.full_name AS mechanic_name
                     FROM mechanic_rating r
                     JOIN service s ON r.service_id = s.service_id
                     JOIN mechanic m ON r.mechanic_id = m.mechanic_id
                     WHERE r.customer_id = ?
                     ORDER BY r.rating_id DESC";
      $review_stmt = $conn->prepare($review_sql);
      $review_stmt->bind_param("i", $customer_id);
      $review_stmt->execute();
      $reviews = $review_stmt->get_result();

      if ($reviews->num_rows > 0) {
        while ($r = $reviews->fetch_assoc()) {
          $stars = str_repeat("&#9733;", $r['rating']) . str_repeat("&#9734;", 5 - $r['rating']);
          echo "<div class='review-item' data-id='{$r['rating_id']}' style='margin-bottom:20px; border-bottom:1px solid #eee; padding-bottom:10px;'>
                  <p><strong>Service:</strong> {$r['service_name']}</p>
                  <p><strong>Mechanic:</strong> {$r['mechanic_name']}</p>
                  <p><span style='color:#facc15; font-size:18px;'>$stars</span></p>
                  <p>{$r['review']}</p>
                  <button class='editReview' style='background:#3b82f6; color:white; border:none; border-radius:6px; padding:5px 10px;'>Edit</button>
                  <button class='deleteReview' style='background:#ef4444; color:white; border:none; border-radius:6px; padding:5px 10px;'>Delete</button>
                </div>";
        }
      } else {
        echo "<p>No reviews yet.</p>";
      }
      ?>
    </div>
  </div>
</div>
<script>
let selectedRating = 0;

$(".star").on("click", function(){
  selectedRating = $(this).data("value");
  $(".star").html("&#9734;");
  $(this).prevAll().addBack().html("&#9733;");
});

$("#submitReview").on("click", function(){
  const service_id = $("#service").val();
  const mechanic_id = $("#service option:selected").data("mechanic");
  const review = $("#reviewText").val().trim();

  if(!service_id || selectedRating === 0 || review === ""){
    alert("Please select a service, give a rating, and write a review!");
    return;
  }

  $.post("review/submit_review.php", {
    service_id: service_id,
    mechanic_id: mechanic_id,
    rating: selectedRating,
    review: review
  }, function(res){
    alert(res);
    location.reload();
  });
});


$(document).on("click", ".editReview", function(){
  const id = $(this).closest(".review-item").data("id");
  const currentText = $(this).siblings("p:last").text();
  const newText = prompt("Edit your review:", currentText);
  if(newText !== null && newText.trim() !== ""){
    $.post("review/edit_review.php", {rating_id: id, review: newText}, function(res){
      alert(res);
      location.reload();
    });
  }
});


$(document).on("click", ".deleteReview", function(){
  const id = $(this).closest(".review-item").data("id");
  if(confirm("Are you sure you want to delete this review?")){
    $.post("review/delete_review.php", {rating_id: id}, function(res){
      alert(res);
      location.reload();
    });
  }
});
</script>

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

  <!-- Toggle Buttons -->
  <div class="emergency-toggle">
      <button class="toggle-btn active" id="showRequestForm">Request</button>
      <button class="toggle-btn" id="showMyAppointments">Appointments</button>
  </div>

  <!-- Emergency Request Form -->
  <div id="requestSection">
    <div class="emergency-container">
      <h2>🚨 Emergency Repair Request</h2>
      <p>If you're facing a critical issue that needs immediate attention, please fill out this form. Our nearest technician will respond as quickly as possible.</p>

      <form id="emergencyForm" method="post">
        <label for="name">Your Name</label>
        <input type="text" id="fullname" placeholder="Full Name" required />

        <label for="contact">Phone Number</label>
        <input type="tel" id="contact" name="contact" placeholder="01XXXXXXXXX" required />

        <label for="location">Service Location</label>
        <input type="text" id="servicelocation" name="servicelocation" required readonly />
        
        <script>
          function getLocation() {
              if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(showPosition, showError);
              } else {
                  Swal.fire({title:"Oops!!", text:"Geolocation not supported.", icon:"error"});
              }
          }
          function showPosition(position) {
              document.getElementById("servicelocation").value = 
                position.coords.latitude + "," + position.coords.longitude;
          }
          function showError(error) {
              Swal.fire({title:"Location Error", text:"Could not fetch location.", icon:"error"});
          }
        </script>

        <label for="serviceType">Problem Type</label>
        <select id="serviceType" name="serviceType" required>
          <option value="">-- Choose --</option>
          <option value="Vehicle">Vehicle</option>
          <option value="Home">Home</option>
          <option value="Tech">Tech</option>
        </select>

        <label for="description">Describe the Issue</label>
        <textarea id="description" name="description" rows="4" placeholder="Provide additional details..."></textarea>

        <button type="submit">Submit Emergency Request</button>
      </form>
    </div>
  </div>

  <!-- My Appointments -->
 <div id="appointmentsSection" style="display:none;">
    <?php
      include("db.php");
      $customer_id = $_SESSION['customer_id'];

      $sql = "SELECT ea.id, ea.service_type, ea.description, ea.date, ea.time, 
                     m.full_name AS mechanic_name, m.phone, m.mechanic_id
              FROM emergency_appointments ea
              JOIN mechanic m ON ea.mechanic_id = m.mechanic_id
              WHERE ea.customer_id = ? AND ea.status = 'Confirmed'
              ORDER BY ea.id DESC";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $customer_id);
      $stmt->execute();
      $res = $stmt->get_result();

      if ($res && $res->num_rows > 0) {
          while($row = $res->fetch_assoc()){
              $appointmentId = $row['id'];
              $mechanicId = $row['mechanic_id'];
              ?>
              <div class="appointment-card" style="display:flex; gap:20px; align-items:flex-start; flex-direction:column;">
                <!-- Appointment details -->
                <div class="appointment-details" style="flex:1;">
                  <h3>🚨 Emergency Appointment</h3>
                  <p><strong>Mechanic:</strong> <?= htmlspecialchars($row['mechanic_name']); ?></p>
                  <p><strong>Mechanic Phone No:</strong> 📞 <?= htmlspecialchars($row['phone']); ?></p>
                  <p><strong>Problem Type:</strong> <?= htmlspecialchars($row['service_type']); ?></p>
                  <p><strong>Description:</strong> <?= htmlspecialchars($row['description']); ?></p>
                  <p><strong>Date:</strong> <?= htmlspecialchars($row['date']); ?></p>
                  <p><strong>Time:</strong> <?= htmlspecialchars($row['time']); ?></p>
                </div>

                <!-- Live map -->
                <div class="appointment-map" style="flex:1; min-width:500px; height:250px;">
                  <iframe id="map_<?= $appointmentId; ?>" 
                          src="" 
                          width="100%" height="100%" style="border-radius:8px;"></iframe>
                </div>
              </div>

              <script>
          function refreshMap_<?= $appointmentId; ?>(){
              $.getJSON("get_location.php?mechanic_id=<?= $mechanicId; ?>", function(loc){
                  if(loc.success){
                      let lat = loc.latitude;
                      let lng = loc.longitude;
                      $("#map_<?= $appointmentId; ?>").attr("src",
                          "https://www.google.com/maps?q="+lat+","+lng+"&hl=es;z=14&output=embed"
                      );
                  } else {
                      console.warn("No live location found for this mechanic.");
                  }
              });
          }
          
          refreshMap_<?= $appointmentId; ?>();
          setInterval(refreshMap_<?= $appointmentId; ?>, 5000);
          </script>
              <?php
          }
      } else {
          echo "<p>No emergency appointments yet.</p>";
      }
    ?>
</div>
</div>
<script>
$(document).ready(function(){
  $("#emergencyForm").on("submit", function(e){
    e.preventDefault();

    var sname = $('#fullname').val();
    var service_type = $('select[name="serviceType"]').val();
    var contact = $('#contact').val();
    var servicelocation = $('#servicelocation').val();
    var description = $('#description').val();
    
 
    $.ajax({
      url: "submit_emergency.php",
      type: "POST",
      data: { sname, service_type, contact, servicelocation, description },
      success: function(response){
        if(response.trim() === "OK"){
          Swal.fire({
            title: "Success!",
            text: "Your emergency request has been submitted.",
            icon: "success"
          });
          $("#emergencyForm")[0].reset();
        } else if(response.trim() === "All fields are required!") {
          Swal.fire({
            title: "All fields are required!",
            text: response,
            icon: "error"
          });
        }else{
          Swal.fire({
            title: "Error!",
            text: response,
            icon: "error"
          });
        }
      },
      error: function(){
        Swal.fire({
          title: "Error!",
          text: "Could not connect to server.",
          icon: "error"
        });
      }
    });
  });

  $("#showRequestForm").on("click", function(){
      $("#requestSection").show();
      $("#appointmentsSection").hide();
      $(this).addClass("active");
      $("#showMyAppointments").removeClass("active");
  });
  $("#showMyAppointments").on("click", function(){
      $("#appointmentsSection").show();
      $("#requestSection").hide();
      $(this).addClass("active");
      $("#showRequestForm").removeClass("active");
  });
});
</script>

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
  <script>
let customer_id = "<?= $_SESSION['customer_id']; ?>";

function updateLiveLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            $.ajax({
                url: "update_live_location.php",
                method: "POST",
                data: { customer_id: customer_id, latitude: latitude, longitude: longitude },
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