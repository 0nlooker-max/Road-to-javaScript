-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 10:50 PM
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
-- Database: `javascript_try`
--

-- --------------------------------------------------------

--
-- Table structure for table `prilimtable`
--

CREATE TABLE `prilimtable` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `course` varchar(45) NOT NULL,
  `user_address` varchar(45) NOT NULL,
  `birthdate` date NOT NULL,
  `profile` varchar(100) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prilimtable`
--

INSERT INTO `prilimtable` (`student_id`, `first_name`, `last_name`, `email`, `gender`, `course`, `user_address`, `birthdate`, `profile`, `date_created`) VALUES
(2, 'cyruss', 'panlaan', 'clinic@gmail.com', 'Male', 'bsgg', 'camayangoy dapit dapit', '2004-08-24', '', '2025-03-03 21:32:22');

-- --------------------------------------------------------

--
-- Table structure for table `tablescript`
--

CREATE TABLE `tablescript` (
  `product_id` int(11) NOT NULL,
  `Product_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tablescript`
--

INSERT INTO `tablescript` (`product_id`, `Product_name`) VALUES
(1, 'PANLAAN'),
(2, 'WTX'),
(3, 'Lemon'),
(4, 'Lemon'),
(36, 'watataps');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prilimtable`
--
ALTER TABLE `prilimtable`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `tablescript`
--
ALTER TABLE `tablescript`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prilimtable`
--
ALTER TABLE `prilimtable`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tablescript`
--
ALTER TABLE `tablescript`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
