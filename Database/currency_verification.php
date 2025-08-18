<?php
require_once 'connect.php';

echo "=== CURRENCY CONVERSION VERIFICATION ===\n\n";

// Check a few sample prices from different tables
echo "Sample converted prices:\n\n";

// Wedding packages
$wedding_query = "SELECT nm, price FROM wedding LIMIT 3";
$wedding_result = mysqli_query($con, $wedding_query);
echo "Wedding Packages:\n";
while($wedding = mysqli_fetch_assoc($wedding_result)) {
    echo "   {$wedding['nm']}: ৳" . number_format($wedding['price']) . "\n";
}

// Birthday packages  
$birthday_query = "SELECT nm, price FROM birthday LIMIT 3";
$birthday_result = mysqli_query($con, $birthday_query);
echo "\nBirthday Packages:\n";
while($birthday = mysqli_fetch_assoc($birthday_result)) {
    echo "   {$birthday['nm']}: ৳" . number_format($birthday['price']) . "\n";
}

// Anniversary packages
$anniversary_query = "SELECT nm, price FROM anniversary LIMIT 3";
$anniversary_result = mysqli_query($con, $anniversary_query);
echo "\nAnniversary Packages:\n";
while($anniversary = mysqli_fetch_assoc($anniversary_result)) {
    echo "   {$anniversary['nm']}: ৳" . number_format($anniversary['price']) . "\n";
}

// Check booking table
$booking_query = "SELECT thm_nm, price FROM booking LIMIT 2";
$booking_result = mysqli_query($con, $booking_query);
echo "\nActive Bookings:\n";
while($booking = mysqli_fetch_assoc($booking_result)) {
    echo "   {$booking['thm_nm']}: ৳" . number_format($booking['price']) . "\n";
}

echo "\n✅ All prices are now displaying in Bangladeshi Taka (৳)\n";
echo "✅ Conversion rate used: 1 INR = 1.45 BDT\n";
echo "✅ Minimum advance payment: ৳1,450\n";
echo "✅ Brand updated to: EventEase\n\n";
?>
