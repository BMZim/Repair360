<?php
require_once __DIR__ . '/../vendor/autoload.php';
include("config.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Validate payment_id
$payment_id = intval($_GET["payment_id"]);

if (!$payment_id) {
    die("Invalid Payment ID");
}

// Fetch data for invoice
$sql = "
    SELECT 
        p.payment_id, p.amount, p.status, p.transaction_id, p.created_at,
        a.appointment_id, a.appointment_date,
        c.full_name AS customer_name, c.phone AS customer_phone, c.email AS customer_email,
        m.full_name AS mechanic_name, m.phone AS mechanic_phone, m.email AS mechanic_email,
        s.skills AS service_name
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    JOIN customer c ON a.customer_id = c.customer_id
    JOIN mechanic m ON a.mechanic_id = m.mechanic_id
    JOIN service s ON a.service_id = s.service_id
    WHERE p.payment_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Invoice data not found.");
}

$invoice_html = '
<style>
body { font-family: DejaVu Sans, sans-serif; }
.invoice-box {
    max-width: 750px;
    padding: 20px;
    margin: auto;
    border: 1px solid #eee;
    font-size: 14px;
    line-height: 20px;
}
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.table th {
    background: #f5f5f5;
    padding: 10px;
    border: 1px solid #ddd;
}
.table td {
    padding: 10px;
    border: 1px solid #ddd;
}
.header-title {
    font-size: 28px;
    font-weight: bold;
    color: #333;
}
.section-title {
    font-size: 18px;
    margin-top: 20px;
    font-weight: bold;
}
</style>

<div class="invoice-box">
    <div class="header-title">Repair360 — Service Invoice</div>
    <p><strong>Invoice ID:</strong> '.$data["payment_id"].'</p>
    <p><strong>Generated On:</strong> '.$data["created_at"].'</p>

    <div class="section-title">Customer Details</div>
    <table class="table">
        <tr><td><strong>Name:</strong></td><td>'.$data["customer_name"].'</td></tr>
        <tr><td><strong>Email:</strong></td><td>'.$data["customer_email"].'</td></tr>
        <tr><td><strong>Phone:</strong></td><td>'.$data["customer_phone"].'</td></tr>
    </table>

    <div class="section-title">Mechanic Details</div>
    <table class="table">
        <tr><td><strong>Name:</strong></td><td>'.$data["mechanic_name"].'</td></tr>
        <tr><td><strong>Email:</strong></td><td>'.$data["mechanic_email"].'</td></tr>
        <tr><td><strong>Phone:</strong></td><td>'.$data["mechanic_phone"].'</td></tr>
    </table>

    <div class="section-title">Service Details</div>
    <table class="table">
        <tr><td><strong>Service:</strong></td><td>'.$data["service_name"].'</td></tr>
        <tr><td><strong>Appointment Date:</strong></td><td>'.$data["appointment_date"].'</td></tr>
        <tr><td><strong>Transaction ID:</strong></td><td>'.$data["transaction_id"].'</td></tr>
        <tr><td><strong>Status:</strong></td><td>'.$data["status"].'</td></tr>
    </table>

    <div class="section-title">Payment Summary</div>
    <table class="table">
        <tr><th>Description</th><th>Amount (BDT)</th></tr>
        <tr><td>Service Charge</td><td>'.$data["amount"].'</td></tr>
        <tr><td><strong>Total</strong></td>
            <td><strong>'.$data["amount"].'</strong></td></tr>
    </table>
</div>
';

// DOMPDF SETTINGS
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($invoice_html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("Invoice_{$payment_id}.pdf", ["Attachment" => true]);
