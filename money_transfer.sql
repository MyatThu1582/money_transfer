-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 02:47 PM
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
(53, '2025-05-27', 'Got Pocket Money', 15000, 0, 35000, 3, '2025-05-27 11:32:01'),
(54, '2025-05-27', 'Found Change', 5000, 0, 40000, 3, '2025-05-27 11:32:26'),
(55, '2025-05-27', 'buy food', 0, 10000, 30000, 3, '2025-05-27 11:32:42'),
(56, '2025-05-25', ' js klfjsdkl jfsldk', 0, 20000, 80000, 3, '2025-05-27 11:53:51'),
(57, '2025-05-25', 'buy drinks', 0, 2000, 78000, 3, '2025-05-27 11:54:17'),
(58, '2025-05-25', 'Buy jeans', 0, 45000, 33000, 3, '2025-05-27 11:55:50'),
(59, '2025-05-25', 'got Change', 2000, 0, 35000, 3, '2025-05-27 11:56:08'),
(60, '2025-05-26', ' fsjs klfjs', 20000, 0, 50000, 1, '2025-05-27 11:57:10'),
(61, '2025-05-26', 'kjskdlfjsf', 0, 10000, 40000, 1, '2025-05-27 11:57:26'),
(62, '2025-05-26', 'popo pop oo', 5000, 0, 45000, 1, '2025-05-27 11:57:43'),
(63, '2025-05-26', 'sdjflks fjsl ', 0, 25000, 20000, 1, '2025-05-27 11:58:07'),
(64, '2025-05-27', 'Got Pocket Money', 50000, 0, 51000, 1, '2025-05-27 11:59:06'),
(65, '2025-05-27', 'dfdsfsdfsd', 13000, 0, 64000, 1, '2025-05-27 11:59:27'),
(66, '2025-05-27', 'Found Change', 6000, 0, 70000, 1, '2025-05-27 11:59:56'),
(67, '2025-05-27', 'Buy Book', 0, 20000, 50000, 1, '2025-05-27 12:00:09');

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
(6, '2025-05-27', 'Opening Balance For 27-5-25', 20000, 3),
(7, '2025-05-25', 'Opening Balance For 25-5-25', 100000, 3),
(8, '2025-05-26', 'Opening Balance For 26-5-25', 30000, 1),
(9, '2025-05-27', 'Opening Balance For 27-5-25', 1000, 1);

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
  `inorout` varchar(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `cash_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `percentage`
--

INSERT INTO `percentage` (`id`, `date`, `description`, `percentage`, `percentage_amt`, `inorout`, `category_id`, `cash_id`) VALUES
(19, '2025-05-27', 'Got Pocket Money', 3, 450, 'in', 3, 53),
(20, '2025-05-27', 'Found Change', 3, 150, 'in', 3, 54),
(21, '2025-05-27', 'buy food', 3, 300, 'out', 3, 55),
(22, '2025-05-25', ' js klfjsdkl jfsldk', 3, 600, 'out', 3, 56),
(23, '2025-05-25', 'buy drinks', 3, 60, 'out', 3, 57),
(24, '2025-05-25', 'Buy jeans', 3, 1350, 'out', 3, 58),
(25, '2025-05-25', 'got Change', 3, 60, 'in', 3, 59),
(26, '2025-05-26', ' fsjs klfjs', 3, 600, 'in', 1, 60),
(27, '2025-05-26', 'kjskdlfjsf', 3, 300, 'out', 1, 61),
(28, '2025-05-26', 'popo pop oo', 3, 150, 'in', 1, 62),
(29, '2025-05-26', 'sdjflks fjsl ', 3, 750, 'out', 1, 63),
(30, '2025-05-27', 'Got Pocket Money', 3, 1500, 'in', 1, 64),
(31, '2025-05-27', 'dfdsfsdfsd', 3, 390, 'in', 1, 65),
(32, '2025-05-27', 'Found Change', 3, 180, 'in', 1, 66),
(33, '2025-05-27', 'Buy Book', 3, 600, 'out', 1, 67);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `opening_balances`
--
ALTER TABLE `opening_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payment_categories`
--
ALTER TABLE `payment_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `percentage`
--
ALTER TABLE `percentage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
