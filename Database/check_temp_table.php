<?php
require_once 'connect.php';

echo "=== TEMP TABLE STRUCTURE ===\n";
$result = mysqli_query($con, 'DESCRIBE temp');
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . ' - ' . $row['Type'] . "\n";
    }
} else {
    echo 'Table temp does not exist or error: ' . mysqli_error($con) . "\n";
}

echo "\n=== TEMP TABLE DATA ===\n";
$data = mysqli_query($con, 'SELECT * FROM temp');
if($data) {
    while($row = mysqli_fetch_assoc($data)) {
        print_r($row);
        echo "\n";
    }
} else {
    echo 'No data or error: ' . mysqli_error($con) . "\n";
}
?>
