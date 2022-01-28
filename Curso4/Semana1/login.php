<?php 
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: logout.php");
    return;
}
include 'pdo.php';
$salt = 'XyZzy12*_';
#$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // php123 is pass

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {    
        $check = hash('md5', $salt.htmlentities($_POST['pass']));
        $stmt = $pdo->prepare("SELECT * FROM users WHERE password=:pw AND email = :em");
        $stmt->execute([
                        'pw' => $check,
                        'em' => htmlentities($_POST['email']),
                      ]);
        if($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch()) {
                $name = $row['name'];
                $id = $row['user_id'];
                $email = $row['email'];
            }
            $_SESSION['name'] = $name;
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            return;
                       
        }
        else {
            $_SESSION['failure'] = "Email or password are wrong!";
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

<form method="POST" action="login.php">
<label for="nam">User Name</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>

<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>

</p>
</div>
</body>
</html>
