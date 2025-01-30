-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 04:52 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_foodhub-secur`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(26) NOT NULL,
  `item_price` float NOT NULL,
  `item_rating` float DEFAULT 0,
  `restaurant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_price`, `item_rating`, `restaurant_id`) VALUES
(1, 'Burger', 180, 5, 3),
(2, 'Pizza', 500, 4.5, 3),
(3, 'Pasta', 250, 0, 2),
(4, 'Nachos', 250, 0, 1),
(5, 'Halim', 150, 2, 1),
(6, 'BBQ Chicken', 200, 0, 1),
(7, 'Subway Sandwiches', 250, 0, 6),
(8, 'Subway Sandwiches', 280, 0, 10),
(9, 'Mexican Nachos', 450, 0, 5),
(10, 'Chicken pizza', 450, 0, 4),
(11, 'Mexican Nachos', 350, 0, 2),
(12, 'BBQ Rice Bowl', 550, 3.5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_type` varchar(15) NOT NULL,
  `order_placed` datetime DEFAULT current_timestamp(),
  `order_status` varchar(10) DEFAULT 'pending',
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_type`, `order_placed`, `order_status`, `item_id`, `quantity`) VALUES
(1, 2, 'onspot', '2024-11-17 17:20:00', 'pending', 1, 1),
(2, 2, 'homedelivery', '2024-11-17 17:30:00', 'success', 2, 1),
(3, 2, 'homedelivery', '2024-11-17 17:35:08', 'pending', 1, 1),
(4, 2, 'onspot', '2024-11-17 17:25:08', 'success', 2, 1),
(5, 2, 'homedelivery', '2024-11-18 00:07:11', 'pending', 3, 1),
(6, 2, 'onspot', '2024-11-18 00:10:10', 'success', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `promotional`
--

CREATE TABLE `promotional` (
  `promo_id` int(11) NOT NULL,
  `restaurant_id` int(11) DEFAULT NULL,
  `discount` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `promotional`
--

INSERT INTO `promotional` (`promo_id`, `restaurant_id`, `discount`) VALUES
(1, 1, 15),
(2, 2, 18);

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `restaurant_id` int(11) NOT NULL,
  `restaurant_name` varchar(26) NOT NULL,
  `restaurant_rating` float DEFAULT 0,
  `restaurant_address` varchar(50) DEFAULT NULL,
  `restaurant_logo` varchar(300) DEFAULT '../restaurants/default.jpg',
  `restaurant_contact` varchar(15) DEFAULT NULL,
  `restaurant_bg` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`restaurant_id`, `restaurant_name`, `restaurant_rating`, `restaurant_address`, `restaurant_logo`, `restaurant_contact`, `restaurant_bg`) VALUES
(1, 'MasterChef', 5, 'Uttor Badda', '../restaurants/masterchef.jpg', '+880123456789', '../restaurants/MasterBackground.jpg'),
(2, 'Hungry Nak! Food Court', 1.5, 'Vatara', '../restaurants/hnfc.jpg', '+880123456789', '../restaurants/hnfc_bg.jpg'),
(3, 'KFC', 4.5, 'Gulshan-1', '../restaurants/kfc.png', '+880123456789', NULL),
(4, 'Pizza Hut', 4.8, 'Merul Badda', '../restaurants/pizzahut.png', '+880123456789', NULL),
(5, 'Cha Ache? Cafe', 2, 'ChekaPora', 'https://i1.wp.com/brunchvirals.com/wp-content/uploads/2021/05/Cha-Ache-Meme.png?w=1300&ssl=1', '+880123456789', NULL),
(6, 'Foodies Corner', 2.5, 'Malibag', 'https://esmart.com.bd/wp-content/uploads/2018/10/restaurant-interior-design-for-free-software-restaurants.jpg', '+88015266369', NULL),
(10, 'ABC', 3, 'Badda', 'https://media-eng.dhakatribune.com/uploads/2015/10/M-1.jpg', '+88012155154554', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE `usertable` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `user_address` varchar(100) NOT NULL,
  `comment` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `usertable`
--

INSERT INTO `usertable` (`user_id`, `username`, `user_email`, `user_phone`, `user_password`, `user_type`, `user_address`, `comment`) VALUES
(1, 'suriya', 'snisamoni181131@bscse.uiu.ac.bd', '+8801735', '81dc9bdb52d04dc20036dbd8313ed055', 'admin', 'Narayanganj', '1234'),
(2, 'saad', 'a@a.c', '+880961255', '81dc9bdb52d04dc20036dbd8313ed055', 'customer', 'Khilgaon', '1234'),
(3, 'abir', 'abir@a.ul', '+80144555', '81dc9bdb52d04dc20036dbd8313ed055', 'manager', 'Pabna', '1234'),
(4, 'pranto', 'pr@n.ka', '12525', '674f3c2c1a8a6f90461e8a66fb5550ba', 'admin', 'bbaria', '5678'),
(5, 'nafiul', 'na@gmail.com', '+880175858585', '81dc9bdb52d04dc20036dbd8313ed055', 'customer', 'test', '1234'),
(6, 'suraiya', 'MEhEUndFSnhiZ2NuQ3FUSktwT3VVZz09OjrIm6fOVG+Po96Y9vC/hAQ+', 'TFdHdkZWWkpYdVdubU96K0REemJDQT09OjoC4NdkAJkGY4I1Qp3zM2J/', '$2y$10$cPAbsD28iTT4Qr3xuZJ9S.byMtwvVks4o.0I/2KS8uaOkNc.RfWpq', 'admin', 'VTBaaWNCcjB4YUtmbVVUZ2lONTlydz09Ojq4IVq+U1OVYQpoQ/lF9LWg', 'Aa123456@');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `ifk` (`restaurant_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `oui` (`user_id`),
  ADD KEY `oii` (`item_id`);

--
-- Indexes for table `promotional`
--
ALTER TABLE `promotional`
  ADD PRIMARY KEY (`promo_id`),
  ADD KEY `pfk` (`restaurant_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `usertable`
--
ALTER TABLE `usertable`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `promotional`
--
ALTER TABLE `promotional`
  MODIFY `promo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `restaurant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `usertable`
--
ALTER TABLE `usertable`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usertable` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `promotional`
--
ALTER TABLE `promotional`
  ADD CONSTRAINT `promotional_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`restaurant_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
