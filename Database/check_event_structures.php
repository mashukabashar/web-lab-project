<?php
include('connect.php');

echo "=== CURRENT EVENT TABLES STRUCTURE ===\n\n";

$tables = ['wedding', 'birthday', 'anniversary', 'otherevent'];

foreach($tables as $table) {
    echo strtoupper($table) . " TABLE STRUCTURE:\n";
    $result = mysqli_query($con, "DESCRIBE $table");
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "- Table not found or error: " . mysqli_error($con) . "\n";
    }
    echo "\n";
}

echo "=== SAMPLE DATA FROM EACH TABLE ===\n\n";

foreach($tables as $table) {
    echo strtoupper($table) . " SAMPLE DATA:\n";
    $result = mysqli_query($con, "SELECT * FROM $table LIMIT 2");
    if($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            print_r($row);
        }
    } else {
        echo "- No data found in $table table\n";
    }
    echo "\n";
}
?>
