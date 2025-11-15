<?php
require_once __DIR__ . '/../vendor/autoload.php';
include("connection.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Get Payment ID
$pid = $_GET['pid'];

// Query Invoice Details
$sql = "SELECT 
            p.payment_id,
            p.amount,
            p.status,
            p.created_at,
            p.transaction_id,
            a.appointment_id,
            a.appointment_date,
            a.appointment_time,
            a.description,
            s.skills AS service_name,
            s.fee AS service_fee,
            s.shop_name,
            m.full_name AS mechanic_name,
            m.phone AS mechanic_phone,
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
$data = $result->fetch_assoc();

// Format
$invoiceDate = date("d M, Y", strtotime($data['created_at']));
$serviceDate = date("d M, Y", strtotime($data['appointment_date']));
$serviceTime = date("h:i A", strtotime($data['appointment_time']));

// HTML Template
$html = "
<style>
    body { font-family: DejaVu Sans, sans-serif; color:#333;}
    .invoice-box {
        width: 100%;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
    }
    .top-header {
        text-align:center;
        padding-bottom:10px;
        border-bottom:2px solid #1a73e8;
    }
    .title {
        font-size:32px;
        color:#1a73e8;
        font-weight:700;
    }
    .shop-name {
        font-size:18px;
        font-weight:600;
        margin-top:-10px;
    }
    table { width:100%; border-collapse: collapse; margin-top:20px; }
    td, th { padding:10px; border:1px solid #ddd; font-size:12px; }
    th { background:#1a73e8; color:white; }
    .summary-box {
        background:#f1f8ff; padding:15px; border-radius:8px;
        margin-top:20px; font-size:12px;
    }
    .footer-note {
        text-align:center; margin-top:30px; font-size:12px; color:#777;
    }
</style>

<div class='invoice-box'>

    <div class='top-header'>
        <div class='title'>Repair 360</div>
        <div class='shop-name'>{$data['shop_name']}</div>
    </div>

    <h3 style='margin-top:20px;'>Invoice Details</h3>

    <table>
        <tr>
            <th>Invoice ID</th>
            <td>#{$data['payment_id']}</td>
        </tr>
        <tr>
            <th>Transaction ID</th>
            <td>{$data['transaction_id']}</td>
        </tr>
        <tr>
            <th>Invoice Date</th>
            <td>{$invoiceDate}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{$data['status']}</td>
        </tr>
    </table>

    <h3 style='margin-top:20px;'>Customer Details</h3>
    <table>
        <tr>
            <th>Name</th>
            <td>{$data['customer_name']}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{$data['customer_phone']}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td>{$data['customer_address']}</td>
        </tr>
    </table>

    <h3 style='margin-top:20px;'>Mechanic Details</h3>
    <table>
        <tr>
            <th>Name</th>
            <td>{$data['mechanic_name']}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{$data['mechanic_phone']}</td>
        </tr>
    </table>

    <h3 style='margin-top:20px;'>Service Details</h3>
    <table>
        <tr>
            <th>Service Name</th>
            <td>{$data['service_name']}</td>
        </tr>
        <tr>
            <th>Service Date</th>
            <td>{$serviceDate} at {$serviceTime}</td>
        </tr>
        <tr>
            <th>Payment Date</th>
            <td>{$data['created_at']}</td>
        </tr>
        <tr>
            <th>Service Fee</th>
            <td>Taka {$data['service_fee']}</td>
        </tr>
    </table>

    <div class='summary-box'>
        <strong>Total Paid Amount:</strong>  
        <span style='float:right;'>{$data['amount']} Taka</span>
    </div>

    <div class='footer-note'>
        Thank you for using <strong>Repair 360</strong>.  
        This invoice is system generated and requires no signature.
    </div>

</div>
";

// PDF Options
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Download the PDF
$dompdf->stream("invoice_{$pid}.pdf");
?>
