<?php
echo "=== UPDATING TEMP TABLE STRUCTURE ===\n\n";

$con = mysqli_connect("localhost", "root", "", "classic_events");
if (!$con) {
    echo "❌ Cannot connect to database: " . mysqli_connect_error() . "\n";
    exit;
}

echo "✅ Connected to database\n";

// Check current structure
echo "\n📋 Current temp table structure:\n";
$result = mysqli_query($con, "DESCRIBE temp");
while($row = mysqli_fetch_array($result)) {
    echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

// Add user_id column if it doesn't exist
$columns = [];
$result = mysqli_query($con, "DESCRIBE temp");
while($row = mysqli_fetch_array($result)) {
    $columns[] = $row['Field'];
}

if (!in_array('user_id', $columns)) {
    echo "\n📝 Adding user_id column...\n";
    $query1 = mysqli_query($con, "ALTER TABLE temp ADD COLUMN user_id INT NULL");
    if ($query1) {
        echo "   ✅ user_id column added successfully\n";
    } else {
        echo "   ❌ Error adding user_id column: " . mysqli_error($con) . "\n";
    }
} else {
    echo "\n✅ user_id column already exists\n";
}

if (!in_array('created_at', $columns)) {
    echo "\n📝 Adding created_at column...\n";
    $query2 = mysqli_query($con, "ALTER TABLE temp ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    if ($query2) {
        echo "   ✅ created_at column added successfully\n";
    } else {
        echo "   ❌ Error adding created_at column: " . mysqli_error($con) . "\n";
    }
} else {
    echo "\n✅ created_at column already exists\n";
}

// Show updated structure
echo "\n📋 Updated temp table structure:\n";
$result = mysqli_query($con, "DESCRIBE temp");
while($row = mysqli_fetch_array($result)) {
    echo "   - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n🎉 Temp table update complete!\n";
mysqli_close($con);
?>
