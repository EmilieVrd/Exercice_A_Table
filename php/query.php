<?php
//include("connect.php");


$q = intval($_GET['q']);

$con = mysqli_connect('localhost', 'root', '', 'resto');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con, "ajax_demo");
$sql = "SELECT * FROM tables WHERE table_qr = '" . $q . "'";
$result = mysqli_query($con, $sql);

echo "<table>
<tr>
<th>nb_chairs</th>
<th>emplacement</th>
<th>table_qr</th>
</tr>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['nb_chairs'] . "</td>";
    echo "<td>" . $row['emplacement'] . "</td>";
    echo "<td>" . $row['table_qr'] . "</td>";
    //echo "<td>" . $row['Hometown'] . "</td>";
    //echo "<td>" . $row['Job'] . "</td>";
    echo "</tr>";
}
echo "</table>";
mysqli_close($con);

?>
