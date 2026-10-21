
<?php

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION["student_id"])) {
    header("Location: index.html");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
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

require_once __DIR__ . "/db.php";



$studentResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM students WHERE role = 'student'"
);
$totalStudents = (int)mysqli_fetch_assoc($studentResult)["total"];

$eventResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM events"
);
$totalEvents = (int)mysqli_fetch_assoc($eventResult)["total"];

$registrationResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM registrations"
);
$totalRegistrations = (int)mysqli_fetch_assoc($registrationResult)["total"];



$latestResult = mysqli_query(
    $conn,
    "SELECT
        registrations.id,
        students.name AS student_name,
        events.title AS event_title,
        registrations.registration_date
     FROM registrations
     INNER JOIN students
        ON registrations.student_id = students.id
     INNER JOIN events
        ON registrations.event_id = events.id
     ORDER BY registrations.registration_date DESC
     LIMIT 5"
);

if (!$latestResult) {
    die("Unable to load latest registrations.");
}

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
    <a href="manage_students.php">Manage Students</a>
    <a href="manage_events.php">Manage Events</a>
    <a href="logout.php">Logout</a>
</nav>

<main>

    <section class="welcome-box">
        <h2>Welcome, Admin!</h2>
        <p>You are logged in as an administrator.</p>
    </section>

    <section class="dashboard">

        <div class="card">
            <h3>Total Students</h3>
            <p><?php echo $totalStudents; ?></p>
        </div>

        <div class="card">
            <h3>Total Events</h3>
            <p><?php echo $totalEvents; ?></p>
        </div>

        <div class="card">
            <h3>Total Registrations</h3>
            <p><?php echo $totalRegistrations; ?></p>
        </div>

        <div class="card">
            <h3>Student Management</h3>
            <p>Add, view, edit, delete, search, and filter student records.</p>
            <a href="manage_students.php">Manage Students</a>
        </div>

        <div class="card">
            <h3>Event Management</h3>
            <p>View and manage college event information.</p>
            <a href="manage_events.php">Manage Events</a>
        </div>

    </section>

    <section class="welcome-box">
        <h2>Latest Registrations</h2>

        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Registration ID</th>
                    <th>Student Name</th>
                    <th>Event Title</th>
                    <th>Registration Date</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($latestResult) > 0) { ?>

                    <?php while ($row = mysqli_fetch_assoc($latestResult)) { ?>
                        <tr>
                            <td><?php echo (int)$row["id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["student_name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["event_title"]); ?></td>
                            <td><?php echo htmlspecialchars($row["registration_date"]); ?></td>
                        </tr>
                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="4">No registrations found.</td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
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
