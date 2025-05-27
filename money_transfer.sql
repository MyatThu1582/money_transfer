-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 05:43 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `money_transfer`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashbook`
--

CREATE TABLE `cashbook` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `in_amt` int(11) NOT NULL,
  `out_amt` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cashbook`
--

INSERT INTO `cashbook` (`id`, `date`, `description`, `in_amt`, `out_amt`, `balance`, `category_id`, `created_at`) VALUES
(46, '2025-05-26', ' sfdjsdkfjksa', 20000, 0, 25000, 3, '2025-05-26 12:33:55'),
(47, '2025-05-26', 'Got Pocket Money', 20000, 0, 70000, 1, '2025-05-26 12:20:32'),
(48, '2025-05-26', 'Found Change', 10000, 0, 80000, 1, '2025-05-26 12:20:53'),
(49, '2025-05-26', 'buy food', 0, 5000, 75000, 1, '2025-05-26 12:25:50'),
(50, '2025-05-26', 'fhghg', 15000, 0, 90000, 1, '2025-05-26 12:25:50'),
(51, '2025-05-26', 'Buy Shirt', 0, 40000, 50000, 1, '2025-05-26 12:25:50'),
(52, '2025-05-26', 'Buy Shirt', 0, 2000, 23000, 3, '2025-05-26 12:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `opening_balances`
--

CREATE TABLE `opening_balances` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `opening_amt` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `opening_balances`
--

INSERT INTO `opening_balances` (`id`, `date`, `description`, `opening_amt`, `category_id`) VALUES
(4, '2025-05-26', 'Opening Balance For 26-5-25', 50000, 1),
(5, '2025-05-26', 'Opening Balance For 26-5-25', 5000, 3);

-- --------------------------------------------------------

--
-- Table structure for table `payment_categories`
--

CREATE TABLE `payment_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_categories`
--

INSERT INTO `payment_categories` (`id`, `name`) VALUES
(1, 'KBZ Pay'),
(2, 'WAVE Pay'),
(3, 'AYA Pay'),
(4, 'CB Pay');

-- --------------------------------------------------------

--
-- Table structure for table `percentage`
--

CREATE TABLE `percentage` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `percentage` int(11) NOT NULL,
  `percentage_amt` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `cash_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `percentage`
--

INSERT INTO `percentage` (`id`, `date`, `description`, `percentage`, `percentage_amt`, `category_id`, `cash_id`) VALUES
(12, '2025-05-26', ' sfdjsdkfjksa', 3, 600, 3, 46),
(13, '2025-05-26', 'Got Pocket Money', 3, 600, 1, 47),
(14, '2025-05-26', 'Found Change', 3, 300, 1, 48),
(15, '2025-05-26', 'buy food', 3, 150, 1, 49),
(16, '2025-05-26', 'fhghg', 3, 450, 1, 50),
(17, '2025-05-26', 'Buy Shirt', 3, 1200, 1, 51),
(18, '2025-05-26', 'Buy Shirt', 3, 60, 3, 52);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashbook`
--
ALTER TABLE `cashbook`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opening_balances`
--
ALTER TABLE `opening_balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_categories`
--
ALTER TABLE `payment_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `percentage`
--
ALTER TABLE `percentage`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashbook`
--
ALTER TABLE `cashbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `opening_balances`
--
ALTER TABLE `opening_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment_categories`
--
ALTER TABLE `payment_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `percentage`
--
ALTER TABLE `percentage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
