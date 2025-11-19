<?php
include("config.php");

$search = $_POST['search'] ?? '';
$like = "%$search%";

$sql = "
    SELECT 
        p.payment_id,
        p.amount,
        p.status,
        a.appointment_id,
        c.full_name AS customer_name,
        m.full_name AS mechanic_name,
        s.skills AS service_name
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    JOIN customer c ON a.customer_id = c.customer_id
    JOIN mechanic m ON a.mechanic_id = m.mechanic_id
    JOIN service s ON a.service_id = s.service_id
    WHERE 
        p.payment_id LIKE ? OR
        c.full_name LIKE ? OR
        m.full_name LIKE ? OR
        s.skills LIKE ?
    ORDER BY p.payment_id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $like, $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        echo "<tr>
            <td>{$row['payment_id']}</td>
            <td>".htmlspecialchars($row['customer_name'])."</td>
            <td>".htmlspecialchars($row['mechanic_name'])."</td>
            <td>".htmlspecialchars($row['service_name'])."</td>
            <td>".number_format($row['amount'], 2)."</td>
            <td><span class='status ".strtolower($row['status'])."'>".$row['status']."</span></td>
            <td>
                <a href='admin_invoice_pdf.php?pid={$row['payment_id']}' target='_blank'>
                    <button class='download-btn'>Download</button>
                </a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='7' style='text-align:center; color:#999;'>No results found</td></tr>";
}
?>
