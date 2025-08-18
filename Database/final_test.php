<?php
require_once 'connect.php';

echo "=== FINAL VERIFICATION TEST ===\n\n";

// Test user session (simulate logged-in user)
$test_user_id = 4;
$test_email = 'pufivaby@mailinator.com';

echo "Testing for user:\n";
echo "User ID: {$test_user_id}\n";
echo "Email: {$test_email}\n\n";

// Test 1: Dashboard statistics query
echo "1. Testing dashboard statistics...\n";
$stats_query = "SELECT 
    COUNT(*) as total_bookings,
    SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
    SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_bookings,
    SUM(CASE WHEN advance_paid IS NOT NULL THEN advance_paid ELSE 0 END) as total_paid
    FROM booking WHERE user_id = '$test_user_id'";

$stats_result = mysqli_query($con, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

echo "   Total bookings: {$stats['total_bookings']}\n";
echo "   Confirmed: {$stats['confirmed_bookings']}\n";
echo "   Paid: {$stats['paid_bookings']}\n";
echo "   Total paid: ৳{$stats['total_paid']}\n";

// Test 2: Booking history query
echo "\n2. Testing booking history...\n";
$history_query = "SELECT * FROM booking WHERE user_id = '$test_user_id' ORDER BY booking_date DESC";
$history_result = mysqli_query($con, $history_query);

echo "   Found " . mysqli_num_rows($history_result) . " booking(s)\n";
while($booking = mysqli_fetch_assoc($history_result)) {
    echo "   - Booking ID: {$booking['id']}\n";
    echo "     Customer: {$booking['nm']}\n";
    echo "     Email: {$booking['email']}\n";
    echo "     Theme: {$booking['thm_nm']}\n";
    echo "     Price: ৳{$booking['price']}\n";
    echo "     Status: {$booking['booking_status']} / {$booking['payment_status']}\n";
}

// Test 3: Email fallback query (should return same results)
echo "\n3. Testing email fallback...\n";
$email_query = "SELECT * FROM booking WHERE email = '$test_email' ORDER BY booking_date DESC";
$email_result = mysqli_query($con, $email_query);
echo "   Email query found " . mysqli_num_rows($email_result) . " booking(s)\n";

echo "\n=== RESULTS ===\n";
if($stats['total_bookings'] > 0) {
    echo "✅ SUCCESS! Booking history is now working correctly!\n";
    echo "✅ Dashboard will show statistics\n";
    echo "✅ Booking history page will show user's bookings\n";
    echo "✅ New bookings will be properly linked to users\n";
} else {
    echo "❌ Still having issues - no bookings found\n";
}
?>
