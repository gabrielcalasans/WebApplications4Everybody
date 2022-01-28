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
<title>Gabriel da Silva Calasans - 60e7705f </title>
<?php require_once 'bootstrap.php'; ?>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card col-md-6">
                <div class="card-header bg-primary text-light">
                    <h2>Profile information</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                    
                        <fieldset disabled>
                            <div class="col-12">
                                <label class="col-form-label" for="first">First Name</label>
                                <input type="text" class="form-control" value="<?= htmlentities($first); ?>" name="first_name" id="first">
                            </div>

                            <div class="col-12">
                                <label class="col-form-label" for="model">Last</label>
                                <input type="text" class="form-control" value = "<?= htmlentities($last); ?>" name="last_name" id="last">                             
                            </div>
                            <div class="col-12">
                                <label class="col-form-label" for="email">Email</label>
                                <input type="text" class="form-control" value = "<?= htmlentities($email); ?>" name="email" id="email">                               
                            </div>
                            <div class="col-12">
                                <label class="col-form-label" for="headline">Headline</label>
                                <input type="text" class="form-control" name="headline" value="<?= htmlentities($headline); ?>" id="headline">                              
                            </div>
                            <div class="col-12">
                                <label class="col-form-label" for="summary">Summary</label>
                                <textarea name="summary" class="form-control" id="summary" rows="8" cols="80"><?= htmlentities($summary);?></textarea>
                            </div>
                            <div class="col-12">
                                    <div class="row" id="edu_fields">
                                        <label class="col-form-label" for="edu_lista">Education</label>
                                            <ul class="list-group list-group-flush col-12 col-md-6 offset-md-1" id="edu_lista">
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
                                                        foreach ($pdo->query($sql) as $row) {
                                                            $rank = $row['edu_rank'];
                                                            $year = $row['edu_year'];
                                                            $school = htmlentities($row['school_name']);

                                                            echo '<li class = "list-group-item"><div class="col-12" id="edu_rank'.$rank.'">';
                                                                echo $year. ' : '.$school;
                                                            echo '</div></li>';

                                                        }


                                                    } 
                                                ?>
                                        </ul>
                                    </div>
                                </div>
                            <div class="col-12">
                                    <div class="row" id="position_fields">
                                        <label class="col-form-label" for="lista">Position</label>
                                            <ul class="list-group list-group-flush col-12 col-md-6 offset-md-1" id="lista">
                                                <?php
                                                    $sql = 'SELECT * FROM position WHERE profile_id = "'. $id.'"';
                                                    if(($pdo->query($sql))->rowCount() > 0) {        
                                                        foreach ($pdo->query($sql) as $row) {
                                                            $rank = $row['rank'];
                                                            $year = $row['year'];
                                                            $description = htmlentities($row['description']);

                                                            echo '<li class = "list-group-item"><div class="col-12" id="position'.$rank.'">';
                                                                echo $year. ' : '.$description;
                                                            echo '</div></li>';

                                                        }


                                                    } 
                                                ?>
                                        </ul>
                                    </div>
                                </div>
                                
                        </fieldset>
                        <div class="col-12">
                                <a class="btn btn-sm btn-primary" href="index.php">Done</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>




</div>
</body>
</html>

