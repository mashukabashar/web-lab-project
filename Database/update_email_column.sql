-- SQL script to fix email column length in booking table
-- Run this script to update the database schema

USE eventease_db;

-- Increase email column length from 20 to 100 characters in booking table
ALTER TABLE `booking` MODIFY COLUMN `email` varchar(100) NOT NULL;

-- Also update registration table email column from 30 to 100 characters
ALTER TABLE `registration` MODIFY COLUMN `email` varchar(100) NOT NULL;

-- Feedback table email column is already 30, but let's make it consistent
ALTER TABLE `feedback` MODIFY COLUMN `email` varchar(100) NOT NULL;

-- Verify the changes
DESCRIBE booking;
DESCRIBE registration;
DESCRIBE feedback;
