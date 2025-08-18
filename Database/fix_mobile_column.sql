-- Fix for mobile number column to handle 10-digit numbers
-- Run this SQL query in your database to fix the mobile number storage issue

USE eventease_db;

-- Change the mobile number column from int(11) to varchar(15) to handle large mobile numbers
ALTER TABLE `registration` MODIFY COLUMN `mo` varchar(15) NOT NULL;

-- Alternative: If you prefer to keep it as a number, use BIGINT
-- ALTER TABLE `registration` MODIFY COLUMN `mo` BIGINT NOT NULL;
