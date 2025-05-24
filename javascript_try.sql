-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2025 at 08:52 AM
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
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `task_title` varchar(45) NOT NULL,
  `discription` varchar(50) NOT NULL,
  `deadline` datetime NOT NULL,
  `Date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_title`, `discription`, `deadline`, `Date_created`) VALUES
(7, 'putik', 'huhuhuhuh2', '2025-05-24 00:55:00', '2025-05-24 04:46:29'),
(8, 'power', 'cant', '2025-05-23 16:55:00', '2025-05-23 23:44:45'),
(9, 'hehey', 'waybouts', '2025-05-24 00:55:00', '2025-05-23 18:59:27'),
(10, 'happy', 'cantttt', '2025-05-24 09:11:00', '2025-05-23 18:58:29'),
(11, 'adohno', 'matulog sayu', '2025-05-25 04:41:00', '2025-05-24 06:42:03');

-- --------------------------------------------------------

--
-- Table structure for table `task_assignment`
--

CREATE TABLE `task_assignment` (
  `assignment_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` varchar(45) NOT NULL,
  `attach_link` varchar(255) DEFAULT NULL,
  `attach_file` varchar(255) DEFAULT NULL,
  `date_submitted` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_assignment`
--

INSERT INTO `task_assignment` (`assignment_id`, `task_id`, `student_id`, `status`, `attach_link`, `attach_file`, `date_submitted`) VALUES
(24, 10, 26, 'Pending', NULL, NULL, '2025-05-23 23:29:05'),
(25, 10, 27, 'Submitted', 'http://localhost/phpmyadmin/index.php?route=/sql&pos=0&db=javascript_try&table=task_assignment', NULL, '2025-05-23 23:29:05'),
(26, 9, 26, 'Pending', NULL, NULL, '2025-05-23 23:29:05'),
(27, 9, 27, 'Pending', NULL, NULL, '2025-05-23 23:29:05'),
(29, 8, 26, 'Pending', NULL, NULL, '2025-05-23 23:44:45'),
(30, 8, 26, 'Pending', NULL, NULL, '2025-05-23 23:44:45'),
(31, 8, 27, 'Pending', NULL, NULL, '2025-05-23 23:44:45'),
(32, 8, 27, 'Pending', NULL, NULL, '2025-05-23 23:44:45'),
(33, 7, 26, 'Pending', NULL, NULL, '2025-05-24 04:46:29'),
(34, 7, 27, 'Pending', NULL, NULL, '2025-05-24 04:46:29'),
(35, 11, 26, 'Pending', NULL, NULL, '2025-05-24 06:42:03'),
(36, 11, 27, 'Pending', NULL, NULL, '2025-05-24 06:42:03');

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
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`student_id`, `first_name`, `last_name`, `email`, `gender`, `phone_number`, `course`, `user_address`, `birthdate`, `profile_image`, `user_password`, `verification_code`, `is_verified`, `date_created`, `role`) VALUES
(24, 'ricksel', 'pagatpat', 'rexpagatpat@gmail.com', 'Male', 77777, 'BIST', 'caynag buyhaatsy', '2009-02-01', '../profiles/asta union.jpeg', '$2y$10$W8WVYXgu9EvseTsQxhuodO4lzGnT3VL5OYgfWcXWtfEjMEKIz0Ca2', '', 1, '2025-05-24 06:50:43', 'admin'),
(25, 'Lynlyn', 'gfdggr', 'rex@gmial.com', 'Female', 324235, 'dgd', 'caynag buyhaatsy', '2004-02-20', NULL, '$2y$10$BzcQOndG0D1zN8bu05zHVeZbU3ALBRimP3sa4naPiQz8NLm5FH5Zy', '0e591a792c164d58afb527b841baff45', 0, '2025-05-14 20:02:31', 'student'),
(26, 'wdd', 'dssd', 'rexpagatpat@gmail.com', 'Male', 23123, 'BSIT', 'saadd', '2222-02-22', NULL, '$2y$10$p5.M7d1u3ghWeFnhMFjWPuZyDBXX8Nfm/U2GxuBxwSwexd/L1bHLO', '', 1, '2025-05-14 20:02:22', 'student'),
(27, 'rikki', 'pagatpat', 'prikkirose@gmail.com', 'Female', 35252353, 'bsat', '12dfgsdg', '2003-06-03', 'profiles/Natori.jpg', '$2y$10$8BtkzrseFzHke.FaY5S09uj7Tkb5AETqCRZjfovS96FNFjpXQ9lhG', '', 1, '2025-05-23 19:15:15', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `task_assignment`
--
ALTER TABLE `task_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `task_assignment`
--
ALTER TABLE `task_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task_assignment`
--
ALTER TABLE `task_assignment`
  ADD CONSTRAINT `task_assignment_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`),
  ADD CONSTRAINT `task_assignment_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
