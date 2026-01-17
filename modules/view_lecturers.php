<?php include __DIR__ . '/../app/includes/header.php'; ?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../views/login.php");
    exit();
}

require_once __DIR__ . '/../app/config/database.php';

$success = $_SESSION['success'] ?? "";
$error   = $_SESSION['error'] ?? "";
unset($_SESSION['success'], $_SESSION['error']);

/* =========================
   ADD LECTURER
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_lecturer'])) {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // hash later
    $phone = trim($_POST['phone']);
    $qualification = trim($_POST['qualification']);
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $national_id = trim($_POST['national_id']);
    $date_of_appointment = $_POST['date_of_appointment'];
    $department = trim($_POST['department']);
    $specialization = trim($_POST['specialization']);
    $role = 'lecturer';

    /* === Generate UNIQUE user_id === */
    $res = $conn->query("
        SELECT CAST(SUBSTRING(user_id,4) AS UNSIGNED) AS num
        FROM users
        WHERE user_id LIKE 'PBC%'
        ORDER BY num DESC
        LIMIT 1
    ");
    $row = $res->fetch_assoc();
    $next = $row ? $row['num'] + 1 : 1;
    $user_id = 'PBC' . str_pad($next, 3, '0', STR_PAD_LEFT);

    /* === Upload passport === */
    $uploadDir = __DIR__ . '/../uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $photo_path = "";
    if ($_FILES['passport_photo']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['passport_photo']['name'], PATHINFO_EXTENSION));
        $file = 'passport_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['passport_photo']['tmp_name'], $uploadDir . $file);
        $photo_path = 'uploads/' . $file;
    }

    /* === Insert USERS === */
    $stmt = $conn->prepare(
        "INSERT INTO users (user_id, full_name, email, password, role)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssss", $user_id, $full_name, $email, $password, $role);
    $stmt->execute();

    /* === Insert LECTURERS === */
    $stmt2 = $conn->prepare(
        "INSERT INTO lecturers
        (user_id, phone_number, qualification, date_of_birth, gender,
         national_id, date_of_appointment, department, specialization, passport_photo)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt2->bind_param(
        "ssssssssss",
        $user_id, $phone, $qualification, $date_of_birth, $gender,
        $national_id, $date_of_appointment, $department,
        $specialization, $photo_path
    );
    $stmt2->execute();

    $_SESSION['success'] = "Lecturer added successfully!";
    header("Location: view_lecturers.php");
    exit();
}

/* =========================
   FETCH LECTURERS
========================= */
$lecturers = $conn->query("
SELECT 
    l.lecturer_id,
    u.full_name,
    u.email,
    l.phone_number,
    l.department,
    l.qualification,
    l.passport_photo
FROM lecturers l
JOIN users u ON u.user_id = l.user_id
ORDER BY u.full_name
");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Lecturers</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body{font-family:Segoe UI;background:#eef3ff;margin:0}
.container{max-width:1200px;margin:30px auto;background:#fff;padding:25px;border-radius:12px}
.top{display:flex;justify-content:space-between;align-items:center}
.btn{background:#28a745;color:#fff;padding:10px 18px;border:none;border-radius:6px;font-weight:bold;cursor:pointer}
.btn:hover{background:#218838}
.form-box{display:none;background:#f8f9fc;padding:20px;margin:20px 0;border-radius:10px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}
input,select{padding:10px;border-radius:6px;border:1px solid #ccc}
.cards{display:flex;flex-wrap:wrap;gap:20px}
.card{width:260px;padding:15px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,.1)}
.card img{width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:10px;}
.card h3{margin:5px 0;}
.card p{margin:3px 0;font-size:14px;color:#333;}
.card .actions{margin-top:10px;display:flex;gap:5px;flex-wrap:wrap;justify-content:center;}
.button-action{padding:6px 10px;border:none;border-radius:5px;cursor:pointer;color:#fff;text-decoration:none;text-align:center;}
.view{background:#28a745;}
.edit{background:#ffc107;color:#000;}
.delete{background:#e74a3b;}
.success{color:green}
.error{color:red}
</style>
<script>
function toggleForm(){
    let f=document.getElementById("addForm");
    f.style.display=(f.style.display==="block")?"none":"block";
}
function confirmDelete(id){
    Swal.fire({
        title:'Are you sure?',
        text:'This lecturer will be permanently deleted!',
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#e74a3b',
        confirmButtonText:'Yes, delete'
    }).then((r)=>{
        if(r.isConfirmed){
            window.location.href='delete_lecturer.php?lecturer_id='+id;
        }
    });
}
</script>
</head>

<body>
<div class="container">

<div class="top">
    <h2>Lecturers</h2>
    <button class="btn" onclick="toggleForm()">+ Add Lecturer</button>
</div>

<?php if($success) echo "<p class='success'>$success</p>"; ?>
<?php if($error) echo "<p class='error'>$error</p>"; ?>

<!-- ADD LECTURER FORM -->
<div id="addForm" class="form-box">
<form method="POST" enctype="multipart/form-data">
<div class="form-grid">
<input name="full_name" placeholder="Full Name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="phone" placeholder="Phone" required>
<input name="qualification" placeholder="Qualification" required>
<input type="date" name="date_of_birth" required>
<select name="gender" required>
<option value="">Gender</option><option>Male</option><option>Female</option><option>Other</option>
</select>
<input name="national_id" placeholder="National ID" required>
<input type="date" name="date_of_appointment" required>
<input name="department" placeholder="Department" required>
<input name="specialization" placeholder="Specialization" required>
<input type="file" name="passport_photo" required>
<input name="password" placeholder="Password" required>
</div><br>
<button class="btn" name="add_lecturer">Save Lecturer</button>
</form>
</div>

<!-- LECTURER CARDS -->
<div class="cards">
<?php while($l=$lecturers->fetch_assoc()): ?>
<div class="card">
<center>
<img src="../<?php echo $l['passport_photo']; ?>" alt="Photo">
<h3><?php echo $l['full_name']; ?></h3>
<p><?php echo $l['department']; ?></p>
<p><?php echo $l['email']; ?></p>
<p><?php echo $l['phone_number']; ?></p>

<div class="actions">
    <a href="show_lecturer.php?lecturer_id=<?php echo $l['lecturer_id']; ?>" class="button-action view">View More</a>
    <a href="edit_lecturer.php?lecturer_id=<?php echo $l['lecturer_id']; ?>" class="button-action edit">Edit</a>
    <button class="button-action delete" onclick="confirmDelete(<?php echo $l['lecturer_id']; ?>)">Delete</button>
</div>

</center>
</div>
<?php endwhile; ?>
</div>

</div>
</body>
</html>
<?php include __DIR__ . '/../app/includes/footer.php'; ?>