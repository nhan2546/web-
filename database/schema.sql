-- SQL schema for the simple store app
-- Create database and users table required by process_resister.php

CREATE DATABASE IF NOT EXISTS `store_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `store_db`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: a simple admin user (password: admin123) - hashed value generated with PHP password_hash
-- Note: Replace the hashed password before using in production.
INSERT INTO `users` (`fullname`, `email`, `password`, `role`) VALUES
('Administrator', 'admin@example.com', '$2y$10$e0NRd1h0QjR5mQZr5pQ/WeQbLr6sYF0VZk1e6yqK7T0C1fQ0Gx8xG', 'admin')
ON DUPLICATE KEY UPDATE email = email;
