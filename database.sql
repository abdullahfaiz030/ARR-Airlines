-- ARR-Airlines Database Schema
-- Use this file to import the database structure manually via phpMyAdmin or the MySQL CLI.
-- For a fully seeded database (with 100+ global airports and thousands of real-time randomized flights),
-- it is highly recommended to run 'setup_db.php' in your browser instead.

CREATE DATABASE IF NOT EXISTS `project`;
USE `project`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `airports`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `airports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(10) NOT NULL UNIQUE,
  `name` VARCHAR(255) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `country` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `flights`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `flights` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `flight_number` VARCHAR(20) NOT NULL UNIQUE,
  `origin_id` INT NOT NULL,
  `dest_id` INT NOT NULL,
  `departure_time` DATETIME NOT NULL,
  `arrival_time` DATETIME NOT NULL,
  `base_price` DECIMAL(10, 2) NOT NULL,
  `total_seats` INT NOT NULL DEFAULT 150,
  FOREIGN KEY (`origin_id`) REFERENCES `airports`(`id`),
  FOREIGN KEY (`dest_id`) REFERENCES `airports`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `bookings`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `flight_id` INT NOT NULL,
  `booking_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `total_price` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
  `pnr` VARCHAR(20) UNIQUE NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`flight_id`) REFERENCES `flights`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `passengers`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `passengers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `dob` DATE NOT NULL,
  `passport` VARCHAR(100) NOT NULL,
  `seat_number` VARCHAR(10) NOT NULL,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `payments`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `status` ENUM('Success', 'Failed') DEFAULT 'Success',
  `transaction_id` VARCHAR(100) UNIQUE NOT NULL,
  `payment_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Seed default admin account
-- Email: admin@arrairlines.com
-- Password: admin123
-- --------------------------------------------------------

INSERT IGNORE INTO `users` (`name`, `email`, `password`, `role`) VALUES
('System Admin', 'admin@arrairlines.com', '$2y$10$wE392JtMhA8n0B5FhY8FveiJSwK39G.0X16W4H4ZfUqgC5YnFh/D.', 'admin');
