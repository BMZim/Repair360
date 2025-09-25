<?php
include("../db.php");

$appointment_id = $_GET['appointment_id'];

$sql = "SELECT sender_type, message, created_at 
        FROM chat_messages 
        WHERE appointment_id=? 
        ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    $align = ($row['sender_type']=='customer') ? "right" : "left";
    $bg = ($row['sender_type']=='customer') ? "#4a90e2" : "#eee";
    $color = ($row['sender_type']=='customer') ? "white" : "black";
    echo "<div style='text-align:$align; margin:5px 0;'>
            <span style='display:inline-block; padding:10px; border-radius:15px; background:$bg; color:$color; max-width:60%;'>
                ".htmlspecialchars($row['message'])."
            </span>
          </div>";
}
?>
