<?php
// Test file to check booking approval functionality
include('../Database/connect.php');
include_once('session.php');

echo "<h2>Booking Approval System Test</h2>\n";

// Test database connection
if ($con) {
    echo "<p style='color: green;'>✓ Database connection successful</p>\n";
} else {
    echo "<p style='color: red;'>✗ Database connection failed</p>\n";
    exit;
}

// Check if booking table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE 'booking'");
if (mysqli_num_rows($table_check) > 0) {
    echo "<p style='color: green;'>✓ Booking table exists</p>\n";
} else {
    echo "<p style='color: red;'>✗ Booking table not found</p>\n";
    exit;
}

// Check if booking_status column exists
$column_check = mysqli_query($con, "SHOW COLUMNS FROM booking LIKE 'booking_status'");
if (mysqli_num_rows($column_check) > 0) {
    echo "<p style='color: green;'>✓ booking_status column exists</p>\n";
} else {
    echo "<p style='color: orange;'>⚠ booking_status column missing - will be added automatically</p>\n";
}

// Get sample bookings
echo "<h3>Current Bookings:</h3>\n";
$bookings_query = "SELECT id, nm, email, COALESCE(booking_status, 'pending') as booking_status, date FROM booking LIMIT 10";
$bookings_result = mysqli_query($con, $bookings_query);

if (mysqli_num_rows($bookings_result) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Event Date</th><th>Test Actions</th></tr>\n";
    
    while ($booking = mysqli_fetch_assoc($bookings_result)) {
        $status_color = '';
        switch($booking['booking_status']) {
            case 'pending': $status_color = 'orange'; break;
            case 'approved': $status_color = 'green'; break;
            case 'rejected': $status_color = 'red'; break;
            default: $status_color = 'gray';
        }
        
        echo "<tr>";
        echo "<td>" . $booking['id'] . "</td>";
        echo "<td>" . htmlspecialchars($booking['nm']) . "</td>";
        echo "<td>" . htmlspecialchars($booking['email']) . "</td>";
        echo "<td style='color: $status_color; font-weight: bold;'>" . ucfirst($booking['booking_status']) . "</td>";
        echo "<td>" . $booking['date'] . "</td>";
        echo "<td>";
        
        if ($booking['booking_status'] == 'pending') {
            echo "<a href='approve_booking.php?id=" . $booking['id'] . "&action=approve' style='color: green;'>Approve</a> | ";
            echo "<a href='approve_booking.php?id=" . $booking['id'] . "&action=reject' style='color: red;'>Reject</a>";
        } else {
            echo "<span style='color: gray;'>Already processed</span>";
        }
        
        echo "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
} else {
    echo "<p>No bookings found in the database.</p>\n";
}

echo "<br><p><a href='view_order.php'>Go to Booking Requests Page</a></p>\n";
echo "<p><a href='setup_database.php'>Run Database Setup</a></p>\n";
?>
