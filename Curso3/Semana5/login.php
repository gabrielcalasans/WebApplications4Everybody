<?php 
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: logout.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // php123 is pass

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {        
        $_SESSION['failure'] = "Email and password are required";
        error_log("Login fail ".$_POST['email']." ".$_SESSION['failure']);
        header('Location: login.php');
        return;
    }
    else if ( strpos((htmlentities($_POST['email'])), '@' ) === false) {
        $_SESSION['failure'] = "Email must have an at-sign (@)";
        error_log("Login fail ".$_POST['email']." ".$_SESSION['failure']);
        header('Location: login.php');
        return;
    }   
    else {
        $check = hash('md5', $salt.htmlentities($_POST['pass']));
        if ( $check == $stored_hash ) {                     
            #error_log("Login success ".$_POST['who']);           
            $_SESSION['name'] = htmlentities($_POST['email']);
            header("Location: index.php");
            return;
        } 
        else {
            $_SESSION['failure'] = "Incorrect password";
            error_log("Login fail ".$_POST['email']." ".$_SESSION['failure']);
            header("Location: login.php");
            return;
        }
    }   
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<title>Gabriel da Silva Calasans - 420d63b1 - Login Page</title>
<?php require_once 'bootstrap.php'; ?>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if (isset($_SESSION['failure'])) {    
    echo('<p style="color: red;">'.$_SESSION['failure']."</p>\n");
    unset($_SESSION['failure']);
}
?>

<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>

</p>
</div>
</body>
</html>
