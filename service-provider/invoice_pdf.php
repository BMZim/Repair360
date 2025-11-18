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
            p.method,
            a.appointment_id,
            a.appointment_date,
            a.appointment_time,
            a.description,
            s.skills AS service_name,
            s.fee AS service_fee,
            s.shop_name,
            m.full_name AS mechanic_name,
            m.phone AS mechanic_phone,
            m.address AS mechanic_address,
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

// Format Dates
$invoiceDate = date('Y-m-d');
$serviceDate = date("d M, Y", strtotime($data['appointment_date']));
$serviceTime = date("h:i A", strtotime($data['appointment_time']));

$image_path_server = $_SERVER["DOCUMENT_ROOT"] . '/service-provider/img/repairlogo.png';
if (file_exists($image_path_server)) {
    $image_data = file_get_contents($image_path_server);
    $base64_image = base64_encode($image_data);
    $image_src = 'data:image/png;base64,' . $base64_image;
} else {
    // Handle error: Image not found
    $image_src = ''; 
}

$html = "

 <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        margin: 0;
        padding: 0;
        background: #ffffff;
    }
    .main { max-width: 1000px; margin: 0 auto; }

    /* HEADER */
    .header {
        height: 110px;
        background: #000;
        padding: 25px 30px;
        color: white;
    }
    .header h1 { margin: 0; font-size: 32px; }
    .sub-title { font-size: 14px; opacity: 0.8; }

    /* SECTION */
    .section { padding: 30px; }


    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
    }
    table th {
        background: #000;
        color: white;
        padding: 10px;
        font-size: 13px;
        text-align: left;
    }
    table td {
        padding: 10px;
        border-bottom: 1px solid #ccc;
        font-size: 13px;
    }
    .details { width: 100%; }
    .details th {
        background-color: white;
        font-size: 14px;
        font-weight: bold;
        color: #E91E63;
        padding-bottom: 8px;
        border-bottom: 1px solid #ccc;
    }
    .details td {
        border: none;
        font-size: 13px;
        
    }

    /* TOTALS */
    .totals {
        margin-top: 20px;
        width: 40%;
        float: right;
        font-size: 14px;
    }
    .totals td { padding: 6px; }

    /* FOOTER */
    .footer-box {
        margin-top: 20px;
        padding: 20px;
    }
    .bottom-text {
        text-align: center;
        margin-top: 30px;
        color: #E91E63;
        font-size: 14px;
        font-weight: bold;
    }

</style>


<div class='main'>

    <!-- HEADER -->
    <div class='header'>
        
            <h1>REPAIR 360</h1>
            <div class='sub-title'>Professional Repair Service Invoice</div>
            <h4>Shop Name: {$data['shop_name']}</h4>
    </div>

    <div class='section'>

        <table class='details'>
            <thead>
                <tr>
                    <th>CUSTOMER DETAILS:</th>
                    <th>MECHANIC DETAILS:</th>
                    <th>SERVICE DETAILS:</th>
                    <th>INVOICE DETAILS:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{$data['customer_name']}</strong></td>
                    <td><strong>{$data['mechanic_name']}</strong></td>
                    <td>{$data['service_name']}</td>
                    <td> #{$data['payment_id']}</td>
                </tr>
                <tr>
                    <td>{$data['customer_phone']}</td>
                    <td>{$data['mechanic_phone']}</td>
                    <td>{$serviceDate} <br>at {$serviceTime}</td>
                    <td>{$data['transaction_id']}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{$invoiceDate}</td>
                </tr>
            </tbody>
        </table>
<p><strong>Payment Date:</strong> {$data['created_at']}</p>
        <!-- PRODUCT TABLE -->
        <table>
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
                    <td>{$data['service_name']}</td>
                    <td>1</td>
                    <td>{$data['amount']} Taka</td>
                    <td>{$data['status']}</td>
                    <td>{$data['amount']} Taka</td>
                </tr>
            </tbody>
        </table>


        <!-- TOTALS -->
        <table class='totals'>
            <tr><td><b>Subtotal:</b></td><td>{$data['amount']} Taka</td></tr>
            <tr><td><b>Tax (0%):</b></td><td>0.00 Taka</td></tr>
            <tr><td><b>Total:</b></td><td><b>{$data['amount']} Taka</b></td></tr>
        </table>

        <div style='clear: both;'></div>


        <!-- FOOTER -->
        <div class='footer-box'>
            <table class='details'>
                <thead>
                    <tr>
                        <th>TERMS & CONDITIONS:</th>
                        <th>PAYMENT METHOD:</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Payment is non-refundable once the service is completed. All repairs follow Repair360 policy guidelines.</strong></td>
                        <td>({$data['method']})<strong> Secure digital invoice generated automatically.</strong></td>
                        <td><img src='{$image_src}' height='110'></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class='bottom-text'>www.repair360.com</div>

    </div>

</div>";

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("invoice_{$pid}.pdf");
?>
