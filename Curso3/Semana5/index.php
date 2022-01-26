<?php
session_start();
include 'pdo.php';



?>


<!DOCTYPE html>
<html>
<head>
<title>Gabriel da Silva Calasans 420d63b1 </title>
<?php require_once "bootstrap.php"; ?>
<link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
<h2>Welcome to the Automobiles Database</h2>
<?php
    if (!isset($_SESSION['name'])) {
?>
    <p>
    <a href="login.php">Please log in</a>
    </p>
<?php
    }
    
?>   
    
<?php if (isset($_SESSION['name'])) {
    if(isset($_SESSION['success'])) {
        echo $_SESSION['success'];
        unset($_SESSION['success']);
    }
    include 'view.php';


?>
    
    <a href="add.php">Add New Entry</a>
    <p>
    <a href="logout.php">Logout</a>
    </p>
    
<?php
    }
    
?>
</div>
</body>

