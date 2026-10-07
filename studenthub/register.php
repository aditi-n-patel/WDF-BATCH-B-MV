<?php


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

    $file = fopen("students.csv", "a");

    if ($file == false) {
        die("Error opening the CSV file.");
    }

    if (filesize("students.csv") == 0) {
        fputcsv($file, [
            "Name",
            "Email",
            "Mobile",
            "Institute",
            "Year",
            "Course",
            "Roll No"
        ]);
    }

    fputcsv($file, [
        $name,
        $email,
        $mobile,
        $institute,
        $year,
        $course,
        $rollNo
    ]);

    fclose($file);

    echo "Registration successful! Your information has been saved.";
}

?>