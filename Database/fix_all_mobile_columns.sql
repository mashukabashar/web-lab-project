-- Comprehensive fix for all data storage limitations
-- Run this SQL query in your database to fix all data storage issues

USE eventease_db;

-- Fix the registration table columns
ALTER TABLE `registration` MODIFY COLUMN `mo` varchar(15) NOT NULL;
ALTER TABLE `registration` MODIFY COLUMN `email` varchar(100) NOT NULL;
ALTER TABLE `registration` MODIFY COLUMN `nm` varchar(50) NOT NULL;
ALTER TABLE `registration` MODIFY COLUMN `surnm` varchar(50) NOT NULL;
ALTER TABLE `registration` MODIFY COLUMN `unm` varchar(50) NOT NULL;

-- Fix the booking table columns
ALTER TABLE `booking` MODIFY COLUMN `mo` varchar(15) NOT NULL;
ALTER TABLE `booking` MODIFY COLUMN `email` varchar(100) NOT NULL;
ALTER TABLE `booking` MODIFY COLUMN `nm` varchar(50) NOT NULL;
ALTER TABLE `booking` MODIFY COLUMN `thm_nm` varchar(50) NOT NULL;

-- Optional: Update existing truncated values (you may want to do this manually)
-- UPDATE `booking` SET `mo` = 'NEEDS_UPDATE' WHERE `mo` = 2147483647;
