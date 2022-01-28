<?php
session_start();
include 'pdo.php';



?>


<!DOCTYPE html>
<html>
<head>
    <title>Gabriel da Silva Calasans 420d63b1 </title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
    <div class="container justify-content-center">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h2>Resume Registry</h2> 
                </div>
                <div class="card-body">
                    <?php 
                        echo '<div class="row g-3 justify-content-center">';
                            echo '<div class="col-12">';
                            include 'lista.php';
                            
                        echo '</div>';
                        if (isset($_SESSION['name']) && isset($_SESSION['user_id']) ) {
                            if(isset($_SESSION['success'])) {
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                            }
                            
                                echo '<div class="col-12">';
                                    echo '<a class="btn btn-success" href="add.php">Add New Entry</a> ';                        
                                    echo '<a class="btn btn-danger" href="logout.php">Logout</a>';
                                echo '</div>';
                            echo '</div>';
                        
                            
                        }
                        if (!isset($_SESSION['name'])) {
                            
                                echo '<div class="col-12">';
                                    echo '<p><a class="btn btn-warning" href="login.php">Please log in</a></p>';     
                                echo '</div>';
                            echo '</div>';
                                                   

                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

