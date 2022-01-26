<?php
session_start();


if ( ! isset($_SESSION['name']) ) {
  die("ACCESS DENIED");
}
else {
    $user = $_SESSION['name'];
}

include 'pdo.php';

if (isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = 'SELECT make, year, mileage, model, autos_id as id FROM autos WHERE autos_id = ' . $id;
    if(($pdo->query($sql))->rowCount() > 0) {
        foreach ($pdo->query($sql) as $row) {
            $make = $row['make'];
            $model = $row['model'];
            $year = $row['year'];
            $mileage = $row['mileage'];
            
        }
    }
    else {
        $_SESSION['success'] = "<p style = 'color: red;'>Bad value for id </p>"; // Caso em que n encontra com o id dado
        header('Location: index.php');
        return;
    }
}
else{    
    $_SESSION['success'] = "<p style = 'color: red;'>Bad value for id </p>"; // Caso em que há um acesso direto
    header('Location: index.php');
    return;
    
}

if (isset($_POST['make'])) {
    if( is_numeric($_POST['year']) === FALSE || is_numeric($_POST['mileage']) === FALSE ) {
        $_SESSION['failure'] = "<font color = 'red'>Mileage and year must be numeric </font>";
        header("Location: edit.php?id=".$id);
        return;
    }
    else if(empty($_POST['make']) === TRUE || empty($_POST['model']) === TRUE ||
            empty($_POST['year']) === TRUE || empty($_POST['mileage']) === TRUE) {
        $_SESSION['failure'] = "<font color = 'red'>All fields are required</font>";
        header("Location: edit.php?id=".$id);
        return;
    }
    else {
        $_SESSION['make'] = htmlentities($_POST['make']);
        $_SESSION['model'] = htmlentities($_POST['model']);
        $_SESSION['year'] = htmlentities($_POST['year']);
        $_SESSION['mileage'] = htmlentities($_POST['mileage']);
        
        $data = [
            'mk' => $_SESSION['make'],
            'md' => $_SESSION['model'],
            'yr' => $_SESSION['year'],
            'ma' => $_SESSION['mileage'],
            'id' => $id,
        ];
        $sql = "UPDATE autos SET make=:mk, model=:md, year=:yr, mileage=:ma WHERE autos_id=:id";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);
        
        $_SESSION['success'] = "<p style = 'color: green;'>Record edited </p>";
        header("Location: index.php");
        return;
        #$message = "<font color = 'green'>Record inserted</font>";
    }
    
}
?>
<!DOCTYPE html>
<html>
    
    <head>
    <?php require_once 'bootstrap.php'; ?>
        <title>Gabriel da Silva Calasans 420d63b1 </title>
    
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
            <input type="text" name="make" value="<?=htmlentities($make)?>" id="make"><br/>
            <label for="model">Model</label>
            <input type="text" name="model" value="<?=htmlentities($model)?>" id="model"><br/>
            <label for="year">Year</label>
            <input type="text" name="year" value="<?=htmlentities($year)?>" id="year"><br/>
            <label for="mileage">Mileage</label>
            <input type="text" name="mileage" value="<?=htmlentities($mileage)?>" id="mileage"><br/>
                
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
            </form>
            
        </div>
    </body> 
</html>