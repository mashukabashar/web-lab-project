<?php
require_once 'connect.php';

echo "=== UPDATING TEMP TABLE FOR MULTI-USER SUPPORT ===\n";

// Add user_id column to temp table if it doesn't exist
$check_column = mysqli_query($con, "SHOW COLUMNS FROM temp LIKE 'user_id'");
if(mysqli_num_rows($check_column) == 0) {
    $add_column = mysqli_query($con, "ALTER TABLE temp ADD COLUMN user_id INT DEFAULT NULL");
    if($add_column) {
        echo "✅ Added user_id column to temp table\n";
    } else {
        echo "❌ Failed to add user_id column: " . mysqli_error($con) . "\n";
    }
} else {
    echo "✅ user_id column already exists in temp table\n";
}

// Also add created_at column for better tracking
$check_created = mysqli_query($con, "SHOW COLUMNS FROM temp LIKE 'created_at'");
if(mysqli_num_rows($check_created) == 0) {
    $add_created = mysqli_query($con, "ALTER TABLE temp ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    if($add_created) {
        echo "✅ Added created_at column to temp table\n";
    } else {
        echo "❌ Failed to add created_at column: " . mysqli_error($con) . "\n";
    }
} else {
    echo "✅ created_at column already exists in temp table\n";
}

echo "\n=== UPDATED TEMP TABLE STRUCTURE ===\n";
$result = mysqli_query($con, 'DESCRIBE temp');
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . ' - ' . $row['Type'] . "\n";
}

echo "\n✅ Temp table is now ready for multi-user support!\n";
?>
