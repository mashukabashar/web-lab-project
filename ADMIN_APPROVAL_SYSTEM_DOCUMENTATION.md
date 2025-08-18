# Admin Approval System Documentation

## Overview
The EventEase system now includes a comprehensive admin approval workflow for event booking requests. All new bookings start with a "pending" status and require admin approval before users can proceed with payments.

## Features Implemented

### 1. Booking Status Management
- **Pending**: Default status for new bookings
- **Approved**: Admin-approved bookings (users can make payments)
- **Rejected**: Admin-rejected bookings (no payment allowed)

### 2. Admin Dashboard Enhancements

#### Dashboard Statistics (`Admin/index.php`)
- Real-time count of pending, approved, and rejected bookings
- Visual dashboard with color-coded status panels
- Alert notifications for pending requests requiring attention
- Quick navigation to booking management

#### Booking Requests Management (`Admin/view_order.php`)
- Enhanced table view with booking status column
- Color-coded status badges (Yellow=Pending, Green=Approved, Red=Rejected)
- Action buttons for pending bookings (Approve/Reject)
- Improved responsive design for mobile devices
- Better image sizing and formatting
- Enhanced date formatting and currency display

#### Navigation Updates (`Admin/header.php`)
- Updated menu item from "VIEW ORDER" to "BOOKING REQUESTS"
- Added notification badge showing pending request count
- Real-time notification updates

### 3. Approval Workflow (`Admin/approve_booking.php`)
- Secure approval/rejection handling
- Input validation and security checks
- Status change logging
- Email notification integration
- Error handling with user feedback

### 4. User Experience Enhancements

#### Booking History (`booking_history.php`)
- Updated status display to show approval status
- Conditional payment buttons (only show for approved bookings)
- Status-specific messaging:
  - Pending: "Waiting for admin approval"
  - Rejected: "Booking rejected - contact us"
  - Approved: Payment button available
- Enhanced visual feedback with color-coded status badges

### 5. Email Notification System (`Admin/email_notifications.php`)
- Automated email notifications for status changes
- Professional HTML email templates
- Separate templates for approval and rejection
- Detailed booking information in emails
- Next steps guidance for users

## Workflow Process

### User Side:
1. User submits an event booking request
2. Booking is created with "pending" status
3. User receives confirmation and waits for approval
4. User receives email notification when status changes
5. If approved: User can make payment
6. If rejected: User is notified with next steps

### Admin Side:
1. Admin sees pending requests on dashboard
2. Admin reviews booking details
3. Admin clicks "Approve" or "Reject"
4. System updates status and sends notification
5. Admin can track all bookings with status overview

## Database Schema
The existing `booking` table utilizes the `booking_status` column with values:
- `pending` (default for new bookings)
- `approved` (admin approved)
- `rejected` (admin rejected)

## File Changes Made

### New Files:
- `Admin/approve_booking.php` - Handles approval/rejection logic
- `Admin/email_notifications.php` - Email notification functions

### Modified Files:
- `Admin/view_order.php` - Enhanced booking management interface
- `Admin/index.php` - Added dashboard statistics
- `Admin/header.php` - Updated navigation and notifications
- `booking_history.php` - Improved user status display

## Security Features
- Session validation for admin actions
- Input sanitization and validation
- SQL injection prevention
- CSRF protection through confirmation dialogs
- Status verification before processing

## Usage Instructions

### For Admins:
1. Log into admin panel
2. Check dashboard for pending requests overview
3. Navigate to "BOOKING REQUESTS" to see all bookings
4. Use "Approve" or "Reject" buttons for pending bookings
5. Monitor status changes and user communications

### For Users:
1. Submit booking request through website
2. Check booking history to monitor status
3. Wait for admin approval notification
4. Make payment once booking is approved
5. Contact support if booking is rejected

## Technical Notes
- Email functionality requires proper mail server configuration
- Responsive design ensures mobile compatibility
- Real-time status updates on page refresh
- Color-coded visual feedback throughout interface
- Maintains existing database structure

## Future Enhancements
- Real-time notifications without page refresh
- Batch approval functionality
- Advanced filtering and search options
- Admin comments/notes on rejections
- SMS notifications integration
- Detailed audit trail logging
