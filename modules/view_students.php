<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'registrar') {
    header("Location: ../views/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
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
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #05581e;
            margin-bottom: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .card h3 {
            color: #05581e;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 14px;
            color: #555;
        }

        .card a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #fff;
            background: #05581e;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
        }

        .card a:hover {
            background: #044317;
        }

        .back {
            text-align: center;
            margin-top: 30px;
        }

        .back a {
            color: #05581e;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

<header>
    <h1>View Students</h1>
</header>

<div class="container">
    <h2>Select Student Category</h2>

    <div class="grid">

        <div class="card">
            <h3>Certificate – Regular</h3>
            <p>Certificate students attending regular classes</p>
            <a href="students_list.php?level=certificate&mode=regular">View Students</a>
        </div>

        <div class="card">
            <h3>Diploma – Regular</h3>
            <p>Diploma students attending regular classes</p>
            <a href="students_list.php?level=diploma&mode=regular">View Students</a>
        </div>

        <div class="card">
            <h3>Degree Class</h3>
            <p>Degree program students</p>
            <a href="students_list.php?level=degree&mode=regular">View Students</a>
        </div>

        <div class="card">
            <h3>Certificate – Extension</h3>
            <p>Certificate students in extension program</p>
            <a href="students_list.php?level=certificate&mode=extension">View Students</a>
        </div>

        <div class="card">
            <h3>Diploma – Extension</h3>
            <p>Diploma students in extension program</p>
            <a href="students_list.php?level=diploma&mode=extension">View Students</a>
        </div>

    </div>

    <div class="back">
        <a href="registrar_dashboard.php">← Back to Registrar Dashboard</a>
    </div>
</div>

</body>
</html>
