-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Nov 13, 2025 at 03:00 AM
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
-- Database: `dbbarber`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cust_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`cust_id`, `username`, `password`, `email`) VALUES
(4, 'kimi', '123', 'hakimi.royal@gmail.com'),
(5, 'yat', '321', 'yat@gmail.com'),
(6, 'hiwayat', '1234', 'faisal@gmail.com'),
(7, 'fatehah', 'kimiperutbulu', 'tehacomel@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receipt_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt`
--

INSERT INTO `receipt` (`receipt_id`, `cust_id`, `date`, `time`, `notes`, `name`, `created_at`) VALUES
(4, 5, '2025-11-13', '04:49:00', 'wassqs', 'yat', '2025-11-11 20:46:20'),
(5, 6, '2028-02-22', '22:00:00', 'fade', 'faisal@gmail.com', '2025-11-11 20:54:54'),
(7, 6, '2025-11-12', '17:04:00', 'low fade', 'hiwayat', '2025-11-11 21:05:02'),
(8, 4, '2025-11-13', '05:11:00', 'fade', 'kimi1122', '2025-11-11 21:10:03'),
(9, 4, '2025-11-13', '05:16:00', 'trim', 'kiki', '2025-11-11 21:12:33'),
(11, 7, '2025-11-07', '05:20:00', 'dye', 'fatehah', '2025-11-11 21:18:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receipt_id`),
  ADD UNIQUE KEY `unique_slot` (`date`,`time`),
  ADD KEY `fk_receipt_customer` (`cust_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `fk_receipt_customer` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`cust_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
