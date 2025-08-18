<?php
// Email notification functions for booking status changes

function sendBookingNotification($booking_id, $status, $con) {
    // Get booking details
    $query = "SELECT * FROM booking WHERE id = $booking_id";
    $result = mysqli_query($con, $query);
    
    if(mysqli_num_rows($result) == 0) {
        return false;
    }
    
    $booking = mysqli_fetch_assoc($result);
    
    $to = $booking['email'];
    $subject = "EventEase - Booking Status Update";
    
    if($status == 'approved') {
        $message = "
        <html>
        <head>
            <title>Booking Approved - EventEase</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
                <h2 style='color: #28a745; text-align: center;'>🎉 Booking Approved!</h2>
                
                <p>Dear " . $booking['nm'] . ",</p>
                
                <p>Great news! Your event booking has been <strong style='color: #28a745;'>APPROVED</strong> by our admin team.</p>
                
                <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <h3 style='margin-top: 0;'>Booking Details:</h3>
                    <p><strong>Booking ID:</strong> #" . $booking['id'] . "</p>
                    <p><strong>Event Theme:</strong> " . $booking['thm_nm'] . "</p>
                    <p><strong>Event Date:</strong> " . $booking['date'] . "</p>
                    <p><strong>Total Price:</strong> ৳" . number_format($booking['price']) . "</p>
                    <p><strong>Advance Payment:</strong> ৳" . number_format($booking['advance_paid']) . "</p>
                    <p><strong>Remaining Amount:</strong> ৳" . number_format($booking['remaining_amount']) . "</p>
                </div>
                
                <p><strong>Next Steps:</strong></p>
                <ul>
                    <li>You can now proceed to make the advance payment if not already done</li>
                    <li>Log in to your account to view booking details and download invoice</li>
                    <li>Our team will contact you soon to finalize the arrangements</li>
                </ul>
                
                <p>If you have any questions, please don't hesitate to contact us.</p>
                
                <p>Thank you for choosing EventEase!</p>
                
                <hr style='margin: 30px 0;'>
                <p style='text-align: center; color: #666; font-size: 12px;'>
                    EventEase - Making Your Events Memorable<br>
                    This is an automated message, please do not reply directly to this email.
                </p>
            </div>
        </body>
        </html>
        ";
    } else if($status == 'rejected') {
        $message = "
        <html>
        <head>
            <title>Booking Update - EventEase</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
                <h2 style='color: #dc3545; text-align: center;'>Booking Status Update</h2>
                
                <p>Dear " . $booking['nm'] . ",</p>
                
                <p>We regret to inform you that your event booking has been <strong style='color: #dc3545;'>declined</strong> at this time.</p>
                
                <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <h3 style='margin-top: 0;'>Booking Details:</h3>
                    <p><strong>Booking ID:</strong> #" . $booking['id'] . "</p>
                    <p><strong>Event Theme:</strong> " . $booking['thm_nm'] . "</p>
                    <p><strong>Event Date:</strong> " . $booking['date'] . "</p>
                    <p><strong>Total Price:</strong> ৳" . number_format($booking['price']) . "</p>
                </div>
                
                <p><strong>Possible reasons for decline:</strong></p>
                <ul>
                    <li>Date not available</li>
                    <li>Venue conflicts</li>
                    <li>Resource constraints</li>
                    <li>Other scheduling issues</li>
                </ul>
                
                <p>We sincerely apologize for any inconvenience. Please feel free to:</p>
                <ul>
                    <li>Contact us to discuss alternative dates</li>
                    <li>Browse other available themes and packages</li>
                    <li>Submit a new booking request with different details</li>
                </ul>
                
                <p>Thank you for your understanding and for considering EventEase.</p>
                
                <hr style='margin: 30px 0;'>
                <p style='text-align: center; color: #666; font-size: 12px;'>
                    EventEase - Making Your Events Memorable<br>
                    This is an automated message, please do not reply directly to this email.
                </p>
            </div>
        </body>
        </html>
        ";
    }
    
    // Headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: EventEase <noreply@eventease.com>' . "\r\n";
    
    // Send email (Note: This will only work if mail server is properly configured)
    return mail($to, $subject, $message, $headers);
}
?>
