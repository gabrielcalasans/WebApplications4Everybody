<?php
session_start();

if ( isset($_POST['logout'] ) ) {
    // Redirect the browser to index.php
    header("Location: logout.php");
    return;
}

if ( ! isset($_SESSION['name']) ) {
  die('Not logged in');
}
else {
    $user = $_SESSION['name'];
}

include 'pdo.php';

if (isset($_POST['make'])) {
    if( is_numeric($_POST['year']) === FALSE || is_numeric($_POST['mileage']) === FALSE ) {
        $_SESSION['failure'] = "<font color = 'red'>Mileage and year must be numeric </font>";
        header("Location: add.php");
        return;
    }
    else if(empty($_POST['make']) === TRUE) {
        $_SESSION['failure'] = "<font color = 'red'>Make is required</font>";
        header("Location: add.php");
        return;
    }
    else {
        $_SESSION['make'] = $_POST['make'];
        $_SESSION['year'] = $_POST['year'];
        $_SESSION['mileage'] = $_POST['mileage'];
        
        $stmt = $pdo->prepare('INSERT INTO autos
          (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
          ':mk' => htmlentities($_SESSION['make']),
          ':yr' => htmlentities($_SESSION['year']),
          ':mi' => htmlentities($_SESSION['mileage']))
        );
        $_SESSION['success'] = "Record inserted";
        header("Location: view.php");
        return;
        #$message = "<font color = 'green'>Record inserted</font>";
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
            if (isset($_SESSION['failure'])) {    
                echo('<p>'.$_SESSION['failure']."</p>\n");
                unset($_SESSION['failure']);
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
            
        </div>
    </body> 
</html>