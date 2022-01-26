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
else {
        $_SESSION['success'] = "<p style = 'color: red;'>Bad value for id </p>"; // Caso em que n encontra com o id dado
        header('Location: index.php');
        return;
}

if (isset($_POST['autos_id'])) {    
    $data = [
        'id' => $id,        
    ];
    $sql = "DELETE FROM autos WHERE autos_id = :id";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);
    $_SESSION['success'] = "<p style='color: green;'>Record deleted</p>";
    header('Location: index.php');
    return;
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
            <form method="POST">
                Confirm: deleting <?= htmlentities($make);?> <p></p>
                <input type="hidden" name="autos_id" value="0">
                <input type="submit" value="Delete">
                <a href="index.php"> Cancel</a>
            </form>
            
        </div>
    </body> 
</html>