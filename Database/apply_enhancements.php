<?php
require_once 'connect.php';

echo "Applying booking enhancements and fixing email column...\n";

// First, fix the email column length issue
echo "Fixing email column length in all tables...\n";
$email_fixes = [
    "ALTER TABLE `booking` MODIFY COLUMN `email` varchar(100) NOT NULL",
    "ALTER TABLE `registration` MODIFY COLUMN `email` varchar(100) NOT NULL", 
    "ALTER TABLE `feedback` MODIFY COLUMN `email` varchar(100) NOT NULL"
];

foreach ($email_fixes as $query) {
    if (mysqli_query($con, $query)) {
        echo "✅ Successfully updated email column length\n";
    } else {
        echo "❌ Error updating email column: " . mysqli_error($con) . "\n";
    }
}

// Check if columns already exist
$check_queries = [
    'booking_status' => "SHOW COLUMNS FROM booking LIKE 'booking_status'",
    'payment_status' => "SHOW COLUMNS FROM booking LIKE 'payment_status'",
    'advance_paid' => "SHOW COLUMNS FROM booking LIKE 'advance_paid'",
    'remaining_amount' => "SHOW COLUMNS FROM booking LIKE 'remaining_amount'",
    'booking_date' => "SHOW COLUMNS FROM booking LIKE 'booking_date'",
    'user_id' => "SHOW COLUMNS FROM booking LIKE 'user_id'"
];

// Add missing columns
$alter_queries = [
    'booking_status' => "ALTER TABLE `booking` ADD COLUMN `booking_status` varchar(20) DEFAULT 'pending'",
    'payment_status' => "ALTER TABLE `booking` ADD COLUMN `payment_status` varchar(20) DEFAULT 'unpaid'",
    'advance_paid' => "ALTER TABLE `booking` ADD COLUMN `advance_paid` decimal(10,2) DEFAULT 0.00",
    'remaining_amount' => "ALTER TABLE `booking` ADD COLUMN `remaining_amount` decimal(10,2) DEFAULT 0.00",
    'booking_date' => "ALTER TABLE `booking` ADD COLUMN `booking_date` datetime DEFAULT CURRENT_TIMESTAMP",
    'user_id' => "ALTER TABLE `booking` ADD COLUMN `user_id` int(11)"
];

foreach ($check_queries as $column => $check_query) {
    $result = mysqli_query($con, $check_query);
    if (mysqli_num_rows($result) == 0) {
        echo "Adding column: $column\n";
        if (mysqli_query($con, $alter_queries[$column])) {
            echo "✅ Successfully added: $column\n";
        } else {
            echo "❌ Error adding $column: " . mysqli_error($con) . "\n";
        }
    } else {
        echo "✅ Column already exists: $column\n";
    }
}

// Create payments table if it doesn't exist
$payments_check = mysqli_query($con, "SHOW TABLES LIKE 'payments'");
if (mysqli_num_rows($payments_check) == 0) {
    echo "Creating payments table...\n";
    $payments_sql = "CREATE TABLE `payments` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `booking_id` int(11) NOT NULL,
      `user_id` int(11) NOT NULL,
      `amount` decimal(10,2) NOT NULL,
      `payment_type` varchar(20) NOT NULL DEFAULT 'advance',
      `payment_method` varchar(50) NOT NULL,
      `transaction_id` varchar(100),
      `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
      `payment_status` varchar(20) DEFAULT 'completed',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
    
    if (mysqli_query($con, $payments_sql)) {
        echo "✅ Successfully created payments table\n";
    } else {
        echo "❌ Error creating payments table: " . mysqli_error($con) . "\n";
    }
} else {
    echo "✅ Payments table already exists\n";
}

// Update existing bookings with default values
echo "Updating existing bookings with default values...\n";
$update_queries = [
    "UPDATE booking SET booking_status = 'confirmed' WHERE booking_status IS NULL OR booking_status = ''",
    "UPDATE booking SET payment_status = 'partial' WHERE payment_status IS NULL OR payment_status = ''",
    "UPDATE booking SET advance_paid = 0.00 WHERE advance_paid IS NULL",
    "UPDATE booking SET remaining_amount = price WHERE remaining_amount IS NULL OR remaining_amount = 0",
    "UPDATE booking SET booking_date = NOW() WHERE booking_date IS NULL"
];

foreach ($update_queries as $query) {
    if (mysqli_query($con, $query)) {
        echo "✅ Updated existing records\n";
    } else {
        echo "❌ Update error: " . mysqli_error($con) . "\n";
    }
}

echo "\n🎉 Booking enhancements applied successfully!\n";
echo "You can now use the dashboard without errors.\n";
?>
