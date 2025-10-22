<?php
session_start();
include ('connection.php');

if(isset($_POST)){

    $sname = $_POST['name'];
    $smecid = $_POST['mecid'];
    $semail = $_POST['email'];
    $spass = $_POST['password'];
    $dob_input = $_POST['date'];
    $sdob = date('Y-m-d', strtotime($dob_input));
    $sgender = $_POST['gender'];
    $snid = $_POST['nid'];
    $sdivision = $_POST['division'];
    $saddress = $_POST['address'];
    $sphone = $_POST['phone'];
    $sexperience = $_POST['experience'];
    $scity = $_POST['city'];
    $stype = $_POST['type'];


    $hashedPass = password_hash($spass, PASSWORD_BCRYPT);



    $email_valid = "select * from mechanic where email ='$semail'";
    $result = mysqli_query($con, $email_valid);
    $nid_valid = "select * from mechanic where nid ='$snid'";
    $result1 = mysqli_query($con, $nid_valid);
    if($rows = mysqli_num_rows($result)>0){
        echo "1";
    }else if($rows = mysqli_num_rows($result1)>0){
        echo "2";
    }else{
        $sql = "insert into mechanic values('$smecid','$sname','$snid','$hashedPass','$sdob','$sgender','$sdivision','$saddress','$sphone','$scity','$semail', 'New User', '$stype', '$sexperience','')";
        $done=mysqli_query($con, $sql);
        echo "0";
        $_SESSION['type'] = $stype;
        $_SESSION['id'] = $smecid;
        
        
    }
}


    
   

?>