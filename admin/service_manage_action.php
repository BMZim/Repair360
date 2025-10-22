<?php
include("config.php");

$action = $_REQUEST['action'] ?? '';

if($action === 'fetch'){
    $query = $_GET['query'] ?? '';
    $sql = "SELECT * FROM service_skills 
            WHERE id LIKE ? OR service_type LIKE ? OR service_name LIKE ?
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $like = "%$query%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){
            echo "<tr>
                    <td >".$row['id']."</td>
                    <td >".$row['service_type']."</td>
                    <td >".$row['service_name']."</td>
                    <td >
                      <button class='action-btn edit-btn-ser' data-id='{$row['id']}' data-type='{$row['service_type']}' data-name='{$row['service_name']}' style='background:#3182ce;color:white;border:none;padding:6px 10px;border-radius:4px;'>Edit</button>
                      <button class='action-btn delete-btn-ser' data-id='{$row['id']}' style='background:#e53e3e;color:white;border:none;padding:6px 10px;border-radius:4px;'>Delete</button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4' style='text-align:center;'>No Services Found</td></tr>";
    }
    exit;
}

if($action === 'add'){
    $type = trim($_POST['service_type']);
    $name = trim($_POST['service_name']);
    if(!$type || !$name) die("Fields cannot be empty!");

    $stmt = $conn->prepare("INSERT INTO service_skills (service_type, service_name) VALUES (?,?)");
    $stmt->bind_param("ss", $type, $name);
    echo $stmt->execute() ? "OK" : "Error adding service!";
    exit;
}

if($action === 'edit'){
    $id = $_POST['service_id'];
    $type = trim($_POST['service_type']);
    $name = trim($_POST['service_name']);
    $stmt = $conn->prepare("UPDATE service_skills SET service_type=?, service_name=? WHERE id=?");
    $stmt->bind_param("ssi", $type, $name, $id);
    echo $stmt->execute() ? "OK" : "Error updating service!";
    exit;
}

if($action === 'delete'){
    $id = $_POST['service_id'];
    $stmt = $conn->prepare("DELETE FROM service_skills WHERE id=?");
    $stmt->bind_param("i", $id);
    echo $stmt->execute() ? "OK" : "Error deleting service!";
    exit;
}
?>
