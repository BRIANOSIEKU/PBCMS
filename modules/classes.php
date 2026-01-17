<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';

$category_id = $_GET['category_id'];

$stmt = $conn->prepare(
    "SELECT * FROM classes WHERE category_id = ? ORDER BY academic_year DESC"
);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Classes</h2>

<a href="add_class.php?category_id=<?= $category_id; ?>">➕ Register New Class</a>

<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <a href="students_list.php?class_id=<?= $row['class_id']; ?>">
            <?= htmlspecialchars($row['class_name']); ?>
        </a>
    </li>
<?php endwhile; ?>
</ul>
