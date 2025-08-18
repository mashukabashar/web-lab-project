<?php
// Database setup script to ensure booking table has required columns
include('../Database/connect.php');

echo "<h2>Database Setup for Booking Approval System</h2>\n";

// Function to check if column exists
function columnExists($con, $table, $column) {
    $result = mysqli_query($con, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    return mysqli_num_rows($result) > 0;
}

// Function to add column if it doesn't exist
function addColumnIfNotExists($con, $table, $column, $definition) {
    if (!columnExists($con, $table, $column)) {
        $query = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        if (mysqli_query($con, $query)) {
            echo "<p style='color: green;'>✓ Added column '$column' to table '$table'</p>\n";
            return true;
        } else {
            echo "<p style='color: red;'>✗ Failed to add column '$column': " . mysqli_error($con) . "</p>\n";
            return false;
        }
    } else {
        echo "<p style='color: blue;'>ℹ Column '$column' already exists in table '$table'</p>\n";
        return true;
    }
}

echo "<h3>Checking and updating booking table structure...</h3>\n";

// Add necessary columns for booking approval system
$columns_to_add = array(
    'booking_status' => "varchar(20) DEFAULT 'pending'",
    'payment_status' => "varchar(20) DEFAULT 'unpaid'",
    'advance_paid' => "decimal(10,2) DEFAULT 0.00",
    'remaining_amount' => "decimal(10,2) DEFAULT 0.00",
    'booking_date' => "datetime DEFAULT CURRENT_TIMESTAMP",
    'user_id' => "int(11) DEFAULT NULL"
);

foreach ($columns_to_add as $column => $definition) {
    addColumnIfNotExists($con, 'booking', $column, $definition);
}

// Update existing records with null booking_status
echo "<h3>Updating existing records...</h3>\n";
$update_query = "UPDATE booking SET booking_status = 'pending' WHERE booking_status IS NULL OR booking_status = ''";
if (mysqli_query($con, $update_query)) {
    $affected_rows = mysqli_affected_rows($con);
    echo "<p style='color: green;'>✓ Updated $affected_rows records with pending status</p>\n";
} else {
    echo "<p style='color: red;'>✗ Failed to update records: " . mysqli_error($con) . "</p>\n";
}

// Calculate remaining amounts for existing bookings
echo "<h3>Calculating remaining amounts...</h3>\n";
$calc_query = "UPDATE booking SET remaining_amount = (price - advance_paid) WHERE remaining_amount = 0 AND price > 0";
if (mysqli_query($con, $calc_query)) {
    $affected_rows = mysqli_affected_rows($con);
    echo "<p style='color: green;'>✓ Calculated remaining amounts for $affected_rows records</p>\n";
} else {
    echo "<p style='color: red;'>✗ Failed to calculate remaining amounts: " . mysqli_error($con) . "</p>\n";
}

echo "<h3>Database setup complete!</h3>\n";
echo "<p><a href='view_order.php'>Go to Booking Requests</a></p>\n";

// Display current booking table structure
echo "<h3>Current booking table structure:</h3>\n";
$structure_query = "DESCRIBE booking";
$structure_result = mysqli_query($con, $structure_query);

if ($structure_result) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
    while ($row = mysqli_fetch_assoc($structure_result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
}
?>
