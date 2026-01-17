<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal'){
    header("Location: ../views/login.php");
    exit;
}

// Include database connection
require_once __DIR__ . '/../app/config/database.php';
// Include PrincipalController
require_once __DIR__ . '/../app/controllers/PrincipalController.php';

// Create controller object
$principal = new PrincipalController($conn);
$totalStudents = $principal->getTotalStudents();

/* =====================
   Fetch Current College Dean and Registrar
===================== */
$roleQuery = $conn->query("SELECT role, full_name FROM users WHERE role IN ('dean','registrar')");
$currentRoles = ['dean' => null, 'registrar' => null];
while($r = $roleQuery->fetch_assoc()) {
    $currentRoles[$r['role']] = $r['full_name'];
}
?>

<?php include __DIR__ . '/../app/includes/header.php'; ?>

<!-- Load Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* General */
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
.card.students { background-color: #4e73df; } /* Blue */
.card.dean { background-color: #28a745; }     /* Green */
.card.registrar { background-color: #ffc107; color:#000; } /* Yellow */

/* Card content */
.card .label { font-size:16px; opacity:0.85; display:block; margin-bottom:6px; }
.card p { font-size:26px; margin-top:10px; font-weight:bold; color:#fff; }

/* Adjust registrar text color */
.card.registrar p { color:#000; }

/* Icons inside cards */
.card i {
    font-size: 40px;
    opacity: 0.2;
    position: absolute;
    top: 15px;
    right: 15px;
}

/* Role badges - slimmer and smaller */
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

/* Specific badge colors */
.role-dean { background:#28a745; color:#fff; }
.role-registrar { background:#ffc107; color:#000; }
.role-lecturer { background:#6c757d; color:#fff; }
</style>

<button class="logout" onclick="window.location.href='../views/logout.php'">Logout</button>

<!-- Dashboard navigation links -->
<div class="dashboard-links">
    <a href="#">View Students</a>
    <a href="view_lecturers.php">View Lecturers</a>
    <a href="assign_roles.php">Assign Academic Dean / Registrar</a>
    <a href="#">View Finance</a>
    <a href="#">Send Announcements</a>
    <a href="#">View Academic Reports</a>
</div>

<main>
    <h2 style="text-align:center; margin-top:20px;">Dashboard Overview</h2>

    <div class="dashboard-cards">
        <!-- Total Students Card -->
        <div class="card students">
            <i class="fas fa-user-graduate"></i>
            <span class="label">Total Students</span>
            <p><?php echo $totalStudents; ?></p>
        </div>

        <!-- Current Dean Card -->
        <div class="card dean">
            <i class="fas fa-chalkboard-teacher"></i>
            <span class="label">College Academic Dean</span>
            <?php if($currentRoles['dean']): ?>
                <span class="role-badge role-dean"><?= htmlspecialchars($currentRoles['dean']) ?></span>
            <?php else: ?>
                <span style='color:#fff;'>Not Assigned</span>
            <?php endif; ?>
        </div>

        <!-- Current Registrar Card -->
        <div class="card registrar">
            <i class="fas fa-file-alt"></i>
            <span class="label">College Registrar</span>
            <?php if($currentRoles['registrar']): ?>
                <span class="role-badge role-registrar"><?= htmlspecialchars($currentRoles['registrar']) ?></span>
            <?php else: ?>
                <span style='color:#000;'>Not Assigned</span>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../app/includes/footer.php'; ?>
