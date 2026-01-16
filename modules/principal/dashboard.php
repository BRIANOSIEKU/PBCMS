<?php
session_start();

// Protect the page: only logged-in principals can access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'principal'){
    header("Location: ../../views/login.php"); // redirect to login if not principal
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Principal Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['full_name']; ?>!</h1>
    <p>This is the Principal Dashboard.</p>

    <!-- Logout link -->
    <a href="../../views/logout.php">Logout</a>
</body>
</html>
