-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2026 at 05:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`, `reset_token`, `token_expiry`, `created_at`) VALUES
(1, 'admin', 'shrivastavauttam699@gmail.com', '$2y$10$7WZ6kCD.G5oE6PPZ5Kz3qODT27grJh/HB.O9FcGurx8U6AVb/5xVm', NULL, NULL, '2026-02-22 18:21:36');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `rating` varchar(20) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `rating`, `message`, `created_at`, `order_id`) VALUES
(6, 'jagriti', 'jagritis280@gmail.com', 'Excellent', 'delicious', '2026-03-01 14:50:11', NULL),
(7, 'Uttam', 'shrivastavauttam699@gmail.com', 'Excellent', 'good', '2026-03-01 16:38:33', 29),
(8, 'Uttam', 'shrivastavauttam699@gmail.com', 'Excellent', 'good', '2026-03-01 16:50:39', 29),
(9, 'Uttam', 'shrivastavauttam699@gmail.com', 'Good', 'good services', '2026-03-01 16:53:33', 29);

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `category`, `price`, `image`, `stock`) VALUES
(1, 'Paneer Tikka', 'Starters', 220.00, NULL, 0),
(2, 'Veg Spring Roll', 'Starters', 160.00, NULL, 20),
(3, 'Cheese Balls', 'Starters', 180.00, NULL, 12),
(5, 'Veg Biryani', 'Main Course', 210.00, NULL, 18),
(6, 'Shahi Thali', 'Main Course', 320.00, NULL, 8),
(7, 'Dal Tadka', 'Main Course', 190.00, NULL, 14),
(8, 'Mix Veg', 'Main Course', 200.00, NULL, 16),
(9, 'Butter Naan', 'Breads', 40.00, NULL, 30),
(10, 'Tandoori Roti', 'Breads', 25.00, NULL, 40),
(11, 'Garlic Naan', 'Breads', 50.00, NULL, 25),
(12, 'Cold Coffee', 'Beverages', 120.00, NULL, 2),
(13, 'Masala Chai', 'Beverages', 30.00, NULL, 50),
(14, 'Fresh Lime Soda', 'Beverages', 60.00, NULL, 35),
(15, 'Gulab Jamun', 'Desserts', 90.00, NULL, 25),
(16, 'Ice Cream', 'Desserts', 110.00, NULL, 18),
(17, 'Brownie', 'Desserts', 140.00, NULL, 15),
(18, 'Paneer roll', 'Main Course', 123.00, NULL, 45),
(19, 'italian pasta', 'Main Course', 430.00, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `offer_text` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `offer_text`, `start_date`, `end_date`, `status`) VALUES
(13, '10%off Coffe', '2026-02-22', '2026-02-23', 'Inactive'),
(14, '20%off on coffee', '2026-02-22', '2026-02-23', 'Active'),
(15, 'rang birangi holi dhamaka 30%off', '2026-03-04', '2026-03-06', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `items` text DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `payment_method` varchar(20) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `email`, `phone`, `address`, `items`, `total_amount`, `payment_method`, `order_date`, `status`) VALUES
(13, 'us', 'shrivastavauttam699@gmail.com', '7525962700', 'kyn', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 10:01:51', 'Pending'),
(14, 'Jagriti', 'jagritis280@gmail.com', '9285402651', 'kalyan e', 'Dessert (Qty: 1), Momos (Qty: 2), ', 220, 'Cash', '2026-02-15 11:43:21', 'Delivered'),
(15, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:27', 'Pending'),
(16, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:29', 'Pending'),
(17, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:33', 'Pending'),
(18, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:34', 'Pending'),
(19, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:34', 'Pending'),
(20, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:34', 'Pending'),
(21, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:34', 'Pending'),
(22, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:35', 'Pending'),
(23, 'Uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan e', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:11:35', 'Pending'),
(24, 'kh', 'shrivastavauttam699@gmail.com', 'sf', 'dgjh', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:12:07', 'Pending'),
(25, 'kh', 'shrivastavauttam699@gmail.com', 'sf', 'dgjh', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:12:08', 'Pending'),
(26, 'kh', 'shrivastavauttam699@gmail.com', 'sf', 'dgjh', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:12:08', 'Pending'),
(27, 'uttam', 'shrivastavauttam699@gmail.com', '7525962700', 'kalyan', 'Bread Omlet (Qty: 1), ', 40, 'Cash', '2026-02-15 15:12:58', 'Pending'),
(28, 'Utam', 'shrivastavauttam699@gmail.com', '7525962700', 'kyn', 'Cold Coffee (Qty: 1), Kadhai Paneer (Qty: 1), ', 380, 'Cash', '2026-02-22 14:39:14', 'Pending'),
(29, 'Utam', 'shrivastavauttam699@gmail.com', '7525962700', 'kyn', 'Cold Coffee (Qty: 1), ', 0, 'Cash', '2026-02-22 14:41:12', 'Pending'),
(30, 'name', 'shrivastavauttam699@gmail.com', '7525962700', 'kyn', 'Shahi Thali (Qty: 1), ', 320, 'Cash', '2026-03-01 13:17:58', 'Pending'),
(31, 'jagriti', 'jagritis280@gmail.com', '9285402651', 'katemane', 'Masala Chai (Qty: 1), Shahi Thali (Qty: 1), ', 350, 'Cash', '2026-03-01 14:38:56', 'Delivered');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
