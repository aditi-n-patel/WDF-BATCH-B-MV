
<?php
session_start();
require_once __DIR__ . "/db.php";

if (
    !isset($_SESSION["student_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "admin"
) {
    header("Location: index.html");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

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

$message = "";
$editEvent = null;

$uploadDir = __DIR__ . "/uploads/";
$allowedTypes = ["image/jpeg", "image/png", "image/webp"];
$maxFileSize = 2 * 1024 * 1024;

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function removePoster($filename)
{
    $safeName = basename((string)$filename);
    $path = __DIR__ . "/uploads/" . $safeName;

    if ($safeName !== "" && is_file($path)) {
        unlink($path);
    }
}

if (isset($_GET["message"])) {
    $messages = [
        "added" => "Event added successfully.",
        "updated" => "Event updated successfully.",
        "deleted" => "Event deleted successfully."
    ];

    $message = $messages[$_GET["message"]] ?? "";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if (in_array($action, ["add", "update"], true)) {
        $title = trim($_POST["title"] ?? "");
        $description = trim($_POST["description"] ?? "");
        $category = trim($_POST["category"] ?? "");
        $eventDate = trim($_POST["event_date"] ?? "");
        $venue = trim($_POST["venue"] ?? "");
        $id = (int)($_POST["id"] ?? 0);

        if (
            $title === "" || $description === "" ||
            $category === "" || $eventDate === "" || $venue === ""
        ) {
            $message = "Please fill in all event fields.";
        } elseif (
            !in_array($category, ["Technical", "Cultural", "Sports"], true)
        ) {
            $message = "Please select a valid category.";
        } elseif (
            DateTime::createFromFormat("!Y-m-d", $eventDate) === false ||
            DateTime::createFromFormat("!Y-m-d", $eventDate)->format("Y-m-d") !== $eventDate
        ) {
            $message = "Please enter a valid event date.";
        } else {
            $posterName = "";
            $oldPoster = "";

            if ($action === "update") {
                $stmt = mysqli_prepare(
                    $conn,
                    "SELECT poster FROM events WHERE id = ?"
                );
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $oldResult = mysqli_stmt_get_result($stmt);
                $oldEvent = mysqli_fetch_assoc($oldResult);
                mysqli_stmt_close($stmt);

                if (!$oldEvent) {
                    $message = "Event not found.";
                } else {
                    $oldPoster = $oldEvent["poster"] ?? "";
                    $posterName = $oldPoster;
                }
            }

            if ($message === "") {
                $hasUpload = isset($_FILES["poster"]) &&
                    $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE;

                if ($hasUpload) {
                    $file = $_FILES["poster"];

                    if ($file["error"] !== UPLOAD_ERR_OK) {
                        $message = "Poster upload failed. Please try again.";
                    } elseif ($file["size"] > $maxFileSize) {
                        $message = "Poster must be 2 MB or smaller.";
                    } elseif (!is_uploaded_file($file["tmp_name"])) {
                        $message = "Invalid uploaded file.";
                    } else {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mimeType = $finfo->file($file["tmp_name"]);

                        $extensions = [
                            "image/jpeg" => "jpg",
                            "image/png" => "png",
                            "image/webp" => "webp"
                        ];

                        if (!isset($extensions[$mimeType])) {
                            $message = "Only JPG, JPEG, PNG, and WEBP posters are allowed.";
                        } else {
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0755, true);
                            }

                            $posterName = bin2hex(random_bytes(16)) .
                                "." . $extensions[$mimeType];

                            if (!move_uploaded_file(
                                $file["tmp_name"],
                                $uploadDir . $posterName
                            )) {
                                $message = "Unable to save the poster.";
                            }
                        }
                    }
                }

                if ($message === "") {
                    try {
                        if ($action === "add") {
                            $sql = "INSERT INTO events
                                (title, description, category, event_date, venue, poster)
                                VALUES (?, ?, ?, ?, ?, ?)";

                            $stmt = mysqli_prepare($conn, $sql);

                            mysqli_stmt_bind_param(
                                $stmt,
                                "ssssss",
                                $title,
                                $description,
                                $category,
                                $eventDate,
                                $venue,
                                $posterName
                            );

                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);

                            header("Location: manage_events.php?message=added");
                            exit;
                        } else {
                            $sql = "UPDATE events
                                SET title=?, description=?, category=?,
                                    event_date=?, venue=?, poster=?
                                WHERE id=?";

                            $stmt = mysqli_prepare($conn, $sql);

                            mysqli_stmt_bind_param(
                                $stmt,
                                "ssssssi",
                                $title,
                                $description,
                                $category,
                                $eventDate,
                                $venue,
                                $posterName,
                                $id
                            );

                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);

                            if (
                                $hasUpload && $oldPoster !== "" &&
                                $oldPoster !== $posterName
                            ) {
                                removePoster($oldPoster);
                            }

                            header("Location: manage_events.php?message=updated");
                            exit;
                        }
                    } catch (mysqli_sql_exception $e) {
                        if (
                            $hasUpload && $posterName !== "" &&
                            $posterName !== $oldPoster
                        ) {
                            removePoster($posterName);
                        }

                        $message = "Unable to save the event. Please check the details and try again.";
                    }
                } elseif (
                    $hasUpload && isset($posterName) &&
                    $posterName !== "" && $posterName !== $oldPoster
                ) {
                    removePoster($posterName);
                }
            }
        }
    } elseif ($action === "delete") {
        $id = (int)($_POST["id"] ?? 0);

        $stmt = mysqli_prepare(
            $conn,
            "SELECT poster FROM events WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $event = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$event) {
            $message = "Event not found.";
        } else {
            $check = mysqli_prepare(
                $conn,
                "SELECT COUNT(*) AS total FROM registrations WHERE event_id = ?"
            );
            mysqli_stmt_bind_param($check, "i", $id);
            mysqli_stmt_execute($check);
            $checkResult = mysqli_stmt_get_result($check);
            $registration = mysqli_fetch_assoc($checkResult);
            mysqli_stmt_close($check);

            if ((int)$registration["total"] > 0) {
                $message = "Cannot delete this event because student registrations exist.";
            } else {
                try {
                    $delete = mysqli_prepare(
                        $conn,
                        "DELETE FROM events WHERE id = ?"
                    );
                    mysqli_stmt_bind_param($delete, "i", $id);
                    mysqli_stmt_execute($delete);
                    $deleted = mysqli_stmt_affected_rows($delete);
                    mysqli_stmt_close($delete);

                    if ($deleted === 1) {
                        removePoster($event["poster"] ?? "");
                        header("Location: manage_events.php?message=deleted");
                        exit;
                    }

                    $message = "Unable to delete the event.";
                } catch (mysqli_sql_exception $e) {
                    $message = "Cannot delete this event because related records exist.";
                }
            }
        }
    }
}

if (isset($_GET["edit"])) {
    $id = (int)$_GET["edit"];

    $stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $editEvent = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$editEvent) {
        $message = "Event not found.";
    }
}

$result = mysqli_query(
    $conn,
    "SELECT * FROM events ORDER BY event_date DESC, id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - StudentHub Portal</title>
    <link rel="stylesheet" href="manage_events.css">
</head>
<body>

<header>
    <h1>StudentHub Portal</h1>
    <p>Admin Event Management</p>
</header>

<nav>
    <a href="admin.php">Admin Dashboard</a>
    <a href="manage_events.php">Manage Events</a>
    <a href="logout.php">Logout</a>
</nav>

<main>

    <?php if ($message !== ""): ?>
        <p class="message"><?= h($message) ?></p>
    <?php endif; ?>

    <section class="form-section">
        <h2><?= $editEvent ? "Edit Event" : "Add Event" ?></h2>

        <form method="POST" action="manage_events.php" enctype="multipart/form-data">
            <input
                type="hidden"
                name="action"
                value="<?= $editEvent ? "update" : "add" ?>"
            >

            <?php if ($editEvent): ?>
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int)$editEvent["id"] ?>"
                >
            <?php endif; ?>

            <label>Event Title
                <input
                    type="text"
                    name="title"
                    required
                    value="<?= h($editEvent["title"] ?? "") ?>"
                >
            </label>

            <label>Description
                <textarea name="description" required><?= h($editEvent["description"] ?? "") ?></textarea>
            </label>

            <label>Category
                <select name="category" required>
                    <option value="">Select Category</option>
                    <?php foreach (["Technical", "Cultural", "Sports"] as $cat): ?>
                        <option
                            value="<?= h($cat) ?>"
                            <?= ($editEvent["category"] ?? "") === $cat ? "selected" : "" ?>
                        >
                            <?= h($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>Event Date
                <input
                    type="date"
                    name="event_date"
                    required
                    value="<?= h($editEvent["event_date"] ?? "") ?>"
                >
            </label>

            <label>Venue
                <input
                    type="text"
                    name="venue"
                    required
                    value="<?= h($editEvent["venue"] ?? "") ?>"
                >
            </label>

            <label>Event Poster (JPG, PNG, WEBP; maximum 2 MB)
                <input
                    type="file"
                    name="poster"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    <?= $editEvent ? "" : "" ?>
                >
            </label>

            <?php if (!empty($editEvent["poster"])): ?>
                <p>Current poster:</p>
                <img
                    class="poster-preview"
                    src="<?= "uploads/" . rawurlencode(basename($editEvent["poster"])) ?>"
                    alt="Current event poster"
                >
                <p>Leave the poster field empty to keep the current poster.</p>
            <?php endif; ?>

            <button type="submit">
                <?= $editEvent ? "Update Event" : "Add Event" ?>
            </button>

            <?php if ($editEvent): ?>
                <a class="cancel-link" href="manage_events.php">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </section>

    <section class="table-section">
        <h2>Event Records</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Poster</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($event = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= (int)$event["id"] ?></td>
                                <td>
                                    <?php if (!empty($event["poster"])): ?>
                                        <img
                                            class="poster-thumbnail"
                                            src="<?= "uploads/" . rawurlencode(basename($event["poster"])) ?>"
                                            alt="Event poster"
                                        >
                                    <?php else: ?>
                                        No poster
                                    <?php endif; ?>
                                </td>
                                <td><?= h($event["title"]) ?></td>
                                <td><?= h($event["description"]) ?></td>
                                <td><?= h($event["category"]) ?></td>
                                <td><?= h($event["event_date"]) ?></td>
                                <td><?= h($event["venue"]) ?></td>
                                <td class="actions">
                                    <a href="manage_events.php?edit=<?= (int)$event["id"] ?>">Edit</a>

                                    <form
                                        method="POST"
                                        action="manage_events.php"
                                        onsubmit="return confirm('Are you sure you want to delete this event?');"
                                    >
                                        <input type="hidden" name="action" value="delete">
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int)$event["id"] ?>"
                                        >
                                        <button type="submit" class="delete-button">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</main>

<footer>
    <p>&copy; 2026 StudentHub Portal. All Rights Reserved.</p>
</footer>

</body>
</html>

<?php
mysqli_free_result($result);
mysqli_close($conn);
?>
