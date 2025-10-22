<?php
include("config.php");

if (!isset($_GET['id'])) {
  echo "<p>Invalid booking ID.</p>";
  exit;
}

$appointment_id = intval($_GET['id']);

$sql = "SELECT 
          a.appointment_id, a.appointment_date, a.appointment_time, a.status, a.description,
          c.customer_id, c.full_name AS customer_name, c.email AS customer_email, c.phone AS customer_phone,
          m.mechanic_id, m.full_name AS mechanic_name, m.email AS mechanic_email, m.phone AS mechanic_phone, s.shop_location,
          s.service_id, s.skills AS service_name
        FROM appointments a
        JOIN customer c ON a.customer_id = c.customer_id
        JOIN mechanic m ON a.mechanic_id = m.mechanic_id
        JOIN service s ON a.service_id = s.service_id
        WHERE a.appointment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
  echo "<p>No booking details found.</p>";
  exit;
}

$data = $result->fetch_assoc();
$location = explode(",", $data['shop_location']);
$latitude = trim($location[0] ?? '');
$longitude = trim($location[1] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Details #<?= htmlspecialchars($data['appointment_id']); ?></title>
<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f8f9fb;
  margin: 0;
  padding: 40px;
}
.container {
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  max-width: 900px;
  margin: auto;
}
h2 {
  text-align: center;
  color: #333;
  margin-bottom: 20px;
}
.details-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}
.details-table th {
  text-align: left;
  padding: 10px;
  width: 25%;
  background: #f3f4f6;
  border-bottom: 1px solid #e5e7eb;
  color: #333;
}
.details-table td {
  padding: 10px;
  border-bottom: 1px solid #e5e7eb;
}
iframe {
  width: 100%;
  height: 300px;
  border-radius: 8px;
  border: none;
}
.badge {
  padding: 6px 12px;
  border-radius: 20px;
  color: white;
  font-weight: 600;
}
.badge.pending { background: #facc15; }
.badge.confirmed { background: #3b82f6; }
.badge.completed { background: #10b981; }
.badge.cancelled { background: #ef4444; }
.back-btn {
  display: inline-block;
  background: #3b82f6;
  color: white;
  padding: 10px 16px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s;
}
.back-btn:hover {
  background: #2563eb;
}
</style>
</head>
<body>
  <div class="container">
    <h2>Booking Details #<?= htmlspecialchars($data['appointment_id']); ?></h2>
    <a href="admin-dashboard.php" class="back-btn">← Back to Dashboard</a>
    <br><br>

    <table class="details-table">
      <tr><th>Appointment ID:</th><td><?= htmlspecialchars($data['appointment_id']); ?></td></tr>
      <tr><th>Date:</th><td><?= date('d M Y', strtotime($data['appointment_date'])); ?></td></tr>
      <tr><th>Time:</th><td><?= htmlspecialchars($data['appointment_time']); ?></td></tr>
      <tr><th>Status:</th><td><span class="badge <?= strtolower($data['status']); ?>"><?= htmlspecialchars($data['status']); ?></span></td></tr>
      <tr><th>Description:</th><td><?= htmlspecialchars($data['description']); ?></td></tr>
    </table>

    <h3>Customer Details</h3>
    <table class="details-table">
      <tr><th>ID:</th><td><?= htmlspecialchars($data['customer_id']); ?></td></tr>
      <tr><th>Name:</th><td><?= htmlspecialchars($data['customer_name']); ?></td></tr>
      <tr><th>Email:</th><td><?= htmlspecialchars($data['customer_email']); ?></td></tr>
      <tr><th>Phone:</th><td><?= htmlspecialchars($data['customer_phone']); ?></td></tr>
    </table>

    <h3>Mechanic Details</h3>
    <table class="details-table">
      <tr><th>ID:</th><td><?= htmlspecialchars($data['mechanic_id']); ?></td></tr>
      <tr><th>Name:</th><td><?= htmlspecialchars($data['mechanic_name']); ?></td></tr>
      <tr><th>Email:</th><td><?= htmlspecialchars($data['mechanic_email']); ?></td></tr>
      <tr><th>Phone:</th><td><?= htmlspecialchars($data['mechanic_phone']); ?></td></tr>
    </table>

    <h3>Service Details</h3>
    <table class="details-table">
      <tr><th>Service ID:</th><td><?= htmlspecialchars($data['service_id']); ?></td></tr>
      <tr><th>Service Name:</th><td><?= htmlspecialchars($data['service_name']); ?></td></tr>
    </table>

    <h3>Mechanic Shop Location</h3>
    <?php if ($latitude && $longitude): ?>
      <iframe src="https://www.google.com/maps?q=<?= $latitude; ?>,<?= $longitude; ?>&hl=es;z=14&output=embed"></iframe>
    <?php else: ?>
      <p>No location available for this mechanic.</p>
    <?php endif; ?>
  </div>
</body>
</html>
