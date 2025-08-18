<?php
include('../Database/connect.php');
include_once('session.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if required parameters are present
if(!isset($_GET['id']) || !isset($_GET['action'])) {
    echo '<script type="text/javascript">
        alert("Invalid request parameters!");
        window.location="view_order.php";
    </script>';
    exit;
}

$booking_id = intval($_GET['id']);
$action = $_GET['action'];

// Validate action - now includes reset and force options
if(!in_array($action, ['approve', 'reject', 'reset', 'force_approve', 'force_reject'])) {
    echo '<script type="text/javascript">
        alert("Invalid action!");
        window.location="view_order.php";
    </script>';
    exit;
}

// First, check if booking_status column exists, if not add it
$check_column_query = "SHOW COLUMNS FROM booking LIKE 'booking_status'";
$column_result = mysqli_query($con, $check_column_query);

if(mysqli_num_rows($column_result) == 0) {
    // Add the booking_status column
    $add_column_query = "ALTER TABLE booking ADD COLUMN booking_status varchar(20) DEFAULT 'pending'";
    if(!mysqli_query($con, $add_column_query)) {
        echo '<script type="text/javascript">
            alert("Error adding booking_status column: ' . mysqli_error($con) . '");
            window.location="view_order.php";
        </script>';
        exit;
    }
}

// Check if booking exists
$check_query = "SELECT * FROM booking WHERE id = $booking_id";
$result = mysqli_query($con, $check_query);

if(mysqli_num_rows($result) == 0) {
    echo '<script type="text/javascript">
        alert("Booking not found!");
        window.location="view_order.php";
    </script>';
    exit;
}

$booking = mysqli_fetch_assoc($result);

// Check if booking_status column has value, if null set to pending
if(is_null($booking['booking_status']) || $booking['booking_status'] == '') {
    $update_pending = "UPDATE booking SET booking_status = 'pending' WHERE id = $booking_id";
    mysqli_query($con, $update_pending);
    $booking['booking_status'] = 'pending';
}

// Handle reset action
if($action == 'reset') {
    $update_query = "UPDATE booking SET booking_status = 'pending' WHERE id = $booking_id";
    if(mysqli_query($con, $update_query)) {
        echo '<script type="text/javascript">
            alert("Booking status has been reset to pending!");
            window.location="view_order.php";
        </script>';
    } else {
        echo '<script type="text/javascript">
            alert("Error resetting booking status: ' . mysqli_error($con) . '");
            window.location="view_order.php";
        </script>';
    }
    exit;
}

// Check if booking has already been processed (unless force action)
if($booking['booking_status'] !== 'pending' && !in_array($action, ['force_approve', 'force_reject'])) {
    echo '<script type="text/javascript">
        if(confirm("This booking has already been processed! Current status: ' . $booking['booking_status'] . '.\\n\\nDo you want to change the status anyway?")) {
            window.location="approve_booking.php?id=' . $booking_id . '&action=force_' . $action . '";
        } else {
            window.location="view_order.php";
        }
    </script>';
    exit;
}

// Determine new status
$new_status = '';
if(in_array($action, ['approve', 'force_approve'])) {
    $new_status = 'approved';
} else if(in_array($action, ['reject', 'force_reject'])) {
    $new_status = 'rejected';
}

// Update booking status
$update_query = "UPDATE booking SET booking_status = '$new_status' WHERE id = $booking_id";

if(mysqli_query($con, $update_query)) {
    $action_text = ($new_status == 'approved') ? 'approved' : 'rejected';
    $was_forced = in_array($action, ['force_approve', 'force_reject']);
    
    // Try to send email notification (but don't fail if it doesn't work)
    $email_sent = false;
    try {
        if(file_exists('email_notifications.php')) {
            include_once('email_notifications.php');
            $email_sent = sendBookingNotification($booking_id, $new_status, $con);
        }
    } catch(Exception $e) {
        // Email sending failed, but continue
        $email_sent = false;
    }
    
    $notification_message = "Booking has been successfully $action_text!";
    if($was_forced) {
        $notification_message = "Booking status has been changed to $action_text!";
    }
    if(!$email_sent) {
        $notification_message .= " (Note: Email notification could not be sent)";
    }
    
    echo '<script type="text/javascript">
        alert("' . $notification_message . '");
        window.location="view_order.php";
    </script>';
} else {
    echo '<script type="text/javascript">
        alert("Error updating booking status: ' . mysqli_error($con) . '");
        window.location="view_order.php";
    </script>';
}
?>
