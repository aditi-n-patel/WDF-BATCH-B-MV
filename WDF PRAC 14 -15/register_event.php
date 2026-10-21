
<?php
session_start();

require_once __DIR__ . "/db.php";

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

if (
    !isset($_SESSION["student_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "student"
) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Please log in with your student account first."
    ]);
    exit;
}

$studentId = (int) $_SESSION["student_id"];
$eventId = filter_input(INPUT_POST, "event_id", FILTER_VALIDATE_INT);

if (!$eventId || $eventId <= 0) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Invalid event."
    ]);
    exit;
}

$checkEvent = $conn->prepare("SELECT id FROM events WHERE id = ?");
$checkEvent->bind_param("i", $eventId);
$checkEvent->execute();
$eventResult = $checkEvent->get_result();

if ($eventResult->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Event not found."
    ]);
    exit;
}

$checkEvent->close();

$checkRegistration = $conn->prepare(
    "SELECT id FROM registrations WHERE student_id = ? AND event_id = ?"
);
$checkRegistration->bind_param("ii", $studentId, $eventId);
$checkRegistration->execute();
$registrationResult = $checkRegistration->get_result();

if ($registrationResult->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "You have already registered for this event."
    ]);
    exit;
}

$checkRegistration->close();

$insert = $conn->prepare(
    "INSERT INTO registrations (student_id, event_id, registration_date)
     VALUES (?, ?, NOW())"
);
$insert->bind_param("ii", $studentId, $eventId);

if ($insert->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Registration successful!"
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Registration failed. Please try again."
    ]);
}

$insert->close();
$conn->close();
?>
