<?php
require_once 'connect.php';

// Check if we have any bookings
$count_query = "SELECT COUNT(*) as total FROM booking";
$count_result = mysqli_query($con, $count_query);
$count = mysqli_fetch_assoc($count_result)['total'];

echo "Current booking count: $count\n";

if($count == 0) {
    echo "No bookings found. The booking history will show 'No bookings yet' message.\n";
    echo "Users can start booking events by visiting the gallery page.\n";
} else {
    echo "Great! There are existing bookings in the database.\n";
    echo "Users will be able to see their booking history.\n";
    
    // Show sample of existing bookings
    echo "\nSample bookings:\n";
    $sample_query = "SELECT id, nm, email, thm_nm, price, date, booking_status, payment_status FROM booking LIMIT 3";
    $sample_result = mysqli_query($con, $sample_query);
    
    while($booking = mysqli_fetch_assoc($sample_result)) {
        echo "ID: {$booking['id']}, Customer: {$booking['nm']}, Theme: {$booking['thm_nm']}, Price: ৳{$booking['price']}\n";
    }
}

echo "\n✅ Booking history system is ready to use!\n";
echo "Users can access it through:\n";
echo "1. Header 'MY BOOKINGS' button\n";
echo "2. Dashboard 'View All Bookings' card\n";
echo "3. Direct URL: booking_history.php\n";
?>
