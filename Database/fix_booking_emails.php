<?php
require_once 'connect.php';

echo "=== FIXING EXISTING BOOKING DATA ===\n\n";

// Get all bookings with user_id
$bookings_query = "SELECT b.id, b.email as booking_email, b.user_id, r.email as user_email, r.nm, r.surnm 
                   FROM booking b 
                   LEFT JOIN registration r ON b.user_id = r.id 
                   WHERE b.user_id IS NOT NULL AND b.user_id != 0";

$bookings_result = mysqli_query($con, $bookings_query);

if(mysqli_num_rows($bookings_result) > 0) {
    echo "Found bookings to potentially fix:\n\n";
    
    $fixed_count = 0;
    while($booking = mysqli_fetch_assoc($bookings_result)) {
        echo "Booking ID: {$booking['id']}\n";
        echo "  Current email: {$booking['booking_email']}\n";
        echo "  User's actual email: {$booking['user_email']}\n";
        
        if($booking['booking_email'] != $booking['user_email']) {
            echo "  ❌ Email mismatch! Fixing...\n";
            
            $update_query = "UPDATE booking SET email = '{$booking['user_email']}' WHERE id = {$booking['id']}";
            if(mysqli_query($con, $update_query)) {
                echo "  ✅ Fixed!\n";
                $fixed_count++;
            } else {
                echo "  ❌ Error: " . mysqli_error($con) . "\n";
            }
        } else {
            echo "  ✅ Email already correct\n";
        }
        echo "\n";
    }
    
    echo "=== SUMMARY ===\n";
    echo "Fixed {$fixed_count} booking records\n";
    
} else {
    echo "No bookings found with user_id\n";
}

echo "\n=== VERIFICATION ===\n";
echo "Checking booking history will now work correctly...\n";

// Test the fixed query
$test_user_id = 4; // The user we know has a booking
$test_query = "SELECT id, nm, email, thm_nm FROM booking WHERE user_id = $test_user_id";
$test_result = mysqli_query($con, $test_query);

echo "Bookings for user_id $test_user_id:\n";
while($booking = mysqli_fetch_assoc($test_result)) {
    echo "  ID: {$booking['id']}, Customer: {$booking['nm']}, Email: {$booking['email']}, Theme: {$booking['thm_nm']}\n";
}

echo "\n✅ Booking history should now work properly!\n";
?>
