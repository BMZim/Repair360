<?php
include("config.php");

$id = intval($_GET['id'] ?? 0);
$role = $_GET['role'] ?? '';

if(!$id || !$role){
    echo "<p>Invalid user selection.</p>";
    exit;
}

$table = ($role === 'Customer') ? 'customer' : 'mechanic';
$id_column = ($role === 'Customer') ? 'customer_id' : 'mechanic_id';

$sql = "SELECT * FROM $table WHERE $id_column = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if(!$result || $result->num_rows === 0){
    echo "<p>No user found.</p>";
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Profile image setup
$avatar = (!empty($user['avatar']) && file_exists("uploads/".$user['avatar'])) 
    ? "uploads/".$user['avatar'] 
    : "uploads/default-avatar.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($user['full_name']) ?> - Profile</title>
<style>
body {
  font-family: 'Segoe UI', Arial, sans-serif;
  background: #f7f9fc;
  margin: 0;
  padding: 40px;
}
.profile-container {
  max-width: 800px;
  margin: auto;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  overflow: hidden;
}
.profile-header {
  text-align: center;
  padding: 30px;
  background: linear-gradient(135deg, #007bff, #00b4d8);
  color: white;
}
.profile-header img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  border: 4px solid #fff;
  margin-bottom: 10px;
  object-fit: cover;
}
.profile-header h2 {
  margin: 10px 0 5px;
  font-size: 24px;
}
.profile-header p {
  font-size: 15px;
  opacity: 0.9;
}
.profile-details {
  padding: 25px 40px;
}
.profile-details h3 {
  color: #333;
  border-bottom: 2px solid #007bff;
  padding-bottom: 6px;
  margin-bottom: 20px;
  font-size: 20px;
}
.profile-details table {
  width: 100%;
  border-collapse: collapse;
}
.profile-details th {
  text-align: left;
  color: #555;
  padding: 10px;
  width: 200px;
  background: #f2f2f2;
}
.profile-details td {
  padding: 10px;
  color: #333;
  background: #fafafa;
}
.status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  color: white;
  font-size: 12px;
  font-weight: 600;
}
.status-Verified { background: #28a745; }
.status-Blocked { background: #dc3545; }
.status-Not\ Verified { background: #ffc107; color: #000; }
</style>
</head>
<body>

<div class="profile-container">
  <div class="profile-header">
    <img src="<?= htmlspecialchars($avatar) ?>" alt="Profile Picture">
    <h2><?= htmlspecialchars($user['full_name']) ?></h2>
    <p><?= htmlspecialchars($role) ?> 
      - <span class="status-badge status-<?= str_replace(' ', '\ ', $user['status']) ?>">
        <?= htmlspecialchars($user['status']) ?>
      </span>
    </p>
  </div>

  <div class="profile-details">
    <h3>Profile Details</h3>
    <table>
      <?php
      foreach($user as $key => $value){
          if(in_array($key, ['avatar', 'password'])) continue;
          echo "<tr>
                  <th>" . ucwords(str_replace('_',' ',$key)) . "</th>
                  <td>" . htmlspecialchars($value) . "</td>
                </tr>";
      }
      ?>
    </table>
  </div>
</div>

</body>
</html>
