<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'accountant'){
    header("Location: ../views/login.php");
    exit;
}
?>
<h1>Welcome Accountant <?php echo $_SESSION['full_name']; ?></h1>
<a href="../views/logout.php">Logout</a>
