<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal'){
    header("Location: ../views/login.php");
    exit;
}

// Include database connection
require_once __DIR__ . '/../app/config/database.php';

$error = "";
$success = "";

// Handle form submission
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $qualification = trim($_POST['qualification']);
    $password = trim($_POST['password']); // plain text for now
    $role = 'lecturer';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $error = "Email already exists!";
    } else {
        // Insert lecturer into users table
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone_number, qualification, password, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $full_name, $email, $phone, $qualification, $password, $role);
        
        if($stmt->execute()){
            $success = "Lecturer added successfully!";
        } else {
            $error = "Error adding lecturer: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Lecturer</title>
    <style>
        body { font-family: Arial; background-color: #f5f6fa; margin:0; display:flex; justify-content:center; align-items:center; height:100vh; }
        .form-container { background:#fff; padding:30px; border-radius:10px; box-shadow:0 8px 25px rgba(0,0,0,0.2); width:400px; }
        h2 { text-align:center; color:#4e73df; }
        input { width:100%; padding:12px; margin:8px 0; border-radius:6px; border:1px solid #ccc; }
        button { width:100%; padding:12px; background:#4e73df; color:white; border:none; border-radius:6px; cursor:pointer; font-size:16px; }
        button:hover { background:#2e59d9; }
        .error { color:red; text-align:center; margin-bottom:10px; }
        .success { color:green; text-align:center; margin-bottom:10px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Lecturer</h2>

    <?php if($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    <?php if($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>

    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="text" name="qualification" placeholder="Qualification" required>
        <input type="text" name="password" placeholder="Password" required>
        <button type="submit">Add Lecturer</button>
    </form>
</div>

</body>
</html>
