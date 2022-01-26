<title>Gabriel da Silva Calasans 671293ef </title>
<h1>Welcome to my guessing game</h1>
<?php

if (empty($_GET)){
    echo "Missing guess parameter";
}
else {
    
    if(is_numeric($_GET['guess'])) {        
        if($_GET['guess'] == 38) {
            echo "Congratulations - You are right";
        }
        
        else if($_GET['guess'] > 38) {
            echo "Your guess is too high";
        }
        
        else {
            echo "Your guess is too low";
        }
    }
    
    else {
       if($_GET['guess'] == "") {
            echo "Your guess is too short";
        }
        else {
            echo "Your guess is not a number";
        }
    }
}



?>