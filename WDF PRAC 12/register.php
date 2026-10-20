<?php
require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
   $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $institute = trim($_POST["institute"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $year = trim($_POST["year"] ?? "");
    $rollNo = trim($_POST["rollNo"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";

    $name = htmlspecialchars($name);
    $username=htmlspecialchars($username);
    $email = htmlspecialchars($email);
    $mobile = htmlspecialchars($mobile);
    $institute = htmlspecialchars($institute);
    $year = htmlspecialchars($year);
    $course = htmlspecialchars($course);
    $rollNo = htmlspecialchars($rollNo);

    if ($name == "" ||$username==""|| $email == "" || $mobile == "" ||
        $institute == "" || $year == "" || $course == "" ||
        $rollNo == "" || $password == "" || $confirmPassword == "") {

        die("Please fill all the fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Please enter a valid email address.");
    }

    if (!preg_match("/^[A-Za-z0-9_]{3,20}$/", $username)) {
    die("Username must contain 3 to 20 letters, numbers, or underscore.");
}

    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        die("Please enter a valid 10-digit mobile number.");
    }

    if (strlen($password) < 8) {
        die("Password must contain at least 8 characters.");
    }

    if (!preg_match("/[A-Z]/", $password)) {
        die("Password must contain one uppercase letter.");
    }

    if (!preg_match("/[a-z]/", $password)) {
        die("Password must contain one lowercase letter.");
    }

    if (!preg_match("/[0-9]/", $password)) {
        die("Password must contain one number.");
    }

    if ($password != $confirmPassword) {
        die("Passwords do not match.");
    }

    $check = mysqli_query($conn, "SELECT id FROM students WHERE email='$email' OR username='$username'");

    if (mysqli_num_rows($check) > 0) {
        die("Email or username already exists.");
    }
   
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      if ($year == "1st Year") {
          $year = 1;
      } elseif ($year == "2nd Year") {
             $year = 2;

       } elseif ($year == "3rd Year") {
               $year = 3;
        } elseif ($year == "4th Year") {
                    $year = 4;
}

  $sql="INSERT INTO students(name,username,email,mobile,institute,year,course,roll_no,password)
  VALUES
  ('$name','$username','$email','$mobile','$institute','$year','$course','$rollNo','$hashedPassword')";

if(mysqli_query($conn,$sql)){
     echo" Registration successfully!! Your information has been saved.";
}

else{
    echo"Registration failed: ".mysqli_error($conn);
}


}


?>