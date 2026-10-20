
<?php

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION["student_id"])) {
    header("Location: index.html");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    header("Location: index.html");
    exit;
}

$timeout = 600;

if (
    !isset($_SESSION["last_activity"]) ||
    (time() - $_SESSION["last_activity"]) > $timeout
) {
    $_SESSION = [];
    session_destroy();

    header("Location: index.html");
    exit;
}

$_SESSION["last_activity"] = time();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StudentHub Portal</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<header>
    <h1>StudentHub Portal</h1>
    <p>Admin Dashboard</p>
</header>

<nav>
    <a href="home.html">Home</a>
    <a href="admin.php">Dashboard</a>
    <a href="logout.php">Logout</a>
</nav>

<main>
    <section class="welcome-box">
        <h2>Welcome, Admin!</h2>
        <p>You are logged in as an administrator.</p>
    </section>

    <section class="dashboard">

        <div class="card">
            <h3>Student Management</h3>
            <p>View and manage student information.</p>
        </div>

        <div class="card">
            <h3>Event Management</h3>
            <p>View and manage college event information.</p>
             <a href="manage_events.php">Manage Events</a>
        </div>

        <div class="card">
            <h3>Registrations</h3>
            <p>View student event registration information.</p>
        </div>
        <div class="card">
    <h3>Student Management</h3>
    <p>Add, view, edit, delete, search, and filter student records.</p>
    <a href="manage_students.php">Manage Students</a>
</div>

    </section>
</main>

<footer>
    <p>&copy; 2026 StudentHub Portal. All Rights Reserved.</p>
</footer>
<script>
let timeout = 600000;
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
</body>
</html>
