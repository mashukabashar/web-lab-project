-- IMPORTANT: Run this SQL script in your MySQL database to fix the email column length error
-- You can run this through phpMyAdmin, MySQL Workbench, or command line

USE eventease_db;

-- Fix the booking table email column (increase from 20 to 100 characters)
ALTER TABLE `booking` MODIFY COLUMN `email` varchar(100) NOT NULL;

-- Also fix registration table email column (increase from 30 to 100 characters)  
ALTER TABLE `registration` MODIFY COLUMN `email` varchar(100) NOT NULL;

-- Fix feedback table email column (increase from 30 to 100 characters)
ALTER TABLE `feedback` MODIFY COLUMN `email` varchar(100) NOT NULL;

-- Fix admin login: Ensure admin table exists and has correct data
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nm` varchar(10) NOT NULL,
  `pswd` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Insert or update admin credentials
INSERT INTO `admin` (`id`, `nm`, `pswd`) VALUES (1, 'Drashti', 'sabhaya') 
ON DUPLICATE KEY UPDATE `nm` = 'Drashti', `pswd` = 'sabhaya';

-- Alternative admin with easier credentials
INSERT INTO `admin` (`nm`, `pswd`) VALUES ('admin', 'admin123') 
ON DUPLICATE KEY UPDATE `pswd` = 'admin123';

-- Verify the changes
DESCRIBE booking;
DESCRIBE registration; 
DESCRIBE feedback;
DESCRIBE admin;
SELECT * FROM admin;
