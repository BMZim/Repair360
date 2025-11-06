<?php
session_start();
require_once "db.php";

function get_req($key, $default = null) {
    return isset($_REQUEST[$key]) ? trim($_REQUEST[$key]) : $default;
}

$appointment_id = intval(get_req('appointment_id', 0));
$customer_id    = intval(get_req('customer_id', 0));
$mechanic_id    = intval(get_req('mechanic_id', 0));
$amount         = get_req('amount', '0.00');

$amount = preg_replace('/[^0-9.]/', '', $amount);
if ($amount === '') $amount = '0.00';
$amount = number_format((float)$amount, 2, '.', '');

// 🧠 Fetch mechanic name
$mechanic_name = "Unknown Mechanic";
if ($mechanic_id > 0) {
    $mquery = $conn->prepare("SELECT full_name FROM mechanic WHERE mechanic_id = ?");
    $mquery->bind_param("i", $mechanic_id);
    $mquery->execute();
    $mquery->bind_result($mechanic_full_name);
    if ($mquery->fetch()) {
        $mechanic_name = $mechanic_full_name;
    }
    $mquery->close();
}

$payment_success = false; // 🔹 flag for JS SweetAlert

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
    $appointment_id = intval($_POST['appointment_id'] ?? 0);
    $customer_id    = intval($_POST['customer_id'] ?? 0);
    $mechanic_id    = intval($_POST['mechanic_id'] ?? 0);
    $amount         = preg_replace('/[^0-9.]/', '', $_POST['amount'] ?? '0.00');
    $amount         = number_format((float)$amount, 2, '.', '');
    $method         = 'bkash';
    $note           = substr($_POST['note'] ?? '', 0, 500);
    $transaction_id = $_POST['transaction'] ?? '';

    if ($appointment_id > 0 && $customer_id > 0 && (float)$amount > 0) {
        $check = $conn->prepare("SELECT payment_id FROM payments WHERE appointment_id = ?");
        $check->bind_param("i", $appointment_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            // Update existing record
            $update = $conn->prepare("UPDATE payments 
                SET customer_id = ?, mechanic_id = ?, amount = ?, method = ?, status = 'Paid', 
                    transaction_id = ?, note = ?, created_at = NOW() 
                WHERE appointment_id = ?");
            $update->bind_param("iissssi", $customer_id, $mechanic_id, $amount, $method, $transaction_id, $note, $appointment_id);
            if ($update->execute()) {
                $payment_success = true;
            }
            $update->close();
        }
        $check->close();
    }
}

$displayAmount = number_format((float)$amount, 2);
$customerDisplay = $customer_id ? "Customer ID: {$customer_id}" : "Customer";
$serviceRef = $appointment_id ? "Appointment #{$appointment_id}" : "Service";
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
input[type="text"],textarea{width:100%;padding:10px;border:1px solid #e6e9ef;border-radius:8px;font-size:14px;}
.pay-btn{display:block;width:100%;background:#E61876;color:white;border:none;padding:12px;border-radius:8px;font-weight:700;cursor:pointer;font-size:15px;margin-top:12px;}
.pay-btn:hover{filter:brightness(.98);transform:translateY(-1px)}
.small-info{font-size:12px;color:#9aa3b2;margin-top:10px}
.notice{margin-top:10px}
</style>
</head>
<body>
  <div class="pay-card">
    <div class="brand">
      <div class="logo"><img src="https://seekvectors.com/files/download/BKash-Group-of-Logos-08.jpg" height="80px" width="80px" ></div>
      <div>
        <h2>bKash Payment</h2>
        <p class="small">Secure checkout — Pay your service bill with bKash</p>
      </div>
    </div>

    <div class="muted">Payable for</div>
    <div style="margin-top:6px;font-weight:600;"><?= htmlspecialchars($serviceRef) ?> — <?= htmlspecialchars($customerDisplay) ?></div>

    <div class="amount">৳ <?= htmlspecialchars($displayAmount) ?></div>

    <form method="post">
      <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($appointment_id) ?>">
      <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer_id) ?>">
      <input type="hidden" name="mechanic_id" value="<?= htmlspecialchars($mechanic_id) ?>">
      <input type="hidden" name="amount" value="<?= htmlspecialchars($displayAmount) ?>">

      <label for="receiver_acc">Pay To (Mechanic)</label>
      <input type="text" id="receiver_acc" value="<?= htmlspecialchars($mechanic_name) ?>" readonly>

      <div class="input-row">
        <label for="sender">Payer Name</label>
        <input type="text" id="sender" name="payer_name" value="<?= isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : '' ?>" placeholder="Your name (optional)">
      </div>
      <div class="tx">
        <label for="transaction">Transaction ID</label>
        <input type="text" id="transaction" name="transaction" placeholder="Enter the TrxID of your payment!!" required>
      </div>

      <div class="input-row">
        <label for="note">Note (optional)</label>
        <textarea id="note" name="note" placeholder="Order # or notes" rows="2"></textarea>
      </div>

      <button type="submit" name="pay_now" class="pay-btn">Pay ৳ <?= htmlspecialchars($displayAmount) ?></button>
    </form>

    <div class="notice"></div>

    <div class="small-info">
      This is a demo payment page — no real money is transferred. Clicking <strong>Pay</strong> will just update payment record in database.
    </div>
  </div>

<?php if ($payment_success): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Payment Successful!',
  text: 'Your payment has been processed successfully.',
  timer: 5000,
  showConfirmButton: false,
  willClose: () => {
    window.close(); // auto-close the tab
  }
});
</script>
<?php endif; ?>
</body>
</html>
