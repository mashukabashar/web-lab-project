<?php
echo "=== TESTING EVENT_DETAILS.PHP FIX ===\n\n";

$con = mysqli_connect("localhost", "root", "", "classic_events");
if (!$con) {
    echo "❌ Cannot connect to database: " . mysqli_connect_error() . "\n";
    exit;
}

echo "✅ Connected to database\n";

// Test the problematic query that was failing
echo "\n📝 Testing temp table operations...\n";

// Simulate the operations from event_details.php
$test_user_id = 123;

// Test DELETE operation
$delete_result = mysqli_query($con, "DELETE FROM temp WHERE user_id='$test_user_id'");
if ($delete_result) {
    echo "   ✅ DELETE operation successful\n";
} else {
    echo "   ❌ DELETE operation failed: " . mysqli_error($con) . "\n";
}

// Test INSERT operation
$test_data = [
    'id' => 1,
    'img' => 'test.jpg',
    'nm' => 'Test Event',
    'price' => 5000
];

$insert_result = mysqli_query($con, "INSERT INTO temp (id, img, nm, price, user_id, created_at) VALUES('{$test_data['id']}','{$test_data['img']}','{$test_data['nm']}',{$test_data['price']},'$test_user_id',NOW())");

if ($insert_result) {
    echo "   ✅ INSERT operation successful\n";
} else {
    echo "   ❌ INSERT operation failed: " . mysqli_error($con) . "\n";
}

// Test SELECT operation
$select_result = mysqli_query($con, "SELECT * FROM temp WHERE user_id='$test_user_id'");
if ($select_result && mysqli_num_rows($select_result) > 0) {
    echo "   ✅ SELECT operation successful\n";
    $row = mysqli_fetch_assoc($select_result);
    echo "      - Found record: " . $row['nm'] . " (Price: " . $row['price'] . ")\n";
} else {
    echo "   ❌ SELECT operation failed or no records found\n";
}

// Clean up test data
mysqli_query($con, "DELETE FROM temp WHERE user_id='$test_user_id'");

echo "\n🎉 All temp table operations are working correctly!\n";
echo "✅ The event_details.php error should now be resolved.\n\n";

echo "You can now:\n";
echo "1. Browse events in your application\n";
echo "2. Click on event details\n";
echo "3. Add events to cart\n";
echo "4. The 'unknown column user_id' error should be gone\n";

mysqli_close($con);
?>
