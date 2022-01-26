<?php
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}
else {
    $user = htmlentities($_GET['name']);
}
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

include 'pdo.php';

if (isset($_POST['make'])) {
    if( is_numeric($_POST['year']) === FALSE || is_numeric($_POST['mileage']) === FALSE ) {
        $message = "<font color = 'red'>Mileage and year must be numeric </font>";
    }
    else if(empty($_POST['make']) === TRUE) {
        $message = "<font color = 'red'>Make is required</font>";
    }
    else {
        $stmt = $pdo->prepare('INSERT INTO autos
          (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
          ':mk' => htmlentities($_POST['make']),
          ':yr' => htmlentities($_POST['year']),
          ':mi' => htmlentities($_POST['mileage']))
        );
        $message = "<font color = 'green'>Record inserted</font>";
    }
    
}



?>
<!DOCTYPE html>
<html>
    
    <head>
    <?php require_once 'bootstrap.php'; ?>
        <title>Gabriel da Silva Calasans</title>
    
    </head>
    <body>
        <div class="container">
            <?php
            
            echo "<h1> Tracking Autos for $user </h1>";
            if (isset($_POST['make'])) {
                echo "$message";
            }            
            ?>
            <form method = "POST">
                
            <label for="make">Make</label>
            <input type="text" name="make" id="make"><br/>
            <label for="year">Year</label>
            <input type="text" name="year" id="year"><br/>
            <label for="mileage">Mileage</label>
            <input type="text" name="mileage" id="mileage"><br/>
                
            <input type="submit" value="Add">
            <input type="submit" name="logout" value="Logout">
            </form>
            <h2>Automobiles</h2>
            <?php
            echo "<ul>";
            $sql = 'SELECT make, year, mileage FROM autos';
            foreach ($pdo->query($sql) as $row) {
                echo "<li>".$row['year']." ". $row['make']." / ".$row['mileage']. "</li>";
               
            }
            echo "</ul>";
            ?>


        </div>
    </body> 
</html>