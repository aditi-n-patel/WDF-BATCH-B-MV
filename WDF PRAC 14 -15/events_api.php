
<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/db.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    $result = mysqli_query(
        $conn,
        "SELECT id, title, description, category, event_date, venue
         FROM events ORDER BY id DESC"
    );

    $events = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }

    echo json_encode($events);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid JSON data."]);
    exit;
}

if ($method === "POST") {
    $title = trim($data["title"] ?? "");
    $description = trim($data["description"] ?? "");
    $category = trim($data["category"] ?? "");
    $event_date = trim($data["event_date"] ?? "");
    $venue = trim($data["venue"] ?? "");

    if (
        $title === "" || $description === "" ||
        $category === "" || $event_date === "" || $venue === ""
    ) {
        http_response_code(400);
        echo json_encode(["message" => "All fields are required."]);
        exit;
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO events (title, description, category, event_date, venue)
         VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $title,
        $description,
        $category,
        $event_date,
        $venue
    );

    if (mysqli_stmt_execute($stmt)) {
        http_response_code(201);
        echo json_encode([
            "message" => "Event added successfully.",
            "id" => mysqli_insert_id($conn)
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Unable to add event."]);
    }

    mysqli_stmt_close($stmt);
    exit;
}

if ($method === "PUT") {
    $id = (int)($data["id"] ?? 0);
    $title = trim($data["title"] ?? "");
    $description = trim($data["description"] ?? "");
    $category = trim($data["category"] ?? "");
    $event_date = trim($data["event_date"] ?? "");
    $venue = trim($data["venue"] ?? "");

    if (
        $id <= 0 || $title === "" || $description === "" ||
        $category === "" || $event_date === "" || $venue === ""
    ) {
        http_response_code(400);
        echo json_encode(["message" => "Valid ID and all fields are required."]);
        exit;
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE events
         SET title=?, description=?, category=?, event_date=?, venue=?
         WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssi",
        $title,
        $description,
        $category,
        $event_date,
        $venue,
        $id
    );

    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(["message" => "Event updated successfully."]);
    } else {
        $check = mysqli_prepare($conn, "SELECT id FROM events WHERE id=?");
        mysqli_stmt_bind_param($check, "i", $id);
        mysqli_stmt_execute($check);
        $found = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($found) > 0) {
            echo json_encode(["message" => "Event details are unchanged."]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Event not found."]);
        }

        mysqli_stmt_close($check);
    }

    mysqli_stmt_close($stmt);
    exit;
}

if ($method === "DELETE") {
    $id = (int)($data["id"] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["message" => "Valid event ID is required."]);
        exit;
    }

    $check = mysqli_prepare($conn, "SELECT id FROM events WHERE id=?");
    mysqli_stmt_bind_param($check, "i", $id);
    mysqli_stmt_execute($check);
    $found = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($found) === 0) {
        http_response_code(404);
        echo json_encode(["message" => "Event not found."]);
        mysqli_stmt_close($check);
        exit;
    }

    mysqli_stmt_close($check);

    $stmt = mysqli_prepare($conn, "DELETE FROM events WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["message" => "Event deleted successfully."]);
    } else {
        http_response_code(409);
        echo json_encode([
            "message" => "Cannot delete this event because related records may exist."
        ]);
    }

    mysqli_stmt_close($stmt);
    exit;
}

http_response_code(405);
echo json_encode(["message" => "Method not allowed."]);

mysqli_close($conn);
?>
