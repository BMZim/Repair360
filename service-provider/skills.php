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
  }
  .skill-box h3{
    text-align: left;
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
  <h2 id="title">Select Skills</h2>
  <form id="skillsForm" method="POST">
    <h3 id="mecid" style="color: red;">Your Mechanic ID:</h3>
    <div id="skillsContainer"></div>
    <br>
    <button type="submit" value="submit" id="submit" name="submit">Save Skills</button>
  </form>
</div>

<script>
  // Skills data
  const skillData = {
    Home: ["Plumbing", "Electrical", "Carpentry", "Painting", "AC Repair", "Cleaning"],
    Vehicle: ["Car Repair", "Bike Repair", "Truck Repair", "Oil Change", "Tire Replacement", "Battery Service"],
    Tech: ["Computer Repair", "Mobile Repair", "TV Repair", "Refrigerator Repair", "Washing Machine Repair", "Networking"]
  };

  // Get mechanicType from URL
  const params = new URLSearchParams(window.location.search);
  const type = params.get("type");
  const id = params.get("id");
  
  // Display skills
  const container = document.getElementById("skillsContainer");
  if (type && skillData[type]) {
    document.getElementById("title").innerText = "Select " + type + " Skills:";
    document.getElementById("mecid").innerText = "Your Mechanic ID: " + id;
    skillData[type].forEach(skill => {
      const checkbox = `<label><input type="checkbox" name="skills[]" value="${skill}"> ${skill}</label>`;
      container.innerHTML += checkbox;
    });
  } else {
    container.innerHTML = "<p>No skills available.</p>";
  }


</script>

</body>
</html>

<?php
include('connection.php');
session_start();

$id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
$type = isset($_SESSION['type']) ? $_SESSION['type'] : '';

if (!$id || !$type) {
    echo "Mechanic ID or type not found!";
    exit();
}

if (isset($_POST['submit'])) {

    // Make sure at least one skill is selected
    $skills = isset($_POST['skills']) ? $_POST['skills'] : [];

    if (empty($skills)) {
        echo "<script>alert('Please select at least one skill');</script>";
        exit();
    }

    // Convert array to comma-separated string
    $skills_str = implode(',', $skills);

    // Check if mechanic exists
    $id_valid = "SELECT * FROM mechanic WHERE mechanic_id ='$id'";
    $result = mysqli_query($con, $id_valid);

    if (mysqli_num_rows($result) > 0) {
        // Insert skills
        $sql = "INSERT INTO mechanic_skills (mechanic_id, mechanic_type, skill_name) VALUES ('$id','$type','$skills_str')";
        $done = mysqli_query($con, $sql);
        if ($done) {
            header("Location: mechanic_login.php");
            exit();
        } else {
            echo "Error inserting skills: " . mysqli_error($con);
        }
    } else {
        echo "Mechanic ID not found!";
    }
}
?>

