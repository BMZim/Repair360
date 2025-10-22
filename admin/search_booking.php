<?php
include("config.php");

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "SELECT 
          a.appointment_id, a.appointment_date, a.status,
          c.full_name AS customer_name,
          m.full_name AS mechanic_name,
          s.service_id, s.skills AS service_name
        FROM appointments a
        JOIN customer c ON a.customer_id = c.customer_id
        JOIN mechanic m ON a.mechanic_id = m.mechanic_id
        JOIN service s ON a.service_id = s.service_id
        WHERE c.full_name LIKE ? 
           OR m.full_name LIKE ? 
           OR s.service_id LIKE ?
        ORDER BY a.appointment_date DESC";

$stmt = $conn->prepare($sql);
$searchTerm = "%$q%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
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
  echo "<tr><td colspan='7' style='text-align:center;'>No results found</td></tr>";
}
?>
