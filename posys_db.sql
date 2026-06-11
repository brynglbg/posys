-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql100.infinityfree.com
-- Generation Time: Jun 11, 2026 at 01:11 PM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40338813_opsys`
--

-- --------------------------------------------------------

--
-- Table structure for table `bo`
--

CREATE TABLE `bo` (
  `id` int(11) NOT NULL,
  `bor_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `pickedup_by` varchar(50) NOT NULL,
  `pickedup_at` varchar(50) NOT NULL,
  `encoded_at` varchar(50) NOT NULL,
  `created_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bor`
--

CREATE TABLE `bor` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `created_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bor_product`
--

CREATE TABLE `bor_product` (
  `id` int(11) NOT NULL,
  `bor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `expired_at` varchar(50) NOT NULL,
  `qty` float NOT NULL,
  `pickup_status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bo_product`
--

CREATE TABLE `bo_product` (
  `id` int(11) NOT NULL,
  `bo_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `expired_at` varchar(50) NOT NULL,
  `qty` float NOT NULL,
  `encode_status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `name`, `slug`, `icon`, `sort`) VALUES
(10, 'Purhases', 'purchases', 'box', 1),
(18, 'Vendor', 'vendor', 'person-workspace', 3),
(19, 'Bad Order', 'bad_order', 'clipboard-x', 2);

-- --------------------------------------------------------

--
-- Table structure for table `page_sub`
--

CREATE TABLE `page_sub` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `page_sub`
--

INSERT INTO `page_sub` (`id`, `page_id`, `name`, `slug`, `sort`) VALUES
(3, 19, 'BO Request', 'bo_request', 1),
(4, 19, 'BO Monitoring', 'bo_monitoring', 2);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`id`, `name`) VALUES
(1, 'Cash'),
(2, 'Check'),
(3, 'Bank Transfer');

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `discount` float NOT NULL,
  `delivered_at` varchar(50) NOT NULL,
  `encoded_at` varchar(50) NOT NULL,
  `encoding_note` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payment`
--

CREATE TABLE `purchase_payment` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `paid_at` varchar(50) NOT NULL,
  `method` int(11) NOT NULL,
  `source` varchar(50) NOT NULL,
  `reference` text NOT NULL,
  `amount` float NOT NULL,
  `note` text NOT NULL,
  `created_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product`
--

CREATE TABLE `purchase_product` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cost` float NOT NULL,
  `qty` float NOT NULL,
  `encode_status` tinyint(4) NOT NULL,
  `barcode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sys`
--

CREATE TABLE `sys` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `default_upass` varchar(50) NOT NULL,
  `timezone` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `sys`
--

INSERT INTO `sys` (`id`, `name`, `address`, `default_upass`, `timezone`) VALUES
(1, 'POSYS', 'Manila, Philippines', '12345678', 'Asia/Manila');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `uname` varchar(50) NOT NULL,
  `upass` varchar(100) NOT NULL,
  `type` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `created_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `fname`, `lname`, `uname`, `upass`, `type`, `role`, `created_at`) VALUES
(17, 'Bryan Gil', 'Ganituen', 'bryangil', '$2y$10$bLghfPQc.B5MHACzkSczCe0O.QrqqYz8eqLQq/43zgKGTX71IHptO', 1, 0, '2017-01-17 00:00:00'),
(24, 'Daisy', 'Cordero', 'daisycord', '$2y$10$qcDhIQ1qVyD/FdCgOiaDKOaw5SR.9XfrZzSr9nc5jlMhy0MZm7NMO', 1, 1, '2026-04-23 13:02:54'),
(26, 'Thalia', 'Burgos', 'nathalia', '$2y$10$ElKunn6ookjkPslFE77ZQ.AeCIneUIHM48ujfyc/uR4vRhbjKt3Se', 2, 0, '2026-04-27 10:56:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_page_access`
--

CREATE TABLE `user_page_access` (
  `user_id` int(11) NOT NULL,
  `page` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ;

--
-- Dumping data for table `user_page_access`
--

INSERT INTO `user_page_access` (`user_id`, `page`, `page_sub`) VALUES
(26, '[\"10\", \"19\"]', '[\"3\"]');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `name`) VALUES
(1, 'PO Payment Encoder');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Non-admin');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `agent_name` varchar(50) NOT NULL,
  `contact_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_product`
--

CREATE TABLE `vendor_product` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cost` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bo`
--
ALTER TABLE `bo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bor`
--
ALTER TABLE `bor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bor_product`
--
ALTER TABLE `bor_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bo_product`
--
ALTER TABLE `bo_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_sub`
--
ALTER TABLE `page_sub`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_payment`
--
ALTER TABLE `purchase_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_product`
--
ALTER TABLE `purchase_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys`
--
ALTER TABLE `sys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_product`
--
ALTER TABLE `vendor_product`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bo`
--
ALTER TABLE `bo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bor`
--
ALTER TABLE `bor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bor_product`
--
ALTER TABLE `bor_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bo_product`
--
ALTER TABLE `bo_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `page_sub`
--
ALTER TABLE `page_sub`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_payment`
--
ALTER TABLE `purchase_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_product`
--
ALTER TABLE `purchase_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys`
--
ALTER TABLE `sys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_page_access`
--
ALTER TABLE `user_page_access`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_product`
--
ALTER TABLE `vendor_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
