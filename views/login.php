<?php
session_start();
$error = "";

// Include database connection
require_once __DIR__ . '/../app/config/database.php';

// Include AuthController
require_once __DIR__ . '/../app/controllers/AuthController.php';

// Create AuthController object
$auth = new AuthController($conn);

// Handle form submission
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if($auth->login($email, $password)){
        // Redirect based on role
        switch($_SESSION['role']){
            case 'principal':
                header("Location: ../modules/principal_dashboard.php"); exit;
            case 'dean':
                header("Location: ../modules/dean_dashboard.php"); exit;
            case 'registrar':
                header("Location: ../modules/registrar_dashboard.php"); exit;
            case 'lecturer':
                header("Location: ../modules/lecturer_dashboard.php"); exit;
            case 'accountant':
                header("Location: ../modules/accountant_dashboard.php"); exit;
            case 'student':
                header("Location: ../modules/student_dashboard.php"); exit;
            default:
                $error = "User role not recognized!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pentecostal Bible College Management System</title>
    <style>
        /* General body styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #09ebb2, #60b372);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        /* Login card */
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        /* Inputs */
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #4e73df;
            box-shadow: 0 0 5px rgba(78,115,223,0.5);
        }

        /* Button */
        button {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 6px;
            background-color: #089427;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #0a90e9;
        }

        /* Error message */
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>PENTECOSTAL BIBLE COLLEGE MANAGEMENT SYSTEM</h2>
<h3>User Login</h3>
    <?php if($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
