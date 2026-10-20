
<?php
session_start();
require_once __DIR__ . "/db.php";

if (
    !isset($_SESSION["student_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] != "admin"
) {
    header("Location: index.html");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

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
$editStudent = null;

if (isset($_GET["message"])) {
    if ($_GET["message"] == "added") {
        $message = "Student added successfully.";
    } elseif ($_GET["message"] == "updated") {
        $message = "Student updated successfully.";
    } elseif ($_GET["message"] == "deleted") {
        $message = "Student deleted successfully.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? "";

    if ($action == "add" || $action == "update") {
        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $mobile = trim($_POST["mobile"] ?? "");
        $institute = trim($_POST["institute"] ?? "");
        $year = trim($_POST["year"] ?? "");
        $course = trim($_POST["course"] ?? "");
        $rollNo = trim($_POST["roll_no"] ?? "");
        $username = trim($_POST["username"] ?? "");

        if (
            $name == "" || $email == "" || $mobile == "" ||
            $institute == "" || $year == "" || $course == "" ||
            $rollNo == "" || $username == ""
        ) {
            $message = "Please fill in all fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Please enter a valid email address.";
        } elseif (!in_array($year, ["1", "2", "3", "4"], true)) {
            $message = "Please select a valid year.";
        } else {
            if ($action == "add") {
                $password = $_POST["password"] ?? "";

                if (strlen($password) < 8) {
                    $message = "Password must contain at least 8 characters.";
                } else {
                    $passwordHash = password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );

                    $role = "student";

                    $sql = "INSERT INTO students
                        (name, email, mobile, institute, year, course,
                         roll_no, username, password, role)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    try {
                        $stmt = mysqli_prepare($conn, $sql);

                        mysqli_stmt_bind_param(
                            $stmt,
                            "ssssssssss",
                            $name,
                            $email,
                            $mobile,
                            $institute,
                            $year,
                            $course,
                            $rollNo,
                            $username,
                            $passwordHash,
                            $role
                        );

                        if (mysqli_stmt_execute($stmt)) {
                            mysqli_stmt_close($stmt);
                            header("Location: manage_students.php?message=added");
                            exit;
                        }

                        $message = "Unable to add student. Email, username, or roll number may already exist.";
                        mysqli_stmt_close($stmt);
                    } catch (mysqli_sql_exception $e) {
                        $message = "Unable to add student. Email, username, or roll number may already exist.";
                    }
                }
            } else {
                $id = (int)($_POST["id"] ?? 0);

                $sql = "UPDATE students
                        SET name=?, email=?, mobile=?, institute=?,
                            year=?, course=?, roll_no=?, username=?
                        WHERE id=? AND role='student'";

                try {
                    $stmt = mysqli_prepare($conn, $sql);

                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssssssssi",
                        $name,
                        $email,
                        $mobile,
                        $institute,
                        $year,
                        $course,
                        $rollNo,
                        $username,
                        $id
                    );

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        header("Location: manage_students.php?message=updated");
                        exit;
                    }

                    $message = "Unable to update student. Check the record and duplicate details.";
                    mysqli_stmt_close($stmt);
                } catch (mysqli_sql_exception $e) {
                    $message = "Unable to update student. Check for duplicate email, username, or roll number.";
                }
            }
        }
    } elseif ($action == "delete") {
        $id = (int)($_POST["id"] ?? 0);

        $checkSql = "SELECT COUNT(*) AS total
                     FROM registrations
                     WHERE student_id = ?";

        $checkStmt = mysqli_prepare($conn, $checkSql);
        mysqli_stmt_bind_param($checkStmt, "i", $id);
        mysqli_stmt_execute($checkStmt);

        $checkResult = mysqli_stmt_get_result($checkStmt);
        $registration = mysqli_fetch_assoc($checkResult);

        mysqli_stmt_close($checkStmt);

        if ($registration["total"] > 0) {
            $message = "Cannot delete this student because related registrations exist.";
        } else {
            try {
                $sql = "DELETE FROM students
                        WHERE id = ? AND role = 'student'";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id);

                if (
                    mysqli_stmt_execute($stmt) &&
                    mysqli_stmt_affected_rows($stmt) == 1
                ) {
                    mysqli_stmt_close($stmt);
                    header("Location: manage_students.php?message=deleted");
                    exit;
                }

                $message = "Unable to delete student. Please check the record.";
                mysqli_stmt_close($stmt);
            } catch (mysqli_sql_exception $e) {
                $message = "Unable to delete student because related records exist.";
            }
        }
    }
}

if (isset($_GET["edit"])) {
    $id = (int)$_GET["edit"];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM students WHERE id=? AND role='student'"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $editStudent = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$editStudent) {
        $message = "Student record not found.";
    }
}

$search = trim($_GET["search"] ?? "");
$courseFilter = trim($_GET["course"] ?? "");

$sql = "SELECT id, name, email, mobile, institute, year, course,
               roll_no, username
        FROM students
        WHERE role='student'";

$params = [];
$types = "";

if ($search != "") {
    $sql .= " AND (name LIKE ? OR email LIKE ? OR course LIKE ?)";

    $term = "%" . $search . "%";

    $params[] = $term;
    $params[] = $term;
    $params[] = $term;

    $types .= "sss";
}

if ($courseFilter != "") {
    $sql .= " AND course=?";
    $params[] = $courseFilter;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$students = mysqli_stmt_get_result($stmt);

$coursesResult = mysqli_query(
    $conn,
    "SELECT DISTINCT course
     FROM students
     WHERE role='student'
     ORDER BY course"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - StudentHub Portal</title>
    <link rel="stylesheet" href="manage_students.css">
</head>
<body>

<header>
    <h1>StudentHub Portal</h1>
    <p>Student Management</p>
</header>

<nav>
    <a href="admin.php">Admin Dashboard</a>
    <a href="manage_students.php">Manage Students</a>
    <a href="logout.php">Logout</a>
</nav>

<main>

    <?php if ($message != ""): ?>
        <p class="message">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <section class="form-section">
        <h2><?= $editStudent ? "Edit Student" : "Add Student" ?></h2>

        <form method="POST" action="manage_students.php">
            <input
                type="hidden"
                name="action"
                value="<?= $editStudent ? "update" : "add" ?>"
            >

            <?php if ($editStudent): ?>
                <input
                    type="hidden"
                    name="id"
                    value="<?= (int)$editStudent["id"] ?>"
                >
            <?php endif; ?>

            <div class="form-grid">

                <label>Name
                    <input
                        type="text"
                        name="name"
                        required
                        value="<?= htmlspecialchars($editStudent["name"] ?? "") ?>"
                    >
                </label>

                <label>Email
                    <input
                        type="email"
                        name="email"
                        required
                        value="<?= htmlspecialchars($editStudent["email"] ?? "") ?>"
                    >
                </label>

                <label>Mobile
                    <input
                        type="text"
                        name="mobile"
                        required
                        value="<?= htmlspecialchars($editStudent["mobile"] ?? "") ?>"
                    >
                </label>

                <label>Institute
                    <input
                        type="text"
                        name="institute"
                        required
                        value="<?= htmlspecialchars($editStudent["institute"] ?? "") ?>"
                    >
                </label>

                <label>Year
                    <select name="year" required>
                        <option value="">Select Year</option>

                        <?php foreach (["1", "2", "3", "4"] as $y): ?>
                            <option
                                value="<?= $y ?>"
                                <?= (string)($editStudent["year"] ?? "") === $y ? "selected" : "" ?>
                            >
                                <?= $y ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>

                <label>Course
                    <input
                        type="text"
                        name="course"
                        required
                        value="<?= htmlspecialchars($editStudent["course"] ?? "") ?>"
                    >
                </label>

                <label>Roll Number
                    <input
                        type="text"
                        name="roll_no"
                        required
                        value="<?= htmlspecialchars($editStudent["roll_no"] ?? "") ?>"
                    >
                </label>

                <label>Username
                    <input
                        type="text"
                        name="username"
                        required
                        value="<?= htmlspecialchars($editStudent["username"] ?? "") ?>"
                    >
                </label>

                <?php if (!$editStudent): ?>
                    <label>Password
                        <input
                            type="password"
                            name="password"
                            minlength="8"
                            required
                            autocomplete="new-password"
                        >
                    </label>
                <?php endif; ?>

            </div>

            <button type="submit">
                <?= $editStudent ? "Update Student" : "Add Student" ?>
            </button>

            <?php if ($editStudent): ?>
                <a class="cancel-link" href="manage_students.php">
                    Cancel Edit
                </a>
            <?php endif; ?>
        </form>
    </section>

    <section class="table-section">
        <h2>Student Records</h2>

        <form method="GET" action="manage_students.php" class="search-form">

            <input
                type="text"
                name="search"
                placeholder="Search name, email, or course"
                value="<?= htmlspecialchars($search) ?>"
            >

            <select name="course">
                <option value="">All Courses</option>

                <?php while ($courseRow = mysqli_fetch_assoc($coursesResult)): ?>
                    <option
                        value="<?= htmlspecialchars($courseRow["course"]) ?>"
                        <?= $courseFilter === $courseRow["course"] ? "selected" : "" ?>
                    >
                        <?= htmlspecialchars($courseRow["course"]) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Search / Filter</button>

            <a class="cancel-link" href="manage_students.php">
                Clear
            </a>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Institute</th>
                        <th>Year</th>
                        <th>Course</th>
                        <th>Roll No.</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (mysqli_num_rows($students) > 0): ?>

                        <?php while ($student = mysqli_fetch_assoc($students)): ?>
                            <tr>
                                <td><?= (int)$student["id"] ?></td>
                                <td><?= htmlspecialchars($student["name"]) ?></td>
                                <td><?= htmlspecialchars($student["email"]) ?></td>
                                <td><?= htmlspecialchars($student["mobile"]) ?></td>
                                <td><?= htmlspecialchars($student["institute"]) ?></td>
                                <td><?= htmlspecialchars($student["year"]) ?></td>
                                <td><?= htmlspecialchars($student["course"]) ?></td>
                                <td><?= htmlspecialchars($student["roll_no"]) ?></td>
                                <td><?= htmlspecialchars($student["username"]) ?></td>

                                <td class="actions">
                                    <a href="manage_students.php?edit=<?= (int)$student["id"] ?>">
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="manage_students.php"
                                        onsubmit="return confirm('Are you sure you want to delete this student?');"
                                    >
                                        <input
                                            type="hidden"
                                            name="action"
                                            value="delete"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int)$student["id"] ?>"
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
                            <td colspan="10">No student records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <p class="total">
            Total matching records: <?= mysqli_num_rows($students) ?>
        </p>
    </section>

</main>

<footer>
    <p>&copy; 2026 StudentHub Portal. All Rights Reserved.</p>
</footer>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
```