<?php
// load_chat_mechanic.php
session_start();
include("../connection.php");

if (!isset($_GET['appointment_id'])) { echo "No appointment_id"; exit; }
$appointment_id = intval($_GET['appointment_id']);

// fetch messages ordered asc
$sql = "SELECT sender_type, message, created_at FROM chat_messages WHERE appointment_id = ? ORDER BY created_at ASC";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($sender_type, $message, $created_at);

$html = '';
if($stmt->num_rows>0){
    while ($stmt->fetch()) {
    if ($sender_type === 'mechanic') {
        // mechanic messages on right (blue)
        $html .= '<div style="text-align:right; margin:8px 0;">';
        $html .= '<span style="display:inline-block; background:#4a90e2; color:#fff; padding:10px 14px; border-radius:14px; max-width:72%; word-wrap:break-word;">' . htmlspecialchars($message) . '</span>';
        $html .= '<div style="font-size:11px; color:#999; margin-top:4px;">' . date('d M H:i', strtotime($created_at)) . '</div>';
        $html .= '</div>';
    } else {
        // customer messages on left (light gray)
        $html .= '<div style="text-align:left; margin:8px 0;">';
        $html .= '<span style="display:inline-block; background:#eee; color:#222; padding:10px 14px; border-radius:14px; max-width:72%; word-wrap:break-word;">' . htmlspecialchars($message) . '</span>';
        $html .= '<div style="font-size:11px; color:#999; margin-top:4px;">' . date('d M H:i', strtotime($created_at)) . '</div>';
        $html .= '</div>';
    }
}
$stmt->close();

echo $html;
}else{
     echo "<p style='text-align:center; color:#777; font-size:14px;'>No messages yet. Start the conversation!</p>";
}

