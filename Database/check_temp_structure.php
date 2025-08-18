<?php
include('connect.php');

echo "Checking temp table structure:\n";
$result = mysqli_query($con, "DESCRIBE temp");
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>
