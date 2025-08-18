<?php
require_once 'connect.php';

echo "=== WEDDING TABLE CHECK ===\n";

// Check if wedding table exists
$result = mysqli_query($con, "SHOW TABLES LIKE 'wedding'");
if(mysqli_num_rows($result) == 0) {
    echo "ERROR: Wedding table does not exist!\n";
    exit;
}

echo "✓ Wedding table exists\n\n";

// Show table structure
echo "=== WEDDING TABLE STRUCTURE ===\n";
$result = mysqli_query($con, 'DESCRIBE wedding');
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . ' - ' . $row['Type'] . "\n";
    }
} else {
    echo "Error describing table: " . mysqli_error($con) . "\n";
}

echo "\n=== WEDDING TABLE DATA ===\n";
$result = mysqli_query($con, 'SELECT * FROM wedding LIMIT 5');
if($result) {
    echo "Number of wedding records: " . mysqli_num_rows($result) . "\n";
    while($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id'] . " - Name: " . $row['nm'] . " - Price: " . $row['price'] . "\n";
    }
} else {
    echo "Error querying wedding data: " . mysqli_error($con) . "\n";
}
?>
