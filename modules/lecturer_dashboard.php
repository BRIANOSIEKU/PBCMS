<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer'){
    header("Location: ../views/login.php");
    exit;
}
?>
<h1>Welcome Lecturer <?php echo $_SESSION['full_name']; ?></h1>
<a href="../views/logout.php">Logout</a>
