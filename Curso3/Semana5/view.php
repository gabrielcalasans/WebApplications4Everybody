<?php 

$sql = 'SELECT make, year, mileage, model, autos_id as id FROM autos';
if(($pdo->query($sql))->rowCount() > 0) {
    echo "<table>";
    echo "<tr>
            <th> Make </th>
            <th> Model </th>
            <th> Year </th>
            <th> Mileage </th>
            <th> Action </th>
        </tr>";
    foreach ($pdo->query($sql) as $row) {
        echo "<tr>
                <td>".$row['make']."</td>
                <td>". $row['model']."</td>
                <td>".$row['year']."</td>
                <td>".$row['mileage']."</td>
                <td> <a href = 'edit.php?id=".$row['id']."'>Edit</a> / <a href = 'delete.php?id=".$row['id']."'>Delete</a> </td>
            </tr>";

    }
    echo "</table>";
}
else {
    echo "<p>No rows found</p>";
}

