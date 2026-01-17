<?php
require_once __DIR__ . '/../app/config/database.php';

// Validate input
if (!isset($_GET['lecturer_id']) || !is_numeric($_GET['lecturer_id'])) {
    die("ERROR: Invalid Lecturer ID.");
}

$lecturer_id = $_GET['lecturer_id'];

// Fetch lecturer info
$query = "SELECT * FROM lecturers l 
          JOIN users u ON l.user_id = u.user_id 
          WHERE l.lecturer_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $lecturer_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Lecturer not found.");
}

$lecturer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lecturer Details</title>
<style>
body{font-family:Arial; background:#f4f6f8; margin:20px;}
.container{max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.1);}
img{width:120px; height:120px; border-radius:50%; object-fit:cover;}
h2{margin-top:10px;}
p{margin:5px 0; font-size:14px; color:#333;}
a.button{display:inline-block; margin-top:15px; padding:8px 12px; background:#007bff; color:#fff; text-decoration:none; border-radius:5px;}
</style>
</head>
<body>

<div class="container">
    <img src="../uploads/<?php echo htmlspecialchars($lecturer['passport_photo']); ?>" alt="Passport">
    <h2><?php echo htmlspecialchars($lecturer['full_name']); ?></h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($lecturer['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($lecturer['phone_number']); ?></p>
    <p><strong>Gender:</strong> <?php echo htmlspecialchars($lecturer['gender']); ?></p>
    <p><strong>DOB:</strong> <?php echo htmlspecialchars($lecturer['date_of_birth']); ?></p>
    <p><strong>National ID:</strong> <?php echo htmlspecialchars($lecturer['national_id']); ?></p>
    <p><strong>Department:</strong> <?php echo htmlspecialchars($lecturer['department']); ?></p>
    <p><strong>Qualification:</strong> <?php echo htmlspecialchars($lecturer['qualification']); ?></p>
    <p><strong>Date of Appointment:</strong> <?php echo htmlspecialchars($lecturer['date_of_appointment']); ?></p>
    <p><strong>Specialization:</strong> <?php echo htmlspecialchars($lecturer['specialization']); ?></p>

    <a href="edit_lecturer.php?lecturer_id=<?php echo $lecturer['lecturer_id']; ?>" class="button">✏️ Edit Lecturer</a>
    <a href="view_lecturers.php" class="button">⬅ Back to List</a>
</div>

</body>
</html>
