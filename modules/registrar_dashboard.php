<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'registrar'){
    header("Location: ../views/login.php");
    exit;
}

// Include database connection
require_once __DIR__ . '/../app/config/database.php';

// --------------------
// Fetch Registrar Data
// --------------------

// Total students (role='student')
$totalStudentsRes = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'");
$totalStudents = $totalStudentsRes ? $totalStudentsRes->fetch_assoc()['total'] : 0;

// Students pending enrollment (assuming status='pending' column exists)
$pendingStudentsRes = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student' AND status='pending'");
$pendingStudents = $pendingStudentsRes ? $pendingStudentsRes->fetch_assoc()['total'] : 0;

// Total courses (ensure table 'courses' exists)
$totalCoursesRes = $conn->query("SELECT COUNT(*) as total FROM courses");
$totalCourses = $totalCoursesRes ? $totalCoursesRes->fetch_assoc()['total'] : 0;
?>

<?php include __DIR__ . '/../app/includes/header.php'; ?>

<!-- Load Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* General styles */
body { font-family: Arial, sans-serif; background-color: #f5f6fa; margin:0; }
.logout { position:absolute; top:20px; right:20px; background-color:#e74a3b; color:white; padding:8px 12px; border:none; border-radius:5px; cursor:pointer;}
.logout:hover { background-color:#c5302c; }

/* Dashboard navigation links */
.dashboard-links {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-top: 20px;
    flex-wrap: wrap;
}
.dashboard-links a {
    text-decoration: none;
    font-weight: bold;
    color: #05581e; /* College dark green */
    padding: 8px 16px;
    border-radius: 6px;
    background-color: #dbe4ff;
    transition: background 0.3s, transform 0.2s;
}
.dashboard-links a:hover {
    background-color: #c0d4ff;
    transform: scale(1.05);
}

/* Dashboard cards container */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 30px;
    justify-items: center;
    max-width: 1000px; /* center and limit width */
    margin-left: auto;
    margin-right: auto;
}

/* Individual card styling */
.card {
    width: 100%;
    padding: 25px;
    border-radius: 12px;
    color: #fff;
    position: relative;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    text-align: left;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}

/* Card colors */
.card.students { background-color: #4e73df; }   /* Blue */
.card.pending { background-color: #fd7e14; }    /* Orange */
.card.courses { background-color: #20c997; }    /* Teal */

/* Card content */
.card .label { font-size:16px; opacity:0.85; display:block; margin-bottom:6px; }
.card p { font-size:26px; margin-top:10px; font-weight:bold; color:#fff; }

/* Adjust pending and courses text colors if needed */
.card.pending p { color:#fff; }
.card.courses p { color:#fff; }

/* Icons inside cards */
.card i {
    font-size: 40px;
    opacity: 0.2;
    position: absolute;
    top: 15px;
    right: 15px;
}

/* Role badges - optional if needed later */
.role-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 13px;
    transition: all 0.2s ease;
    cursor: default;
}
.role-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
</style>

<button class="logout" onclick="window.location.href='../views/logout.php'">Logout</button>

<!-- Dashboard navigation links -->
<div class="dashboard-links">
    <a href="add_student.php">Add Student</a>
    <a href="view_students.php">View Students</a>
    <a href="enrollments.php">Manage Enrollments</a>
    <a href="student_reports.php">Student Reports</a>
</div>

<main>
    <h2 style="text-align:center; margin-top:20px;">Registrar Dashboard Overview</h2>

    <div class="dashboard-cards">
        <!-- Total Students Card -->
        <div class="card students">
            <i class="fas fa-user-graduate"></i>
            <span class="label">Total Students</span>
            <p><?= $totalStudents ?></p>
        </div>

        <!-- Pending Students Card -->
        <div class="card pending">
            <i class="fas fa-user-clock"></i>
            <span class="label">Pending Enrollment</span>
            <p><?= $pendingStudents ?></p>
        </div>

        <!-- Total Courses Card -->
        <div class="card courses">
            <i class="fas fa-book-open"></i>
            <span class="label">Total Courses</span>
            <p><?= $totalCourses ?></p>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../app/includes/footer.php'; ?>
