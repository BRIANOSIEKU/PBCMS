<?php
session_start();

// Only allow principal
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal'){
    header("Location: ../views/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Principal Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
        }

        header {
            background-color: #4e73df;
            color: white;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #f8f9fc;
            padding: 15px;
            display: flex;
            justify-content: center;
            gap: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        nav a {
            text-decoration: none;
            color: #089e42;
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        nav a:hover {
            background-color: #dbe4ff;
        }

        main {
            padding: 20px;
            text-align: center;
        }

        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #e74a3b;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout:hover {
            background-color: #c5302c;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome Principal <?php echo $_SESSION['full_name']; ?></h1>
</header>

<button class="logout" onclick="window.location.href='../views/logout.php'">Logout</button>

<nav>
    <a href="#">View Students</a>
    <a href="#">Add Lecturer</a>
    <a href="#">View Lecturers</a>
    <a href="#">View Finance</a>
    <a href="#">Send Announcements</a>
    <a href="#">View Academic Reports</a>
</nav>

<main>
    <h2>Dashboard Overview</h2>
    <p>This is your principal dashboard. Click the links above to perform actions.</p>
</main>

</body>
</html>
