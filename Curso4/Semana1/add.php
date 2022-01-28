<?php
session_start();

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}

if ( ! isset($_SESSION['name']) ) {
  die("ACCESS DENIED");
}
else {
    $user = $_SESSION['name'];
    $user_id = $_SESSION['user_id'];
}

include 'pdo.php';

if (isset($_POST['first_name'])) {
    
    if(empty($_POST['first_name']) === TRUE || empty($_POST['last_name']) === TRUE ||
            empty($_POST['email']) === TRUE || empty($_POST['headline']) === TRUE ||
            empty($_POST['summary']) === TRUE) {
        
            $_SESSION['failure'] = "<font color = 'red'>All fields are required</font>";
            header("Location: add.php");
            return;
    }
    else if(strpos($_POST['email'], '@') === false){
        $_SESSION['failure'] = "<font color = 'red'>Email address must contain @</font>";
        header("Location: add.php");
        return;
    }
    
    else {
        $_SESSION['first'] = htmlentities($_POST['first_name']);
        $_SESSION['last'] = htmlentities($_POST['last_name']);
        $_SESSION['email'] = htmlentities($_POST['email']);
        $_SESSION['headline'] = htmlentities($_POST['headline']);
        $_SESSION['summary'] = htmlentities($_POST['summary']);
        
        $stmt = $pdo->prepare('INSERT INTO profile
          (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :hd, :sm)');
        $stmt->execute(array(
          ':uid' => $_SESSION['user_id'],
          ':fn' => $_SESSION['first'],
          ':ln' => $_SESSION['last'],
          ':em' => $_SESSION['email'],
          ':hd' => $_SESSION['headline'],
          ':sm' => $_SESSION['summary'])
        );
        $_SESSION['success'] = "<p style = 'color: green;'>Record added </p>";
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
                
            <label for="first">First Name</label>
            <input type="text" name="first_name" id="first"><br/>
            <label for="model">Last</label>
            <input type="text" name="last_name" id="last"><br/>
            <label for="email">Email</label>
            <input type="text" name="email" id="email"><br/>
            <label for="headline">Headline</label>
            <input type="text" name="headline" id="headline"><br/>
            <label for="summary">Summary</label><br />
            <textarea name="summary" id="summary" rows="8" cols="80"></textarea> <br />
                
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
            </form>
            
        </div>
    </body> 
</html>