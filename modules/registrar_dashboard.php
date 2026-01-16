<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'registrar'){
    header("Location: ../views/login.php");
    exit;
}
?>
<h1>Welcome Registrar <?php echo $_SESSION['full_name']; ?></h1>
<a href="../views/logout.php">Logout</a>
