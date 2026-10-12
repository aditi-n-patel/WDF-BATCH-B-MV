<?php

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

$timeout = 10;

if (isset($_SESSION["last_activity"]) &&
    (time() - $_SESSION["last_activity"]) > $timeout) {

    session_unset();
    session_destroy();

    header("Location: index.html");
    exit;
}

$_SESSION["last_activity"] = time();



if (!isset($_SESSION["student_id"])) {
    header("Location: index.html");
    exit;
}

if ($_SESSION["role"] != "student") {
    header("Location: index.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - StudentHub Portal</title>

    <link rel="stylesheet" href="student.css">

</head>

<body>

<header>

    <h1>StudentHub Portal</h1>

</header>

<nav>

    <a href="home.html">Home</a>
    <a href="profile.html">My Profile</a>
    <a href="timetable.html">Time Table</a>
    <a href="fees.html">Fee Details</a>
    <a href="result.html">Result</a>
    <a href="logout.php">Logout</a>

</nav>

<main>

    <div class="welcome-box">

        <h2 id="welcome">Welcome to StudentHub Portal</h2>

        <p id="studentEmail"></p>

    </div>

    <section class="dashboard">

        <div class="card">

            <h3>My Profile</h3>

            <p>View and manage your personal information.</p>

            <a href="profile.html">Open</a>

        </div>

        <div class="card">

            <h3>Time Table</h3>

            <p>View your semester timetable.</p>

            <a href="timetable.html">Open</a>

        </div>

        <div class="card">

            <h3>Fee Details</h3>

            <p>Check your semester fee information.</p>

            <a href="fees.html">Open</a>

        </div>

        <div class="card">

            <h3>Result</h3>

            <p>View your academic results.</p>

            <a href="result.html">Open</a>

        </div>

    </section>

</main>

<footer>

    <p>&copy; 2026 StudentHub Portal. All Rights Reserved.</p>

</footer>



<script>
let timeout = 10000;
let timer;

function resetTimer() {
    clearTimeout(timer);

    timer = setTimeout(function() {
        window.location.href = "logout.php";
    }, timeout);
}

document.addEventListener("mousemove", resetTimer);
document.addEventListener("keydown", resetTimer);
document.addEventListener("click", resetTimer);
document.addEventListener("scroll", resetTimer);

resetTimer();
</script>

<script src="student.js"></script>
<script src="theme.js"></script>

</body>

</html>