<?php
session_start();
require_once __DIR__. "/db.php";
if($_SERVER["REQUEST_METHOD"]=="POST"){

$email=trim($_POST["email"]?? "");
$password = $_POST["password"] ?? "";

if($email==""|| $password==""){
    die("Please enter email and password.");
}

$email=htmlspecialchars($email);

$sql="SELECT * FROM students WHERE email='$email'";
$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)==0){
    die("Invalid email or password");
}

$student=mysqli_fetch_assoc($result);
 if (!password_verify($password, $student["password"])) {
        die("Invalid email or password.");
    }

session_regenerate_id(true);
$_SESSION["student_id"]=$student["id"];
$_SESSION["username"]=$student["username"];
$_SESSION["email"]=$student["email"];
$_SESSION["role"]=$student["role"];
$_SESSION["last_activity"]=time();

if($student["role"]=="admin"){
    header("Location:admin.php");
    exit;
}
else {
        header("Location: home.html");
        exit;

}
}
?>
