<?php 
include ('connection.php');


  if(isset($_POST['Verify'])){
        $mechanic_id = $_POST['mechanic_id'];

        $sql ="SELECT status from mechanic where mechanic_id='$mechanic_id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        if($row['status'] === "Verified"){
            echo "Fail";
        }else{
            $sql1 = "UPDATE mechanic SET status='Not Verified' where mechanic_id='$mechanic_id'";
        $result1 = mysqli_query($con, $sql1);

        echo 'OK';
        }

        
  }

?>