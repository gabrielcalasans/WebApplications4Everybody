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
include 'functions.php';

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
    else if (validatePos() !== True) {
        $_SESSION['failure'] = "<font color = 'red'>".validatePos()."</font>";
        header("Location: add.php");
        return;
    }
    else if (validateEdu() !== True) {
        $_SESSION['failure'] = "<font color = 'red'>".validateEdu()."</font>";
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
        
        $profile_id = $pdo->lastInsertId();
        
        $rank = 1;
        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;

            $year = $_POST['year'.$i];
            $desc = htmlentities($_POST['desc'.$i]);

            $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
            $stmt->execute(array(
              ':pid' => $profile_id,
              ':rank' => $rank,
              ':year' => $year,
              ':desc' => $desc)
            );

            $rank++;    
        }
        
        $rank = 1;
        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['edu_year'.$i]) ) continue;
            if ( ! isset($_POST['edu_school'.$i]) ) continue;

            $year = $_POST['edu_year'.$i];
            $school = htmlentities($_POST['edu_school'.$i]);
            
            $sql = 'SELECT * FROM institution WHERE name = "'. $school.'"';
            $result = $pdo->query($sql);
            if($result->rowCount() > 0) {        
                foreach ($result as $row) {
                    $inst_id = $row['institution_id'];
                }
            }
            else {
                $stmt = $pdo->prepare('INSERT INTO institution (name) VALUES (:school)');
                $stmt->execute(array(
                    ':school' => $school)
                );
                $inst_id = $pdo->lastInsertId();
            }
            
            
            
            $stmt = $pdo->prepare('INSERT INTO education (profile_id, institution_id, rank, year) VALUES ( :pid, :iid, :rank, :year)');
            $stmt->execute(array(
              ':pid' => $profile_id,
              ':iid' => $inst_id,
              ':rank' => $rank,
              ':year' => $year
            ));            
            $rank++;    
        }
        
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
        <title>Gabriel da Silva Calasans 60e7705f</title>
        <style>
            .ui-helper-hidden-accessible { display:none; }
        </style>
    
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header bg-secondary text-light">
                            <h2>Add a new entry for user <?= htmlentities($user); ?></h2>
                        </div>
                        <div class="card-body">
                            <form class="row g-3" method = "POST">
                                <div class="col-md-6">
                                    <label class="col-form-label" for="first">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first">                                 
                                </div>

                                <div class="col-md-6">
                                    <label class="col-form-label" for="model">Last</label>
                                    <input type="text" class="form-control" name="last_name" id="last">                             
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="email">Email</label>
                                    <input type="text" class="form-control" name="email" id="email">                               
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="headline">Headline</label>
                                    <input type="text" class="form-control" name="headline" id="headline">                              
                                </div>
                                <div class="col-12">
                                    <label class="col-form-label" for="summary">Summary</label>
                                    <textarea name="summary" class="form-control" id="summary" rows="8" cols="80"></textarea>
                                </div>
                                <div class="col-12">
                                    Education <input class="btn btn-sm btn-info" type="submit" id="addEdu" value="+">
                                </div>
                                <div class="col-12">
                                    <div class="row g-4" id="edu_fields">
                                    </div>
                                </div>
                                <div class="col-12">
                                    Position <input class="btn btn-sm btn-info" type="submit" id="addPos" value="+">
                                </div>
                                <div class="col-12">
                                    <div class="row g-4" id="position_fields">
                                    </div>
                                </div>
                                <?php
                                if (isset($_SESSION['failure'])) {
                                    echo "<div class ='col-12'>";
                                    echo('<p>'.$_SESSION['failure']."</p>\n");
                                    echo "</div>";
                                    unset($_SESSION['failure']);
                                }            
                                ?>
                                <div class="col-12">
                                    <input class="btn btn-sm btn-success" type="submit" value="Add">
                                    <input class="btn btn-sm btn-danger" type="submit" name="cancel" value="Cancel">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        /*
        var autoCompleteSource = new Array();       
        function updateSource(edu_id){            
            texto = $("#edu_id"+edu_id).val();
            //console.log($("edu_id"+id).val());
            $.getJSON('consulta.php?term='+texto, function(rowz) {
                autoCompleteSource = rowz;
                console.log(rowz);
                $("#edu_id"+edu_id).keyup(function() {
                    //setTimeout('updateSource('+edu_id+')', 3000);
                    updateSource(edu_id);
                });
                
            });
        }    
           
          */  
           
           
            
        
        countPos = 0;
        countEdu = 0;
        // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
        $(document).ready(function(){
            $.ajaxSetup({ cache: false });
            //updateSource();
            window.console && console.log('Document ready called');
            $('#addPos').click(function(event){
                // http://api.jquery.com/event.preventdefault/
                event.preventDefault();
                if ( countPos >= 9 ) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position "+countPos);
                $('#position_fields').append(
                    '<div class="col-12" id="position'+countPos+'">\
                        <div class="row g-2"> \
                            <div class="col-md-1 form-control-label">Year</div>\
                                <div class="col-12 col-md-2"> \
                                    <input type="text" class="form-control" id="posid'+countPos+'" name="year'+countPos+'" value="" /> \
                                </div>\
                                 <div class="col-md-1">\
                                    <input type="button" class="btn btn-danger form-control" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;">\
                                </div>   \
                                <div class="col-md-8"></div>\
                                <div class="col-12"> \
                                    <textarea class="form-control" name="desc'+countPos+'" rows="8" cols="80"></textarea> \
                                </div>\
                            </div>\
                    </div>');
            });
            
             $('#addEdu').click(function(event){
                event.preventDefault();
                if ( countEdu >= 9 ) {
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console && console.log("Adding education "+countEdu);

                $('#edu_fields').append(
                    '<div class="col-12" id="edu'+countEdu+'"> \
                        <div class="row g-2"> \
                            <div class="col-md-1 form-control-label"> Year </div>\
                            <div class="col-12 col-md-2">\
                                <input class="form-control" type="text" name="edu_year'+countEdu+'" value="" />\
                            </div> \
                            <div class="col-md-1"> \
                                <input class="btn btn-danger" type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"> \
                            </div> \
                            <div class="col-md-8"></div> \
                            <div class="col-md-1 form-control-label"> School </div> \
                            <div class="col-md-8 ui-widget"> \
                                <input type="text" size="80" name="edu_school'+countEdu+'" class="school ui-autocomplete-input form-control" value  autocomplete = "off" />\
                            </div> \
                        </div>\
                    </div>'
                );
                 
                $('.school').autocomplete({
                    source: "school.php"
                });

            });
            
            
            
            
            
            
            
            
            
            //onfocus="updateSource('+countEdu+'); return false;" 
            
            
        });
        </script>
        
    </body> 
</html>