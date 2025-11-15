<?php
include("connection.php");

if (!isset($_GET['pid'])) {
    die("Invalid Invoice Request");
}

$pid = $_GET['pid'];

// Fetch invoice details
$sql = "SELECT 
            p.payment_id,
            p.amount,
            p.status,
            p.created_at,
            a.appointment_id,
            a.appointment_date,
            a.description,
            s.skills AS service_name,
            s.fee AS service_fee,
            m.full_name AS mechanic_name,
            s.shop_name,
            c.full_name AS customer_name,
            c.phone AS customer_phone,
            c.address AS customer_address
        FROM payments p
        JOIN appointments a ON p.appointment_id = a.appointment_id
        JOIN service s ON a.service_id = s.service_id
        JOIN mechanic m ON p.mechanic_id = m.mechanic_id
        JOIN customer c ON a.customer_id = c.customer_id
        WHERE p.payment_id = ?";
        
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $pid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invoice Not Found");
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice - Repair 360</title>
<style>
body { font-family: Arial, sans-serif; padding: 20px; }
.invoice-box {
    max-width: 750px;
    margin: auto;
    padding: 25px;
    border: 2px solid #eee;
    border-radius: 10px;
    background: #fff;
}
h2 {
    text-align: center;
    color: #1a73e8;
    margin-bottom: 5px;
}
h3 { margin-top: 0; }
.section-title { font-weight: bold; margin-top: 15px; }
.download-btn {
    padding: 8px 16px;
    background: #1a73e8;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    margin-top: 15px;
}
</style>
</head>

<body>

<div class="invoice-box">

    <h2>Repair 360</h2>
    <h3><?= htmlspecialchars($data['shop_name']); ?></h3>
    <hr>

    <p class="section-title">Invoice Details</p>
    <p><strong>Invoice ID:</strong> #<?= $data['payment_id']; ?></p>
    <p><strong>Appointment Date:</strong> <?= $data['appointment_date']; ?></p>
    <p><strong>Payment Status:</strong> <?= $data['status']; ?></p>

    <p class="section-title">Customer Information</p>
    <p><strong>Name:</strong> <?= $data['customer_name']; ?></p>
    <p><strong>Phone:</strong> <?= $data['customer_phone']; ?></p>
    <p><strong>Address:</strong> <?= $data['customer_address']; ?></p>

    <p class="section-title">Service Information</p>
    <p><strong>Service:</strong> <?= $data['service_name']; ?></p>
    <p><strong>Description:</strong> <?= $data['description']; ?></p>
    <p><strong>Amount:</strong> ৳ <?= number_format($data['amount'], 2); ?></p>

    <a href="invoice_pdf.php?pid=<?= $pid ?>" class="download-btn">Download PDF</a>

</div>

</body>
</html>
