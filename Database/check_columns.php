<?php
require_once 'connect.php';

echo "Wedding table columns:\n";
$result = mysqli_query($con, 'SHOW COLUMNS FROM wedding');
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
?>
