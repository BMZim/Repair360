<?php
session_start();
include('connection.php');

$mechanic_id = $_GET['id'] ?? null; 
$mechanic_type = $_GET['type'] ?? null;

if (!$mechanic_id || !$mechanic_type) {
    echo "<h3 style='color:red;text-align:center;'>Mechanic ID or Type not found in session!</h3>";
    exit();
}


$skill_query = "SELECT service_name FROM service_skills WHERE service_type = ?";
$stmt = $con->prepare($skill_query);
$stmt->bind_param("s", $mechanic_type);
$stmt->execute();
$result = $stmt->get_result();

$skills = [];
while ($row = $result->fetch_assoc()) {
    $skills[] = $row['service_name'];
}

$stmt->close();

if (isset($_POST['submit'])) {
    $selected_skills = isset($_POST['skills']) ? $_POST['skills'] : [];

    if (empty($selected_skills)) {
        echo "<script>alert('Please select at least one skill!');</script>";
    } else {
        $skills_str = implode(',', $selected_skills);

        $sql = "INSERT INTO mechanic_skills (mechanic_id, mechanic_type, skill_name) 
                VALUES ('$mechanic_id', '$mechanic_type', '$skills_str')";
        $done = mysqli_query($con, $sql);

        if ($done) {
            echo "<script>alert('Skills saved successfully!'); 
                  window.location.href='mechanic_login.php';</script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Skills</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    padding: 20px;
    display: flex;
    justify-content: center;
    transform: translateY(100px);
  }
  .skill-box {
    background: white;
    padding: 50px 100px;
    border-radius: 8px;
    max-width: 500px;
    margin: auto;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
  }
  .skill-box h2{
    text-align: left;
    color: #333;
  }
  .skill-box h3{
    text-align: left;
    color: #555;
  }
  label {
    display: block;
    margin-bottom: 8px;
  }
  #skillsForm{
    display: flex;
    flex-direction: column;
  }
  button{
    color: black;
    background-color: #10e26f;
    padding: 10px 15px;
    border-radius: 8px;
    border: none;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: all .5s ease;
  }
  button:hover{
    background-color: #43e08a;
    transform: translateY(-3px);
  }
</style>
</head>
<body>

<div class="skill-box">
  <h2>Select <?= htmlspecialchars($mechanic_type) ?> Skills</h2>
  <form id="skillsForm" method="POST">
    <h3 style="color: red;">Your Mechanic ID: <?= htmlspecialchars($mechanic_id) ?></h3>
    <div id="skillsContainer">
      <?php
      if (!empty($skills)) {
          foreach ($skills as $skill) {
              echo '<label><input type="checkbox" name="skills[]" value="'.htmlspecialchars($skill).'"> '.htmlspecialchars($skill).'</label>';
          }
      } else {
          echo "<p>No skills available for this type.</p>";
      }
      ?>
    </div>
    <br>
    <button type="submit" name="submit">Save Skills</button>
  </form>
</div>

</body>
</html>
