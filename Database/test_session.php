<?php
require_once 'connect.php';

echo "=== TESTING SESSION AND USER MATCHING ===\n\n";

// Simulate a session for testing
session_start();

// Test with the existing user
$test_email = 'pufivaby@mailinator.com'; // This should be user_id 4
$test_username = 'wijev';

echo "Testing with:\n";
echo "Username: {$test_username}\n";
echo "Email: {$test_email}\n\n";

// Check if user exists in registration
$user_query = "SELECT * FROM registration WHERE unm='$test_username'";
$user_result = mysqli_query($con, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

if($user_data) {
    echo "✅ User found in registration:\n";
    echo "   ID: {$user_data['id']}\n";
    echo "   Username: {$user_data['unm']}\n";
    echo "   Email: {$user_data['email']}\n";
    echo "   Name: {$user_data['nm']} {$user_data['surnm']}\n\n";
    
    // Test booking queries
    echo "Testing booking queries:\n\n";
    
    // Query by email
    $email_query = "SELECT * FROM booking WHERE email = '{$user_data['email']}'";
    $email_result = mysqli_query($con, $email_query);
    echo "1. Query by email: {$email_query}\n";
    echo "   Results: " . mysqli_num_rows($email_result) . " bookings\n\n";
    
    // Query by user_id
    $userid_query = "SELECT * FROM booking WHERE user_id = '{$user_data['id']}'";
    $userid_result = mysqli_query($con, $userid_query);
    echo "2. Query by user_id: {$userid_query}\n";
    echo "   Results: " . mysqli_num_rows($userid_result) . " bookings\n\n";
    
    // Check if there's a mismatch
    if(mysqli_num_rows($email_result) == 0 && mysqli_num_rows($userid_result) == 0) {
        echo "❌ No bookings found for this user!\n";
        echo "Let's check all bookings:\n";
        $all_query = "SELECT id, nm, email, user_id FROM booking";
        $all_result = mysqli_query($con, $all_query);
        while($booking = mysqli_fetch_assoc($all_result)) {
            echo "   Booking ID: {$booking['id']}, Email: {$booking['email']}, UserID: {$booking['user_id']}\n";
        }
    }
} else {
    echo "❌ User not found in registration table!\n";
}

echo "\n=== END TEST ===\n";
?>
