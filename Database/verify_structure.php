<?php
require_once 'Database/connect.php';

echo "<h2>Database Column Verification</h2>";

// Check booking table structure
echo "<h3>Booking Table Columns:</h3>";
$columns_result = mysqli_query($con, "SHOW COLUMNS FROM booking");

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Column</th><th>Type</th><th>Default</th></tr>";

while ($row = mysqli_fetch_assoc($columns_result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Test a sample query
echo "<h3>Sample Query Test:</h3>";
$test_query = "SELECT COUNT(*) as total_bookings, 
               SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
               SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_bookings
               FROM booking LIMIT 1";

$test_result = mysqli_query($con, $test_query);

if ($test_result) {
    $test_data = mysqli_fetch_assoc($test_result);
    echo "✅ Query successful!<br>";
    echo "Total bookings in database: " . $test_data['total_bookings'] . "<br>";
    echo "Confirmed bookings: " . $test_data['confirmed_bookings'] . "<br>";
    echo "Paid bookings: " . $test_data['paid_bookings'] . "<br>";
} else {
    echo "❌ Query failed: " . mysqli_error($con);
}

echo "<br><br><a href='dashboard.php'>Test Dashboard</a> | <a href='booking_history.php'>Test Booking History</a>";
?>
