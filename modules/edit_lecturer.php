<?php
require_once __DIR__ . '/../app/config/database.php';

// Validate lecturer_id
if (!isset($_GET['lecturer_id']) || !is_numeric($_GET['lecturer_id'])) {
    die("ERROR: Invalid Lecturer ID.");
}

$lecturer_id = $_GET['lecturer_id'];

// Fetch current lecturer data
$stmt = mysqli_prepare($conn, "SELECT * FROM lecturers l JOIN users u ON l.user_id=u.user_id WHERE l.lecturer_id=?");
mysqli_stmt_bind_param($stmt, "i", $lecturer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Lecturer not found.");
}

$lecturer = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $qualification = trim($_POST['qualification']);
    $department = trim($_POST['department']);
    $specialization = trim($_POST['specialization']);

    // Update users table
    $stmt1 = mysqli_prepare($conn, "UPDATE users SET full_name=?, email=? WHERE user_id=?");
    mysqli_stmt_bind_param($stmt1, "sss", $full_name, $email, $lecturer['user_id']);
    mysqli_stmt_execute($stmt1);

    // Update lecturers table
    $stmt2 = mysqli_prepare($conn, "UPDATE lecturers SET phone_number=?, qualification=?, department=?, specialization=? WHERE lecturer_id=?");
    mysqli_stmt_bind_param($stmt2, "ssssi", $phone, $qualification, $department, $specialization, $lecturer_id);
    mysqli_stmt_execute($stmt2);

    header("Location: show_lecturer.php?lecturer_id=$lecturer_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Lecturer</title>
<style>
body{font-family:Arial; background:#f4f6f8; margin:20px;}
.container{max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
input, select{width:100%; padding:8px; margin:8px 0; border-radius:5px; border:1px solid #ccc;}
button{padding:8px 12px; background:#28a745; color:#fff; border:none; border-radius:5px; cursor:pointer;}
button:hover{background:#218838;}
a.button{display:inline-block; margin-top:10px; padding:8px 12px; background:#007bff; color:#fff; text-decoration:none; border-radius:5px;}
</style>
</head>
<body>

<div class="container">
<h2>Edit Lecturer</h2>

<form method="POST">
    <label>Full Name</label>
    <input type="text" name="full_name" value="<?php echo htmlspecialchars($lecturer['full_name']); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($lecturer['email']); ?>" required>

    <label>Phone</label>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($lecturer['phone_number']); ?>" required>

    <label>Qualification</label>
    <input type="text" name="qualification" value="<?php echo htmlspecialchars($lecturer['qualification']); ?>" required>

    <label>Department</label>
    <input type="text" name="department" value="<?php echo htmlspecialchars($lecturer['department']); ?>" required>

    <label>Specialization</label>
    <input type="text" name="specialization" value="<?php echo htmlspecialchars($lecturer['specialization']); ?>" required>

    <button type="submit">Save Changes</button>
</form>

<a href="show_lecturer.php?lecturer_id=<?php echo $lecturer['lecturer_id']; ?>" class="button">⬅ Back</a>
</div>

</body>
</html>
