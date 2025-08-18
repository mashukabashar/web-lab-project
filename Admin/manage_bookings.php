<?php
include('../Database/connect.php');
include_once('session.php');

echo "<h2>Booking Status Management</h2>\n";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['reset_all'])) {
        $reset_query = "UPDATE booking SET booking_status = 'pending'";
        if (mysqli_query($con, $reset_query)) {
            $affected_rows = mysqli_affected_rows($con);
            echo "<div style='color: green; font-weight: bold; margin: 20px 0;'>
                ✓ Successfully reset $affected_rows bookings to PENDING status!
            </div>";
        } else {
            echo "<div style='color: red; font-weight: bold; margin: 20px 0;'>
                ✗ Error resetting bookings: " . mysqli_error($con) . "
            </div>";
        }
    }
    
    if (isset($_POST['reset_approved'])) {
        $reset_query = "UPDATE booking SET booking_status = 'pending' WHERE booking_status = 'approved'";
        if (mysqli_query($con, $reset_query)) {
            $affected_rows = mysqli_affected_rows($con);
            echo "<div style='color: green; font-weight: bold; margin: 20px 0;'>
                ✓ Successfully reset $affected_rows APPROVED bookings to PENDING status!
            </div>";
        } else {
            echo "<div style='color: red; font-weight: bold; margin: 20px 0;'>
                ✗ Error resetting approved bookings: " . mysqli_error($con) . "
            </div>";
        }
    }
}

// Show current booking statistics
$stats_query = "SELECT 
    booking_status,
    COUNT(*) as count 
FROM booking 
GROUP BY booking_status";
$stats_result = mysqli_query($con, $stats_query);

echo "<h3>Current Booking Status Summary:</h3>";
echo "<table border='1' style='border-collapse: collapse; margin: 20px 0;'>";
echo "<tr><th>Status</th><th>Count</th></tr>";

$total_bookings = 0;
while ($stat = mysqli_fetch_assoc($stats_result)) {
    $status = $stat['booking_status'] ?: 'NULL/Empty';
    $count = $stat['count'];
    $total_bookings += $count;
    
    $color = '';
    switch($status) {
        case 'pending': $color = 'orange'; break;
        case 'approved': $color = 'green'; break;
        case 'rejected': $color = 'red'; break;
        default: $color = 'gray';
    }
    
    echo "<tr>";
    echo "<td style='color: $color; font-weight: bold;'>" . ucfirst($status) . "</td>";
    echo "<td style='text-align: center;'>$count</td>";
    echo "</tr>";
}
echo "<tr style='background-color: #f0f0f0; font-weight: bold;'>";
echo "<td>TOTAL</td>";
echo "<td style='text-align: center;'>$total_bookings</td>";
echo "</tr>";
echo "</table>";

// Quick actions form
echo "<h3>Quick Actions:</h3>";
echo "<form method='POST' style='margin: 20px 0;'>";
echo "<button type='submit' name='reset_all' onclick='return confirm(\"Reset ALL bookings to PENDING status? This cannot be undone!\")' 
         style='background-color: #ff6b35; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;'>
         Reset ALL to Pending
      </button>";
echo "<button type='submit' name='reset_approved' onclick='return confirm(\"Reset only APPROVED bookings to PENDING status?\")' 
         style='background-color: #ffa500; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin: 5px;'>
         Reset Approved to Pending
      </button>";
echo "</form>";

// Show recent bookings
echo "<h3>Recent Bookings (Last 10):</h3>";
$recent_query = "SELECT id, nm, email, booking_status, date, 
                 CASE 
                    WHEN booking_date IS NOT NULL THEN booking_date 
                    ELSE 'N/A' 
                 END as booking_date
                 FROM booking 
                 ORDER BY id DESC 
                 LIMIT 10";
$recent_result = mysqli_query($con, $recent_query);

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Event Date</th><th>Booking Date</th><th>Actions</th></tr>";

while ($booking = mysqli_fetch_assoc($recent_result)) {
    $status = $booking['booking_status'] ?: 'pending';
    $status_color = '';
    switch($status) {
        case 'pending': $status_color = 'orange'; break;
        case 'approved': $status_color = 'green'; break;
        case 'rejected': $status_color = 'red'; break;
        default: $status_color = 'gray';
    }
    
    echo "<tr>";
    echo "<td>" . $booking['id'] . "</td>";
    echo "<td>" . htmlspecialchars($booking['nm']) . "</td>";
    echo "<td>" . htmlspecialchars($booking['email']) . "</td>";
    echo "<td style='color: $status_color; font-weight: bold;'>" . ucfirst($status) . "</td>";
    echo "<td>" . $booking['date'] . "</td>";
    echo "<td>" . $booking['booking_date'] . "</td>";
    echo "<td>";
    
    if ($status == 'pending') {
        echo "<a href='approve_booking.php?id=" . $booking['id'] . "&action=approve' style='color: green;'>Approve</a> | ";
        echo "<a href='approve_booking.php?id=" . $booking['id'] . "&action=reject' style='color: red;'>Reject</a>";
    } else {
        echo "<a href='approve_booking.php?id=" . $booking['id'] . "&action=reset' style='color: orange;'>Reset</a>";
    }
    
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<div style='margin: 30px 0;'>";
echo "<a href='view_order.php' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Booking Requests</a> ";
echo "<a href='test_approval.php' style='background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Test Approval System</a>";
echo "</div>";
?>
