<?php
require_once 'connect.php';

echo "Current price data in database:\n\n";

// Check wedding prices
echo "Wedding packages:\n";
$result = mysqli_query($con, "SELECT id, nm, price FROM wedding LIMIT 5");
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "  ID: {$row['id']}, Name: {$row['nm']}, Price: {$row['price']}\n";
    }
}

// Check birthday prices
echo "\nBirthday packages:\n";
$result = mysqli_query($con, "SELECT id, nm, price FROM birthday LIMIT 5");
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "  ID: {$row['id']}, Name: {$row['nm']}, Price: {$row['price']}\n";
    }
}

// Check booking prices
echo "\nBooking records:\n";
$result = mysqli_query($con, "SELECT id, nm, price FROM booking LIMIT 3");
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "  ID: {$row['id']}, Customer: {$row['nm']}, Price: {$row['price']}\n";
    }
}

echo "\nNote: Current prices appear to be in Indian Rupees (₹)\n";
echo "For Bangladeshi conversion:\n";
echo "1 INR ≈ 1.45 BDT (approximate)\n";
echo "So prices should be converted and symbol changed to ৳\n";
?>
