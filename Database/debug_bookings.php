<?php
require_once 'connect.php';

echo "=== DEBUGGING BOOKING HISTORY ISSUES ===\n\n";

// Check registration table
echo "1. Registration table structure:\n";
$result = mysqli_query($con, 'DESCRIBE registration');
while($row = mysqli_fetch_assoc($result)) {
    echo "   {$row['Field']} - {$row['Type']}\n";
}

// Check booking table
echo "\n2. Booking table structure:\n";
$result = mysqli_query($con, 'DESCRIBE booking');
while($row = mysqli_fetch_assoc($result)) {
    echo "   {$row['Field']} - {$row['Type']}\n";
}

// Check registration data
echo "\n3. Registration data sample:\n";
$result = mysqli_query($con, 'SELECT id, unm, email, nm, surnm FROM registration LIMIT 5');
while($row = mysqli_fetch_assoc($result)) {
    echo "   ID: {$row['id']}, Username: {$row['unm']}, Email: {$row['email']}, Name: {$row['nm']} {$row['surnm']}\n";
}

// Check booking data
echo "\n4. Booking data sample:\n";
$result = mysqli_query($con, 'SELECT id, nm, email, thm_nm, user_id FROM booking LIMIT 5');
while($row = mysqli_fetch_assoc($result)) {
    $user_id = $row['user_id'] ?? 'NULL';
    echo "   ID: {$row['id']}, Customer: {$row['nm']}, Email: {$row['email']}, Theme: {$row['thm_nm']}, UserID: {$user_id}\n";
}

// Check for relationship issues
echo "\n5. Checking user-booking relationships:\n";
$result = mysqli_query($con, 'SELECT COUNT(*) as total_bookings FROM booking');
$total = mysqli_fetch_assoc($result)['total_bookings'];
echo "   Total bookings: {$total}\n";

$result = mysqli_query($con, 'SELECT COUNT(*) as with_user_id FROM booking WHERE user_id IS NOT NULL AND user_id != 0');
$with_user_id = mysqli_fetch_assoc($result)['with_user_id'];
echo "   Bookings with user_id: {$with_user_id}\n";

$result = mysqli_query($con, 'SELECT COUNT(*) as without_user_id FROM booking WHERE user_id IS NULL OR user_id = 0');
$without_user_id = mysqli_fetch_assoc($result)['without_user_id'];
echo "   Bookings without user_id: {$without_user_id}\n";

echo "\n6. Issues identified:\n";
if($without_user_id > 0) {
    echo "   ❌ Problem: {$without_user_id} bookings have no user_id!\n";
    echo "   ❌ This means bookings are not properly linked to users\n";
} else {
    echo "   ✅ All bookings have user_id\n";
}

echo "\n=== END DEBUG INFO ===\n";
?>
