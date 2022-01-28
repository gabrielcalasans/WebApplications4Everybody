<?php
session_start();


if ( ! isset($_SESSION['user_id']) ) {
  die("ACCESS DENIED");
}
else {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['name'];
}


if (isset($_POST['cancel']) ) {
  header('Location: index.php');
  return;
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
else{    
    $_SESSION['success'] = "<p style = 'color: red;'>Bad value for id </p>"; // Caso em que há um acesso direto
    header('Location: index.php');
    return;
    
}

if (isset($_POST['first_name'])) {
    if(empty($_POST['first_name']) === TRUE || empty($_POST['last_name']) === TRUE ||
        empty($_POST['email']) === TRUE || empty($_POST['headline']) === TRUE ||
        empty($_POST['summary']) === TRUE) {

        $_SESSION['failure'] = "<font color = 'red'>All fields are required</font>";
        header("Location: edit.php?=".$id);
        return;
    }
    else if(strpos($_POST['email'], '@') === false){
        $_SESSION['failure'] = "<font color = 'red'>Email address must contain @</font>";
        header("Location: edit.php?=".$id);
        return;
    }
    
    else {
        $_SESSION['first'] = htmlentities($_POST['first_name']);
        $_SESSION['last'] = htmlentities($_POST['last_name']);
        $_SESSION['email'] = htmlentities($_POST['email']);
        $_SESSION['headline'] = htmlentities($_POST['headline']);
        $_SESSION['summary'] = htmlentities($_POST['summary']);
        
        
        $data = [
          ':uid' => $_SESSION['user_id'],
          ':fn' => $_SESSION['first'],
          ':ln' => $_SESSION['last'],
          ':em' => $_SESSION['email'],
          ':hd' => $_SESSION['headline'],
          ':sm' => $_SESSION['summary'],
          ':id' => $id,
        ];
        $sql = "UPDATE profile SET user_id=:uid, first_name=:fn, last_name=:ln, email=:em, headline=:hd, summary=:sm WHERE profile_id=:id";
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
            
            echo "<h1> Editing Profile for $user_name </h1>";
            if (isset($_SESSION['failure'])) {    
                echo('<p>'.$_SESSION['failure']."</p>\n");
                unset($_SESSION['failure']);
            }            
            ?>
            <form method = "POST">
                
            <label for="first">First Name</label>
            <input type="text" name="first_name" value="<?= htmlentities($first); ?>" id="first"><br/>
            <label for="model">Last</label>
            <input type="text" name="last_name" value="<?= htmlentities($last); ?>" id="last"><br/>
            <label for="email">Email</label>
            <input type="text" name="email" value="<?= htmlentities($email); ?>" id="email"><br/>
            <label for="headline">Headline</label>
            <input type="text" name="headline" value="<?= htmlentities($headline); ?>" id="headline"><br/>
            <label for="summary">Summary</label><br />
            <textarea name="summary" id="summary" rows="8" cols="80"><?= htmlentities($summary); ?></textarea> <br />
                
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
            </form>
            
        </div>
    </body> 
</html>