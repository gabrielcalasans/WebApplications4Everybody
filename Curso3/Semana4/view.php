<?php
session_start();

if ( ! isset($_SESSION['name']) ) {
  die('Not logged in');
}
else {
    $user = $_SESSION['name'];
}

include 'pdo.php';


?>

<html>
    
    <head>
    <?php require_once 'bootstrap.php'; ?>
        <title>Gabriel da Silva Calasans</title>
    
    </head>
    <body>
        <div class="container">
            <?php
            
            echo "<h1> Tracking Autos for $user </h1>";
            if (isset($_SESSION['success'])) {    
                echo('<p style = " color: green;">'.$_SESSION['success']."</p>\n");
                unset($_SESSION['success']);
            }            
            ?>
                         
            
            
            
            
            <h2>Automobiles</h2>
            <?php
                echo "<ul>";
                $sql = 'SELECT make, year, mileage FROM autos';
                foreach ($pdo->query($sql) as $row) {
                    echo "<li>".$row['year']." ". $row['make']." / ".$row['mileage']. "</li>";

                }
                echo "</ul>";
            ?>
            
            <a href="add.php">Add New</a> | <a href="logout.php">Logout</a>
            
            
        </div>
    </body> 
</html>