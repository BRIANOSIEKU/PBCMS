<?php
session_start();
require_once __DIR__ . '/../app/config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../views/login.php");
    exit();
}

if (!isset($_GET['lecturer_id'])) {
    $_SESSION['error'] = "Invalid lecturer selection.";
    header("Location: view_lecturers.php");
    exit();
}

$lecturer_id = (int) $_GET['lecturer_id'];

/* Get linked user_id */
$stmt = $conn->prepare(
    "SELECT user_id FROM lecturers WHERE lecturer_id = ?"
);
$stmt->bind_param("i", $lecturer_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $_SESSION['error'] = "Lecturer not found.";
    header("Location: view_lecturers.php");
    exit();
}

$row = $res->fetch_assoc();
$user_id = $row['user_id'];

/* Delete lecturer ONLY */
$stmt = $conn->prepare(
    "DELETE FROM lecturers WHERE lecturer_id = ?"
);
$stmt->bind_param("i", $lecturer_id);
$stmt->execute();

/* Delete linked user */
$stmt = $conn->prepare(
    "DELETE FROM users WHERE user_id = ?"
);
$stmt->bind_param("s", $user_id);
$stmt->execute();

$_SESSION['success'] = "Lecturer deleted successfully!";
header("Location: view_lecturers.php");
exit();
