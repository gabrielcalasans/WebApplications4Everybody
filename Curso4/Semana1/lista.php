<?php 
if(isset($_SESSION['user_id'])) {
    $sql = 'SELECT * FROM profile';
    if(($pdo->query($sql))->rowCount() > 0) {
        echo "<table>";
        echo "<tr>
                <th> Name </th>
                <th> Headline </th>
                <th> Action </th>            
            </tr>";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>
                    <td><a href = 'view.php?profile_id=".$row['profile_id']."'>".$row['first_name']. " ".$row['last_name']."</a></td>
                    <td>". $row['headline']."</td>                
                    <td> <a href = 'edit.php?profile_id=".$row['profile_id']."'>Edit</a> / <a href = 'delete.php?profile_id=".$row['profile_id']."'>Delete</a> </td>
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
        echo "<table>";
        echo "<tr>
                <th> Name </th>
                <th> Headline </th>                          
            </tr>";
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

