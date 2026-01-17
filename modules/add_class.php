<?php
session_start();

/* Access control */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'registrar') {
    header("Location: ../views/login.php");
    exit;
}

require_once __DIR__ . '/../app/config/database.php';

/* Validate category_id */
if (!isset($_GET['category_id']) || empty($_GET['category_id'])) {
    die("Invalid category selected.");
}

$category_id = (int) $_GET['category_id'];

/* Fetch category name */
$stmt = $conn->prepare(
    "SELECT category_name FROM student_categories WHERE category_id = ?"
);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    die("Category not found.");
}

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $class_name = trim($_POST['class_name']);
    $academic_year = trim($_POST['academic_year']);

    if (empty($class_name) || empty($academic_year)) {
        $error = "All fields are required.";
    } else {
        $insert = $conn->prepare(
            "INSERT INTO classes (category_id, class_name, academic_year)
             VALUES (?, ?, ?)"
        );
        $insert->bind_param("iss", $category_id, $class_name, $academic_year);

        if ($insert->execute()) {
            header("Location: classes.php?category_id=" . $category_id);
            exit;
        } else {
            $error = "Failed to register class.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Class</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }
        .container {
            width: 400px;
            margin: 60px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #05581e;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background: #05581e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #044517;
        }
        .error {
            color: red;
            text-align: center;
        }
        .back {
            text-align: center;
            margin-top: 15px;
        }
        .back a {
            text-decoration: none;
            color: #4e73df;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Register Class</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Category</label>
        <input type="text" value="<?= htmlspecialchars($category['category_name']); ?>" readonly>

        <label>Class Name</label>
        <input type="text" name="class_name" placeholder="e.g. Diploma 2026" required>

        <label>Academic Year</label>
        <input type="text" name="academic_year" placeholder="e.g. 2026" required>

        <button type="submit">Register Class</button>
    </form>

    <div class="back">
        <a href="classes.php?category_id=<?= $category_id; ?>">← Back to Classes</a>
    </div>
</div>

</body>
</html>
