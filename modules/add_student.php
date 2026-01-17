<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'registrar') {
    header("Location: ../views/login.php");
    exit;
}

require_once __DIR__ . '/../app/config/database.php';

/* =====================
   Generate Student ID
===================== */
$result = $conn->query("SELECT student_id FROM students ORDER BY student_id DESC LIMIT 1");
if ($result && $row = $result->fetch_assoc()) {
    $lastId = intval(substr($row['student_id'], 3));
    $newId = 'PBC' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
} else {
    $newId = 'PBC001';
}

/* =====================
   Handle Form Submission
===================== */
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $uploadDir = "../uploads/students/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    function uploadFile($file, $uploadDir) {
        if (!empty($file['name'])) {
            $fileName = time() . "_" . basename($file['name']);
            $target = $uploadDir . $fileName;
            move_uploaded_file($file['tmp_name'], $target);
            return $target;
        }
        return null;
    }

    $passport = uploadFile($_FILES['passport_photo'], $uploadDir);
    $kcse = uploadFile($_FILES['kcse_certificate'], $uploadDir);
    $nidUpload = uploadFile($_FILES['national_id_upload'], $uploadDir);

    $stmt = $conn->prepare("
        INSERT INTO students (
            student_id, full_name, national_id, date_of_registration,
            gender, contact, email, date_of_birth,
            local_church, home_district, extension_centre,
            overseer, overseer_contact,
            passport_photo, kcse_certificate, national_id_upload
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssssssssssssssss",
        $newId,
        $_POST['full_name'],
        $_POST['national_id'],
        $_POST['date_of_registration'],
        $_POST['gender'],
        $_POST['contact'],
        $_POST['email'],
        $_POST['date_of_birth'],
        $_POST['local_church'],
        $_POST['home_district'],
        $_POST['extension_centre'],
        $_POST['overseer'],
        $_POST['overseer_contact'],
        $passport,
        $kcse,
        $nidUpload
    );

    if ($stmt->execute()) {
        $message = "Student registered successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
        }
        header {
            background: #05581e;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 900px;
            background: #fff;
            margin: 30px auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #05581e;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        label {
            font-weight: bold;
            font-size: 14px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .full {
            grid-column: span 2;
        }
        button {
            background: #05581e;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }
        button:hover {
            background: #044317;
        }
        .msg {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .back {
            text-align: center;
            margin-top: 15px;
        }
        .back a {
            color: #05581e;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>Add New Student</h1>
</header>

<div class="container">
    <h2>Student Registration Form</h2>

    <?php if($message): ?>
        <div class="msg"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="grid">

            <div>
                <label>Student ID</label>
                <input type="text" value="<?php echo $newId; ?>" readonly>
            </div>

            <div>
                <label>Full Name</label>
                <input type="text" name="full_name" required>
            </div>

            <div>
                <label>National ID</label>
                <input type="text" name="national_id" required>
            </div>

            <div>
                <label>Date of Registration</label>
                <input type="date" name="date_of_registration" required>
            </div>

            <div>
                <label>Gender</label>
                <select name="gender" required>
                    <option value="">-- Select --</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
            </div>

            <div>
                <label>Contact</label>
                <input type="text" name="contact" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div>
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" required>
            </div>

            <div>
                <label>Local Church</label>
                <input type="text" name="local_church">
            </div>

            <div>
                <label>Home District</label>
                <input type="text" name="home_district">
            </div>

            <div>
                <label>Extension Centre</label>
                <input type="text" name="extension_centre">
            </div>

            <div>
                <label>Overseer</label>
                <input type="text" name="overseer">
            </div>

            <div>
                <label>Overseer Contact</label>
                <input type="text" name="overseer_contact">
            </div>

            <div>
                <label>Passport Photo</label>
                <input type="file" name="passport_photo">
            </div>

            <div>
                <label>KCSE Certificate</label>
                <input type="file" name="kcse_certificate">
            </div>

            <div class="full">
                <label>Upload National ID</label>
                <input type="file" name="national_id_upload">
            </div>

        </div>

        <button type="submit">Register Student</button>
    </form>

    <div class="back">
        <a href="registrar_dashboard.php">← Back to Registrar Dashboard</a>
    </div>
</div>

</body>
</html>
