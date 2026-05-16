-- ==========================================
-- WE CLEAN IT - DATABASE EXPORT (FIXED VERSION)
-- ==========================================

CREATE DATABASE IF NOT EXISTS `wecleanit_db`;
USE `wecleanit_db`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- 1. Tabel Users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL UNIQUE,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `profile_picture` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`name`, `phone`, `password_hash`, `role`) VALUES 
('Super Admin', 'admin@wecleanit.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso', '08123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer'),
('Siti Aminah', '08987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');

-- 2. Tabel Addresses
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `address_name` varchar(50) NOT NULL,
  `address_detail` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel Packages
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `packages` (`name`, `price`, `description`) VALUES
('Bersih Standar', 50000, 'Sapu, pel lantai, rapihkan kasur & meja, buang sampah.'),
('Bersih Menyeluruh', 85000, 'Termasuk Standar + sikat kamar mandi & lap kaca/lemari.'),
('Bersih Ekstra', 150000, 'Termasuk Menyeluruh + bersihkan kerak & disinfektan.');

-- 4. Tabel Cleaners
DROP TABLE IF EXISTS `cleaners`;
CREATE TABLE `cleaners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `status` enum('Tersedia','Bertugas','Off') DEFAULT 'Tersedia',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `cleaners` (`name`, `phone`, `status`) VALUES
('Mas Anto', '08111222333', 'Tersedia'),
('Mbak Dewi', '08444555666', 'Tersedia');

-- 5. Tabel Orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `address_detail` text NOT NULL,
  `order_date` date NOT NULL,
  `order_time` varchar(50) NOT NULL,
  `cleaner_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('Belum Dibayar','Sudah Dibayar') DEFAULT 'Belum Dibayar',
  `status` enum('Menunggu','Dikonfirmasi','Sedang Dikerjakan','Selesai','Dibatalkan') DEFAULT 'Menunggu',
  `cancel_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `cleaner_id` (`cleaner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` (`id`, `user_id`, `package_name`, `price`, `address_detail`, `order_date`, `order_time`, `payment_method`, `payment_status`, `status`) VALUES
('ORD-0001', 2, 'Bersih Standar', 50000, 'Jl. Majapahit No.10 Kamar 04', '2026-04-25', '10:00 - 12:00', 'QRIS', 'Sudah Dibayar', 'Selesai'),
('ORD-0002', 2, 'Bersih Menyeluruh', 85000, 'Jl. Majapahit No.10 Kamar 04', '2026-04-28', '13:00 - 15:00', 'GoPay', 'Sudah Dibayar', 'Dibatalkan'),
('ORD-0003', 3, 'Bersih Ekstra', 150000, 'Jl. Sriwijaya No.5 Kamar 12', '2026-04-30', '08:00 - 10:00', 'Transfer Mandiri', 'Sudah Dibayar', 'Menunggu');

-- 6. Tabel Reviews
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;