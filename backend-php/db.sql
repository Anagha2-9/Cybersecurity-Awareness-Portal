-- ============================================================================
-- Cybersecurity Awareness and Learning Portal
-- Practical 14: MySQL Database Schema Creation
-- ============================================================================
-- 
-- How to Import into XAMPP:
-- 1. Open XAMPP Control Panel and start Apache & MySQL.
-- 2. Open browser and visit: http://localhost/phpmyadmin
-- 3. Click the "Import" tab at the top.
-- 4. Choose this file (db.sql) and click "Import".
-- ============================================================================

-- Create the database if it doesn't already exist
CREATE DATABASE IF NOT EXISTS `cyber_portal_db` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `cyber_portal_db`;

-- Create Table: incident_reports
CREATE TABLE IF NOT EXISTS `incident_reports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `reporter_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `incident_type` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  `reported_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert starter dummy records for demonstration
INSERT INTO `incident_reports` (`reporter_name`, `email`, `incident_type`, `description`, `reported_at`) 
VALUES 
('Rohan Sharma', 'rohan@college.edu', 'Phishing', 'Received fake email with spoofed college portal link asking for student credentials.', NOW() - INTERVAL 1 DAY),
('Priya Patel', 'priya@college.edu', 'Malware', 'Downloaded an unverified PDF from an untrusted forum that triggered Windows Defender alerts.', NOW() - INTERVAL 5 HOUR);
