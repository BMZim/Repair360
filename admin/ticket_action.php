<?php
include("config.php");

$action = $_POST["action"] ?? "";

if($action == "view"){
    $id = intval($_POST["id"]);
    $q = $conn->query("SELECT description FROM support_tickets WHERE ticket_id=$id");
    $row = $q->fetch_assoc();
    echo nl2br($row["description"]);
    exit;
}

if($action == "resolve"){
    $id = intval($_POST["id"]);
    $user_id = intval($_POST["user_id"]);
    $user_type = $_POST["user_type"];

    // Update ticket status
    $conn->query("UPDATE support_tickets SET status='Resolved' WHERE ticket_id=$id");

    // Send Notification
    $message = "Your support ticket #$id has been resolved.";

    if($user_type == "customer"){
        $stmt = $conn->prepare("INSERT INTO customer_notifications (customer_id, message) VALUES (?,?)");
        $stmt->bind_param("is", $user_id, $message);
    } else {
        $stmt = $conn->prepare("INSERT INTO mechanic_notifications (mechanic_id, message) VALUES (?,?)");
        $stmt->bind_param("is", $user_id, $message);
    }

    echo $stmt->execute() ? "OK" : "DB Error";
    exit;
}

echo "Invalid Request";
?>
