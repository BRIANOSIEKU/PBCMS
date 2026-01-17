<?php
session_start();
if ($_SESSION['role'] !== 'registrar') {
    header("Location: ../views/login.php");
    exit;
}

require_once __DIR__ . '/../app/config/database.php';

$result = $conn->query("SELECT * FROM student_categories ORDER BY category_name");
?>

<h2>Student Categories</h2>

<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <a href="classes.php?category_id=<?= $row['category_id']; ?>">
            <?= htmlspecialchars($row['category_name']); ?>
        </a>
    </li>
<?php endwhile; ?>
</ul>
