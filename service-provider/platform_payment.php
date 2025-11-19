<?php
session_start();
include("connection.php"); // FIXED: DB CONNECTION

$payment_success = false;

// Validate mechanic_id
if (!isset($_GET['mechanic_id']) || !is_numeric($_GET['mechanic_id'])) {
    die("Invalid mechanic ID");
}

$mechanic_id = intval($_GET['mechanic_id']);

// Fetch mechanic
$sql = "SELECT * FROM mechanic WHERE mechanic_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $mechanic_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch platform charge
$sql1 = "SELECT * FROM platform_charge WHERE mechanic_id = ?";
$stmt1 = $con->prepare($sql1);
$stmt1->bind_param("i", $mechanic_id);
$stmt1->execute();
$row1 = $stmt1->get_result()->fetch_assoc();
$stmt1->close();

$current_fee = $row1['platform_fee'];  // Original platform fee

// Handle Payment
if (isset($_POST['pay_now'])) {

    $new_pay = floatval($_POST['platform_fee']);
    $transaction_id = trim($_POST['transaction']);
    $method = "bKash";

    if ($new_pay <= 0 || $new_pay > $current_fee) {
        die("Invalid payment amount");
    }

    $remaining_fee = $current_fee - $new_pay;

    // FIXED SQL UPDATE with comma
    $sql2 = "
        UPDATE platform_charge 
        SET platform_fee = ?, 
            method = ?, 
            transaction_id = ?, 
            created_at = NOW()
        WHERE mechanic_id = ?
    ";

    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param("dssi", $remaining_fee, $method, $transaction_id, $mechanic_id);

    if ($stmt2->execute()) {
        $payment_success = true;
    }

    $stmt2->close();

    // Re-fetch updated values
    $stmt3 = $con->prepare($sql1);
    $stmt3->bind_param("i", $mechanic_id);
    $stmt3->execute();
    $row1 = $stmt3->get_result()->fetch_assoc();
    $stmt3->close();
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>bKash Payment</title>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body{font-family:Segoe UI,Roboto,Arial,sans-serif;background:#f3f6fb;margin:0;padding:30px;display:flex;justify-content:center;}
.pay-card{width:360px;background:#fff;border-radius:10px;box-shadow:0 8px 30px rgba(22,40,80,0.08);padding:18px;}
.brand{display:flex;gap:12px;align-items:center;margin-bottom:6px;}
.logo{width:48px;height:48px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:18px;}
h2{margin:0;font-size:20px;}
p.small{color:#666;margin:6px 0 14px;font-size:13px;}
.amount{font-size:34px;font-weight:700;color:#111;margin:12px 0;}
.muted{color:#667085;font-size:13px;}
.input-row{margin-top:12px;}
label{display:block;font-size:13px;color:#374151;margin-bottom:6px;}
input[type="text"], textarea, input[type="number"]{width:100%;padding:10px;border:1px solid #e6e9ef;border-radius:8px;font-size:14px;}
.pay-btn{display:block;width:100%;background:#E61876;color:white;border:none;padding:12px;border-radius:8px;font-weight:700;cursor:pointer;font-size:15px;margin-top:12px;}
</style>
</head>
<body>

<div class="pay-card">
    <div class="brand">
        <div class="logo"><img src="https://seekvectors.com/files/download/BKash-Group-of-Logos-08.jpg" height="80" width="80"></div>
        <div>
            <h2>bKash Payment</h2>
            <p class="small">Pay your platform fee</p>
        </div>
    </div>

    <div class="muted">Mechanic ID:</div>
    <div style="font-weight:600;"><?= htmlspecialchars($row['mechanic_id']) ?></div>

    <div class="amount">৳ <?= htmlspecialchars($row1['platform_fee']) ?></div>

    <form method="post">
        <label>Amount to Pay</label>
        <input type="number" name="platform_fee" step="0.01" max="<?= $row1['platform_fee'] ?>" placeholder="Enter payable amount" required>

        <label>Pay To (Repair360)</label>
        <input type="text" readonly value="Repair 360">

        <label>Payer Name</label>
        <input type="text" name="payer_name" readonly value="<?= htmlspecialchars($row['full_name']) ?>">

        <label>Transaction ID</label>
        <input type="text" name="transaction" required placeholder="Enter TrxID">

        <button type="submit" name="pay_now" class="pay-btn">Pay</button>
    </form>
</div>

<?php if ($payment_success): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Payment Successful!',
  text: 'Platform fee updated.',
  timer: 3000,
  showConfirmButton: false
});
</script>
<?php endif; ?>

</body>
</html>
