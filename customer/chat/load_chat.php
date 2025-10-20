<?php
include("../db.php");

if (!isset($_GET['appointment_id'])) {
    exit("No appointment specified");
}

$appointment_id = intval($_GET['appointment_id']);

$sql = "SELECT sender_type, message, created_at 
        FROM chat_messages 
        WHERE appointment_id=? 
        ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $align = ($row['sender_type'] == 'customer') ? "right" : "left";
        $bg = ($row['sender_type'] == 'customer') ? "#4a90e2" : "#f1f1f1";
        $color = ($row['sender_type'] == 'customer') ? "#fff" : "#000";
        $timestamp = date("d M, h:i A", strtotime($row['created_at']));

        echo "
        <div style='display:flex; flex-direction:column; align-items:" . ($align == "right" ? "flex-end" : "flex-start") . "; margin:8px 0;'>
            <div style='background:$bg; color:$color; padding:10px 14px; border-radius:18px; max-width:70%; word-wrap:break-word; font-size:14px;'>
                ".nl2br(htmlspecialchars($row['message']))."
            </div>
            <span style='font-size:11px; color:#888; margin-top:3px; ".($align == "right" ? "margin-right:8px;" : "margin-left:8px;")."'>
                $timestamp
            </span>
        </div>
        ";
    }
} else {
    echo "<p style='text-align:center; color:#777; font-size:14px;'>No messages yet. Start the conversation!</p>";
}
?>
