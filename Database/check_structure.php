<?php
require_once 'connect.php';

echo "Checking booking table structure:\n";
$result = mysqli_query($con, 'DESCRIBE booking');
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}

echo "\nSample booking data:\n";
$result = mysqli_query($con, 'SELECT * FROM booking LIMIT 1');
if(mysqli_num_rows($result) > 0) {
    $booking = mysqli_fetch_assoc($result);
    foreach($booking as $key => $value) {
        echo "$key: $value\n";
    }
}
?>
