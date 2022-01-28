<?php 
if(isset($_SESSION['user_id'])) {
    $sql = 'SELECT * FROM profile';
    if(($pdo->query($sql))->rowCount() > 0) {
        echo "<table class = 'table table-striped table-hover'>";
        echo "<thead>
                <tr>
                    <th scope='col'> Name </th>
                    <th scope='col'> Headline </th>
                    <th scope='col'> Action </th>            
                </tr>
            </thead>";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>
                    <td><a href = 'view.php?profile_id=".$row['profile_id']."'>".$row['first_name']. " ".$row['last_name']."</a></td>
                    <td>". $row['headline']."</td>                
                    <td> <a class='btn btn-sm btn-warning' href = 'edit.php?profile_id=".$row['profile_id']."'>Edit</a> <a class='btn btn-sm btn-danger' href = 'delete.php?profile_id=".$row['profile_id']."'>Delete <span>&times</span></a> </td>
                </tr>";

        }
        echo "</table>";
    }
    else {
        echo "<p>No rows found</p>";
    }
}
else {
    $sql = 'SELECT * FROM profile';
    if(($pdo->query($sql))->rowCount() > 0) {
        echo "<table class = 'table table-striped table-hover'>";
        echo "<thead>
                <tr>
                    <th scope='col'> Name </th>
                    <th scope='col'> Headline </th>                          
                </tr>
            </thead>";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>
                    <td><a href = 'view.php?profile_id=".$row['profile_id']."'>".$row['first_name']. " ".$row['last_name']."</a></td>
                    <td>". $row['headline']."</td> 
                </tr>";

        }
        echo "</table>";
    }
    else {
        echo "<p>No rows found</p>";
    }
}

