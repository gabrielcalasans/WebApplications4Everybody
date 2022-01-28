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
<h2>Gabriel Calasans Resume Registry</h2>
<?php
    if (!isset($_SESSION['name'])) {
?>
    <p>
    <a href="login.php">Please log in</a>
    </p>
<?php
    }
    
?>   
    
<?php 
    include 'lista.php';
    
    if (isset($_SESSION['name']) && isset($_SESSION['user_id']) ) {
        if(isset($_SESSION['success'])) {
            echo $_SESSION['success'];
            unset($_SESSION['success']);
        }
    


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

