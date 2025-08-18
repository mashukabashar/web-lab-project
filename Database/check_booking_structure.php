<?php
require_once 'connect.php';

echo "=== BOOKING TABLE STRUCTURE ===\n";
$result = mysqli_query($con, 'DESCRIBE booking');
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}

echo "\n=== SAMPLE BOOKING DATA ===\n";
$bookings = mysqli_query($con, 'SELECT * FROM booking LIMIT 3');
while($booking = mysqli_fetch_assoc($bookings)) {
    print_r($booking);
    echo "\n";
}
?>
