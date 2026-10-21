
<?php
session_start();

require_once __DIR__ . "/db.php";

if (
    !isset($_SESSION["student_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "student"
) {
    header("Location: index.html");
    exit;
}

$studentId = (int) $_SESSION["student_id"];

$sql = "SELECT e.id, e.title, e.description, e.category,
               e.event_date, e.venue, r.registration_date
        FROM registrations r
        INNER JOIN events e ON r.event_id = e.id
        WHERE r.student_id = ?
        ORDER BY r.registration_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registrations - StudentHub Portal</title>
    <link rel="stylesheet" href="event.css">
    <style>
        main {
            padding: 20px;
            max-width: 1000px;
            margin: auto;
        }

        .registration-card {
            border: 1px solid rgb(200, 200, 200);
            padding: 18px;
            margin: 15px 0;
            background-color: rgb(255, 255, 255);
            color: rgb(40, 40, 40);
        }

        .registration-card h3 {
            color: rgb(33, 150, 243);
        }

        .empty-message {
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <h1>StudentHub Portal</h1>
    <p>My Event Registrations</p>
</header>

<nav>
    <a href="student.html">Student Dashboard</a>
    <a href="event.html">Browse Events</a>
    <a href="logout.php">Logout</a>
</nav>

<main>
    <h2>My Registered Events</h2>

    <?php if ($result->num_rows > 0) { ?>

        <p>Total registrations: <?php echo $result->num_rows; ?></p>

        <?php while ($event = $result->fetch_assoc()) { ?>

            <div class="registration-card">
                <h3>
                    <?php echo htmlspecialchars($event["title"]); ?>
                </h3>

                <p>
                    <strong>Category:</strong>
                    <?php echo htmlspecialchars($event["category"]); ?>
                </p>

                <p>
                    <strong>Event Date:</strong>
                    <?php echo htmlspecialchars($event["event_date"]); ?>
                </p>

                <p>
                    <strong>Venue:</strong>
                    <?php echo htmlspecialchars($event["venue"]); ?>
                </p>

                <p>
                    <strong>Description:</strong>
                    <?php echo htmlspecialchars($event["description"]); ?>
                </p>

                <p>
                    <strong>Registration Date:</strong>
                    <?php echo htmlspecialchars($event["registration_date"]); ?>
                </p>

                <p><strong>Status:</strong> Registered</p>
            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="empty-message">
            <p>You have not registered for any events yet.</p>
            <a href="event.html">Browse Events</a>
        </div>

    <?php } ?>

</main>

<footer>
    <p>&copy; 2026 StudentHub Portal. All Rights Reserved.</p>
</footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
