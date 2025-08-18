-- Demo data for testing the new booking system features
-- Run this to create sample data for testing

USE eventease_db;

-- First, make sure user_id can be NULL temporarily
ALTER TABLE booking MODIFY COLUMN user_id int(11) NULL;
ALTER TABLE payments MODIFY COLUMN user_id int(11) NULL;

-- Update existing bookings to include the new fields (if any exist)
UPDATE booking SET 
    booking_status = 'confirmed',
    payment_status = 'partial',
    advance_paid = 50000,
    remaining_amount = price - 50000,
    booking_date = NOW()
WHERE advance_paid IS NULL OR advance_paid = 0;

-- Update user_id where possible
UPDATE booking b 
SET user_id = (SELECT id FROM registration r WHERE r.email = b.email LIMIT 1)
WHERE user_id IS NULL;

-- Insert a sample payment record for existing bookings
INSERT INTO payments (booking_id, user_id, amount, payment_type, payment_method, transaction_id, payment_status)
SELECT 
    b.id,
    b.user_id,
    b.advance_paid,
    'advance',
    'credit_card',
    CONCAT('TXN', UNIX_TIMESTAMP(NOW()), FLOOR(RAND()*1000)),
    'completed'
FROM booking b 
WHERE b.advance_paid > 0 
AND NOT EXISTS (SELECT 1 FROM payments p WHERE p.booking_id = b.id)
LIMIT 5;
