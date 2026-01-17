<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../views/login.php");
    exit();
}

require_once __DIR__ . '/../app/config/database.php';

$success = $error = "";

/* =====================
   Handle Role Assignment / Demotion
===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['action'])) {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'assign_dean' || $action === 'assign_registrar') {
        $role_name = $action === 'assign_dean' ? 'dean' : 'registrar';

        // Check if role is already assigned
        $stmt = $conn->prepare("SELECT full_name FROM users WHERE role=?");
        $stmt->bind_param("s", $role_name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($current_holder);
            $stmt->fetch();
            $error = ucfirst($role_name) . " role is already assigned to $current_holder. Please demote first.";
        } else {
            // Assign the role
            $update = $conn->prepare("UPDATE users SET role=? WHERE user_id=?");
            $update->bind_param("ss", $role_name, $user_id);
            if ($update->execute()) {
                $success = ucfirst($role_name) . " assigned successfully!";
            } else {
                $error = "Failed to assign role: " . $update->error;
            }
            $update->close();
        }
        $stmt->close();

    } elseif ($action === 'demote') {
        // Demote back to lecturer
        $update = $conn->prepare("UPDATE users SET role='lecturer' WHERE user_id=?");
        $update->bind_param("s", $user_id);
        if ($update->execute()) {
            $success = "Lecturer demoted successfully!";
        } else {
            $error = "Failed to demote: " . $update->error;
        }
        $update->close();
    }
}

/* =====================
   Fetch current Dean and Registrar
===================== */
$roleQuery = $conn->query("SELECT role, full_name FROM users WHERE role IN ('dean','registrar')");
$currentRoles = ['dean' => null, 'registrar' => null];
while($r = $roleQuery->fetch_assoc()) {
    $currentRoles[$r['role']] = $r['full_name'];
}

/* =====================
   Fetch all lecturers
===================== */
$lecturers = $conn->query("
SELECT l.lecturer_id, u.user_id, u.full_name, u.role
FROM lecturers l
JOIN users u ON u.user_id = l.user_id
ORDER BY u.full_name
");
?>

<?php include __DIR__ . '/../app/includes/header.php'; ?>

<style>
/* Badge hover effect */
.role-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 5px;
    font-weight: bold;
    transition: all 0.2s ease;
    cursor: default;
}
.role-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}
.role-dean { background:#28a745; color:#fff; }
.role-registrar { background:#ffc107; color:#000; }
.role-lecturer { background:#6c757d; color:#fff; }
</style>

<div class="container">
    <h2>Assign College Roles</h2>

    <?php if($success) echo "<p class='success'>$success</p>"; ?>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>

    <!-- Show current role holders with badges -->
    <div style="margin-bottom:20px;">
        <strong>Current Academic Dean:</strong>
        <?php if($currentRoles['dean']): ?>
            <span class="role-badge role-dean"><?= htmlspecialchars($currentRoles['dean']) ?></span>
        <?php else: ?>
            <span style='color:#6c757d;'>Not Assigned</span>
        <?php endif; ?>
        <br>
        <strong>Current Registrar:</strong>
        <?php if($currentRoles['registrar']): ?>
            <span class="role-badge role-registrar"><?= htmlspecialchars($currentRoles['registrar']) ?></span>
        <?php else: ?>
            <span style='color:#6c757d;'>Not Assigned</span>
        <?php endif; ?>
    </div>

    <table border="1" cellpadding="10" cellspacing="0" style="width:100%;margin-top:10px;border-collapse:collapse;">
        <tr style="background:#007bff;color:#fff;">
            <th>Name</th>
            <th>Current Role</th>
            <th>Actions</th>
        </tr>
        <?php while($l = $lecturers->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($l['full_name']) ?></td>
            <td>
                <?php
                switch($l['role']){
                    case 'dean':
                        echo "<span class='role-badge role-dean'>Dean</span>";
                        break;
                    case 'registrar':
                        echo "<span class='role-badge role-registrar'>Registrar</span>";
                        break;
                    default:
                        echo "<span class='role-badge role-lecturer'>Lecturer</span>";
                        break;
                }
                ?>
            </td>
            <td>
                <form method="POST" style="display:flex;gap:10px;">
                    <input type="hidden" name="user_id" value="<?= $l['user_id'] ?>">

                    <?php if($l['role'] === 'lecturer'): ?>
                        <!-- Assign Dean -->
                        <button type="submit" name="action" value="assign_dean"
                            style="background:#28a745;color:#fff;padding:5px 10px;border:none;border-radius:4px;cursor:pointer;"
                            <?= $currentRoles['dean'] ? 'disabled' : '' ?>>
                            Assign Academic Dean
                        </button>

                        <!-- Assign Registrar -->
                        <button type="submit" name="action" value="assign_registrar"
                            style="background:#ffc107;color:#000;padding:5px 10px;border:none;border-radius:4px;cursor:pointer;"
                            <?= $currentRoles['registrar'] ? 'disabled' : '' ?>>
                            Assign Registrar
                        </button>

                    <?php else: ?>
                        <!-- Demote -->
                        <button type="submit" name="action" value="demote"
                            style="background:#e74a3b;color:#fff;padding:5px 10px;border:none;border-radius:4px;cursor:pointer;">
                            Demote to Lecturer
                        </button>
                    <?php endif; ?>

                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include __DIR__ . '/../app/includes/footer.php'; ?>
