<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'registrar') {
    header("Location: ../views/login.php");
    exit;
}

require_once __DIR__ . '/../app/config/database.php';

/* ✅ VALIDATE class_id */
if (!isset($_GET['class_id']) || empty($_GET['class_id'])) {
    die("Invalid class selected.");
}

$class_id = (int) $_GET['class_id'];

/* Fetch students */
$stmt = $conn->prepare(
    "SELECT student_id, full_name, gender FROM students WHERE class_id = ?"
);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Students</h2>

<a href="add_student.php?class_id=<?= $class_id; ?>">➕ Register Student</a>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>Gender</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['student_id']); ?></td>
            <td><?= htmlspecialchars($row['full_name']); ?></td>
            <td><?= htmlspecialchars($row['gender']); ?></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No students registered in this class.</td>
        </tr>
    <?php endif; ?>
</table>
