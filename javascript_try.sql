-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 02:22 AM
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
  `profile` varchar(100) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prilimtable`
--

INSERT INTO `prilimtable` (`student_id`, `first_name`, `last_name`, `email`, `gender`, `course`, `user_address`, `birthdate`, `profile`, `date_created`) VALUES
(12, 'kent Daril', 'Pantalan', 'rex@gmial.com', 'Male', 'BSIT major in CRIM', 'BanakodDaanbantayan', '2025-02-25', 'profiles/1741851970_download.jpg', '2025-03-13 07:46:10'),
(13, 'ricksel', 'pagatpat', 'whattataps@gmail.com', 'Male', 'BSIT', 'mano', '2004-08-24', 'profiles/asta union.jpeg', '2025-03-18 05:14:44'),
(14, 'REX', 'pagatpat', 'whattataps@gmail.com', 'Male', 'BSIT', 'mano', '2009-04-28', 'profiles/black.webp', '2025-03-18 06:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `srudents_info`
--

CREATE TABLE `srudents_info` (
  `assinged_id` int(11) NOT NULL,
  `student_name` varchar(45) NOT NULL,
  `school` varchar(45) NOT NULL,
  `school_address` varchar(45) NOT NULL,
  `contact_number` int(11) NOT NULL,
  `coordinator` varchar(45) NOT NULL,
  `organization` varchar(45) NOT NULL,
  `date_started` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `srudents_info`
--

INSERT INTO `srudents_info` (`assinged_id`, `student_name`, `school`, `school_address`, `contact_number`, `coordinator`, `organization`, `date_started`) VALUES
(2, 'panlaan', 'asfasf', 'afaf', 0, 'sfaaf', 'afafg', '2024-12-29');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `gender` varchar(45) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `course` varchar(45) NOT NULL,
  `user_address` varchar(45) NOT NULL,
  `birthdate` date NOT NULL,
  `profile_image` varchar(100) DEFAULT NULL,
  `user_password` varchar(100) NOT NULL,
  `verification_code` varchar(255) NOT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`student_id`, `first_name`, `last_name`, `email`, `gender`, `phone_number`, `course`, `user_address`, `birthdate`, `profile_image`, `user_password`, `verification_code`, `is_verified`, `date_created`) VALUES
(17, 'ricksel', 'pagatpat', 'rexpagatpat@gmail.com', 'Male', 987655443, 'BIST', 'caynag buyhaatsy', '2004-08-24', NULL, '$2y$10$VGH/h9/yAbBvyUV/VtfiaOg8AwJ6LzLZE31QS9h6fBGgbwNMEz1/e', '', 1, '2025-03-27 01:11:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prilimtable`
--
ALTER TABLE `prilimtable`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `srudents_info`
--
ALTER TABLE `srudents_info`
  ADD PRIMARY KEY (`assinged_id`);

--
-- Indexes for table `tablescript`
--
ALTER TABLE `tablescript`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prilimtable`
--
ALTER TABLE `prilimtable`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `srudents_info`
--
ALTER TABLE `srudents_info`
  MODIFY `assinged_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tablescript`
--
ALTER TABLE `tablescript`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
