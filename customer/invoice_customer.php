<?php
require_once __DIR__ . '/../vendor/autoload.php';
include("db.php");

use Dompdf\Dompdf;

$pid = $_GET['pid'];

$sql = "SELECT 
            p.payment_id,
            p.amount,
            p.status,
            p.method,
            p.created_at,
            p.transaction_id,
            a.appointment_id,
            a.fee,
            a.appointment_date,
            a.appointment_time,
            a.description,
            s.skills AS service_name,
            m.full_name AS mechanic_name,
            m.phone AS mechanic_phone,
            s.shop_name,
            c.full_name AS customer_name,
            c.phone AS customer_phone
        FROM payments p
        JOIN appointments a ON p.appointment_id = a.appointment_id
        JOIN service s ON a.service_id = s.service_id
        JOIN mechanic m ON p.mechanic_id = m.mechanic_id
        JOIN customer c ON a.customer_id = c.customer_id
        WHERE p.payment_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pid);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

$invoiceDate = date('Y-m-d');
$serviceDate = date("d M, Y", strtotime($data['appointment_date']));
$serviceTime = date("h:i A", strtotime($data['appointment_time']));

$image_path_server = $_SERVER["DOCUMENT_ROOT"] . '/customer/img/repairlogo.png';
if (file_exists($image_path_server)) {
    $image_data = file_get_contents($image_path_server);
    $base64_image = base64_encode($image_data);
    $image_src = 'data:image/png;base64,' . $base64_image;
} else {
    // Handle error: Image not found
    $image_src = ''; 
}

$dompdf = new Dompdf();

$html = '

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 0;
        padding: 0;
        background: #ffffff;
        display: flex;
        justify-content: center;
    }
    .main{
        max-width: 1000px;
    }

    .header {
        height: 90px;
        background: #000;
        padding: 30px;
        color: white;
        text-align: left;
    }

    .header h1 {
        margin: 0;
        font-size: 32px;
    }

    .sub-title {
        font-size: 14px;
        margin-top: 5px;
        opacity: 0.8;
    }

    .section {
        padding: 30px;
    }

    .col-title {
        font-size: 14px;
        font-weight: bold;
        color: #E91E63;
        margin-bottom: 5px;
    }

    .info-box {
        font-size: 13px;
        line-height: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        background: #000;
        color: white;
        font-size: 13px;
        padding: 10px;
        text-align: left;
    }

    .table td {
        font-size: 13px;
        padding: 10px;
        border-bottom: 1px solid #ccc;
    }

    .totals {
        margin-top: 20px;
        width: 40%;
        float: right;
        font-size: 14px;
    }

    .totals td {
        padding: 6px;
        border-bottom: 1px solid #ccc;
    }
        .details{
        width: 100%;
        border-collapse: collapse;
    }
    .details th{
        background-color: white;
        font-size: 14px;
        font-weight: bold;
        color: #E91E63;
        text-align: left;
    }
    .details td{
        font-size: 13px;
        padding: 10px;
        border-bottom: none;
        text-align: left;
    }

    .footer-box {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        padding: 20px;
        font-size: 13px;
    }
        .bottom-text {
        text-align: center;

        color: #E91E63;
        font-size: 14px;
        font-weight: bold;
    }
    .details-f th{
        background-color: white;
        font-size: 14px;
        font-weight: bold;
        color: #E91E63;
        text-align: left;
        border-bottom: 1px solid #ccc;
    }
        .details-f td{
        font-size: 13px;
        padding: 10px;
        border-bottom: none;
        text-align: left;
}
        
</style>

<div class="main">

<div class="header">
    
        <h1>REPAIR 360</h1>
        <div class="sub-title">Professional Repair Service Invoice</div>
        <h4>Shop Name: '.$data['shop_name'].'</h4>

</div>


<div class="section">


    <table class="details">
        <thead>
            <tr>
                    <th>CUSTOMER:</th>
                    <th>MECHANIC:</th>
                    <th>SERVICE:</th>
                    <th>INVOICE:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>'.$data['customer_name'].'</strong></td>
                    <td><strong>'.$data['mechanic_name'].'</strong></td>
                    <td>'.$data['service_name'].'</td>
                    <td> #'.$data['payment_id'].'</td>
            </tr>
            <tr>
                <td>'.$data['customer_phone'].'</td>
                    <td>'.$data['mechanic_phone'].'</td>
                    <td>'.$serviceDate. '.<br>at '. $serviceTime.'</td>
                    <td>TxID: '.$data['transaction_id'].'</td>
            </tr>
            <tr>
                <td></td>
                    <td></td>
                    <td></td>
                    <td>'.$invoiceDate.'</td>
            </tr>
        </tbody>
    </table>

<p><strong>Payment Date:</strong> '.$data['created_at'].'</p>
    <table class="table">
        <thead>
            <tr>
                <th>PRODUCT</th>
                <th>QTY</th>
                <th>AMOUNT</th>
                <th>STATUS</th>
                <th>TOTAL</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>'.$data["service_name"].'</td>
                <td>1</td>
                <td>'.$data["amount"].' Taka</td>
                <td>'.$data["status"].'</td>
                <td>'.$data["amount"].' TAKA</td>
            </tr>
        </tbody>
    </table>


    <table class="totals">
        <tr>
            <td><b>Subtotal:</b></td>
            <td>'.$data["amount"].' Taka</td>
        </tr>
        <tr>
            <td><b>Tax (0%):</b></td>
            <td>0.00 Taka</td>
        </tr>
        <tr>
            <td><b>Total:</b></td>
            <td><b>'.$data["amount"].' Taka</b></td>
        </tr>
    </table>

    <div style="clear: both;"></div>


    <div class="footer-box">

     <table class="details-f">
        <thead>
            <tr>
                <th>TERMS & CONDITIONS:</th>
                <th>PAYMENT METHOD:</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong> Payment is non-refundable once the service is completed. 
            All repairs follow Repair360 policy guidelines.</strong></td>
                <td><strong>('.$data["method"].') Secure digital invoice generated automatically.</strong></td>
                <td><img src="' . $image_src . '" 
         height="128" width="128" alt="Logo"></td>
            </tr>
        </tbody>
    </table>

    </div>

    <div class="bottom-text">www.repair360.com</div>

</div>
</div>

';


$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("Invoice_{$pid}.pdf");
