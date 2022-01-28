<?php
session_start();


if ( ! isset($_SESSION['name']) ) {
  die("ACCESS DENIED");
}
else {
    $user_name = $_SESSION['name'];
    $user_id = $_SESSION['user_id'];
}

include 'pdo.php';


if (isset($_GET['profile_id'])){
    $id = $_GET['profile_id'];
    $sql = 'SELECT * FROM profile WHERE profile_id = "'. $id.'"';
    if(($pdo->query($sql))->rowCount() > 0) {        
        foreach ($pdo->query($sql) as $row) {
            $first = $row['first_name'];
            $last = $row['last_name'];
            $email = $row['email'];
            $headline = $row['headline'];
            $summary = $row['summary'];
            
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

if (isset($_POST['delete'])) {    
    $data = [
        'id' => $id,        
    ];
    $sql = "DELETE FROM profile WHERE profile_id = :id";
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
                Confirm: deleting <?= htmlentities($first." ".$last);?> <p></p>
                <input type="hidden" name="delete" value="0">
                <input type="submit" value="Delete">
                <a href="index.php"> Cancel</a>
            </form>
            
        </div>
    </body> 
</html>