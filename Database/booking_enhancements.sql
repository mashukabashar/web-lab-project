-- Enhanced database structure for booking history, payments, and invoices
-- Run this SQL query to add new functionality

USE eventease_db;

-- Add new columns to booking table for payment tracking
ALTER TABLE `booking` ADD COLUMN `booking_status` varchar(20) DEFAULT 'pending';
ALTER TABLE `booking` ADD COLUMN `payment_status` varchar(20) DEFAULT 'unpaid';
ALTER TABLE `booking` ADD COLUMN `advance_paid` decimal(10,2) DEFAULT 0.00;
ALTER TABLE `booking` ADD COLUMN `remaining_amount` decimal(10,2) DEFAULT 0.00;
ALTER TABLE `booking` ADD COLUMN `booking_date` datetime DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `booking` ADD COLUMN `user_id` int(11);

-- Create payments table for tracking payment history
CREATE TABLE `payments` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Create user_bookings view for easy booking history access
CREATE VIEW `user_booking_history` AS
SELECT 
    b.id as booking_id,
    b.nm as customer_name,
    b.email,
    b.mo as mobile,
    b.thm_nm as theme_name,
    b.theme as theme_image,
    b.price as total_price,
    b.advance_paid,
    b.remaining_amount,
    b.date as event_date,
    b.booking_date,
    b.booking_status,
    b.payment_status,
    r.id as user_id,
    r.unm as username
FROM booking b
LEFT JOIN registration r ON r.email = b.email
ORDER BY b.booking_date DESC;
