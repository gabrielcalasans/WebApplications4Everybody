<?php
session_start();

/*
SELECT education.rank, education.year, institution.name
FROM education
INNER JOIN institution
ON education.institution_id = institution.institution_id
INNER JOIN profile
ON education.profile_id = profile.profile_id
*/

if ( ! isset($_SESSION['user_id']) ) {
  die("ACCESS DENIED");
}
else {
    $user_id = $_SESSION['user_id'];
    $user = $_SESSION['name'];
}


if (isset($_POST['cancel']) ) {
  header('Location: index.php');
  return;
}

include 'pdo.php';
include 'functions.php';

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
        header("Location: edit.php?profile_id=".$id);
        return;
    }
    else if(strpos($_POST['email'], '@') === false){
        $_SESSION['failure'] = "<font color = 'red'>Email address must contain @</font>";
        header("Location: edit.php?profile_id=".$id);
        return;
    }
    else if (validatePos() !== True) {
        $_SESSION['failure'] = "<font color = 'red'>".validatePos()."</font>";
        header("Location: edit.php?profile_id=".$id);
        return;
    }
    else if (validateEdu() !== True) {
        $_SESSION['failure'] = "<font color = 'red'>".validateEdu()."</font>";
        header("Location: edit.php?profile_id=".$id);
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
        
        $stmt = $pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $id));
        
        $rank = 1;
        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;

            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');

            $stmt->execute(array(
              ':pid' => $id,
              ':rank' => $rank,
              ':year' => $year,
              ':desc' => $desc)
            );

            $rank++;    
        }
        
        
        $stmt = $pdo->prepare('DELETE FROM education WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $id));
        
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
              ':pid' => $id,
              ':iid' => $inst_id,
              ':rank' => $rank,
              ':year' => $year
            ));            
            $rank++;    
        }
        
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
        <title>Gabriel da Silva Calasans 60e7705f </title>
    
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header bg-warning text-light">
                            <h2>Editing entry for user <?= htmlentities($user); ?></h2>
                        </div>
                        <div class="card-body">
                            <form class="row g-3" method = "POST">
                                <div class="col-md-6">
                                    <label class="col-form-label" for="first">First Name</label>
                                    <input type="text" class="form-control" value="<?= htmlentities($first); ?>" name="first_name" id="first">                                 
                                </div>

                                <div class="col-md-6">
                                    <label class="col-form-label" for="model">Last</label>
                                    <input type="text" class="form-control" value = "<?= htmlentities($last); ?>" name="last_name" id="last">                             
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="email">Email</label>
                                    <input type="text" class="form-control" value = "<?= htmlentities($email); ?>" name="email" id="email">                               
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label" for="headline">Headline</label>
                                    <input type="text" class="form-control" name="headline" value="<?= htmlentities($headline); ?>" id="headline">                              
                                </div>
                                <div class="col-12">
                                    <label class="col-form-label" for="summary">Summary</label>
                                    <textarea name="summary" class="form-control" id="summary" rows="8" cols="80"><?= htmlentities($summary);?></textarea>
                                </div>
                                <div class="col-12">
                                    Education <input class="btn btn-sm btn-info" type="submit" id="addEdu" value="+">
                                </div>
                                <div class="col-12">
                                    <div class="row g-4" id="edu_fields">
                                        <?php
                                            $sql = 'SELECT education.rank as edu_rank, education.year as edu_year,
                                                           institution.name as school_name
                                                    FROM education
                                                    INNER JOIN institution
                                                    ON education.institution_id = institution.institution_id
                                                    INNER JOIN profile
                                                    ON education.profile_id = profile.profile_id
                                                    WHERE profile.profile_id = "'.$id.'"';
                                            $result = $pdo->query($sql);
                                            if($result->rowCount() > 0) {        
                                                foreach ($result as $row) {
                                                    $rank = $row['edu_rank'];
                                                    $year = $row['edu_year'];
                                                    $school = htmlentities($row['school_name']);
                                                    
                                                    echo '<div class="col-12" id="edu'.$rank.'">';
                                                        echo '<div class="row g-2">';
                                                            echo '<div class="col-md-1 form-control-label">Year</div>';
                                                            echo '<div class="col-12 col-md-2">';
                                                                echo '<input type="text" class="form-control" id="posid'.$rank.'" name="edu_year'.$rank.'" value="'.$year.'" />';
                                                            echo '</div>';
                                                            echo '<div class="col-md-1">';
                                                                echo '<input type="button" class="btn btn-danger form-control" value="-" onclick="$(\'#edu'.$rank.'\').remove();return false;">';
                                                            echo '</div>';
                                                            echo '<div class="col-md-8"></div>';
                                                            echo '<div class="col-md-1 form-control-label"> School </div>';
                                                            echo '<div class="col-md-8 ui-widget">';
                                                                echo '<input type="text" size="80" name="edu_school'.$rank.'" class="school ui-autocomplete-input form-control" value="'.$school.'"  autocomplete = "off" />';
                                                            echo '</div>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                    
                                                }
                                                echo '<script>countEdu = '.$rank.'; console.log(countEdu);</script>';

                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    Position <input class="btn btn-sm btn-info" type="submit" id="addPos" value="+">
                                </div>
                                <div class="col-12">
                                    <div class="row g-4" id="position_fields">
                                        <?php
                                            $sql = 'SELECT * FROM position WHERE profile_id = "'. $id.'"';
                                            $result = $pdo->query($sql);
                                            if($result->rowCount() > 0) {        
                                                foreach ($result as $row) {
                                                    $rank = $row['rank'];
                                                    $year = $row['year'];
                                                    $description = htmlentities($row['description']);
                                                    
                                                    echo '<div class="col-12" id="position'.$rank.'">';
                                                        echo '<div class="row g-2">';
                                                            echo '<div class="col-md-1 form-control-label">Year</div>';
                                                            echo '<div class="col-12 col-md-2">';
                                                                echo '<input type="text" class="form-control" id="posid'.$rank.'" name="year'.$rank.'" value="'.$year.'" />';
                                                            echo '</div>';
                                                            echo '<div class="col-md-1">';
                                                                echo '<input type="button" class="btn btn-danger form-control" value="-" onclick="$(\'#position'.$rank.'\').remove();return false;">';
                                                            echo '</div>';
                                                            echo '<div class="col-md-8"></div>';
                                                            echo '<div class="col-12">';
                                                                echo '<textarea class="form-control" name="desc'.$rank.'" rows="8" cols="80">'.$description.'</textarea>';
                                                            echo '</div>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                    
                                                }
                                                echo '<script>countPos = '.$rank.'; console.log(countPos);</script>';

                                            } 
                                        ?>
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
                                    <input class="btn btn-sm btn-success" type="submit" value="Save">
                                    <input class="btn btn-sm btn-danger" type="submit" name="cancel" value="Cancel">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        

        // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
        $(document).ready(function(){
            window.console && console.log('Document ready called');
            if (typeof countEdu === 'undefined') {
                countEdu = 0;
            }
            if (typeof countPos === 'undefined') {
                countPos = 0;
            }
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
            
                $('.school').autocomplete({
                    source: "school.php"
                });
        });
        </script>
        
    </body> 
</html>