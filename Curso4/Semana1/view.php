<?php
session_start();

include 'pdo.php';

if(!isset($_GET['profile_id'])) {
    $_SESSION['success'] = "<p style='color: red;'>Missing profile_id</p>";
    header('Location: index.php');
    return;
}
else {
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
        echo "<p>No rows found</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Gabriel da Silva Calasans - 420d63b1 - Login Page</title>
<?php require_once 'bootstrap.php'; ?>
</head>
<body>
<div class="container">
<h1>Profile information</h1>
<p>First Name: <?= htmlentities($first) ?>
</p>
<p>Last Name: <?= htmlentities($last) ?>
</p>
<p>Email: <?= htmlentities($email) ?>
</p>
<p>Summary: <br/> <?= htmlentities($summary) ?>
</p>

<a href="index.php">Done</a>

</div>
</body>
</html>

