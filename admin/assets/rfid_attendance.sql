-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 13, 2024 at 07:10 AM
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
-- Database: `rfid_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$KPNoECBnx/flUL/zLC4Le.041ATG2.5MGiBeidKynog2cry9AP8sq', '2024-11-28 12:36:58'),
(2, 'jeremy', '$2y$10$vV/cigGI3kuTE6p.Q2yrTuxg55PWHY/ZcGA5QVSzE20E/Pz2WS.Rm', '2024-12-13 06:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session` enum('morning','afternoon') NOT NULL,
  `log_type` enum('time_in','time_out') NOT NULL,
  `log_time` datetime NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `session`, `log_type`, `log_time`, `timestamp`) VALUES
(141, 1, 'morning', 'time_in', '2024-12-05 20:42:47', '2024-12-05 20:42:47'),
(142, 1, 'morning', 'time_out', '2024-12-05 20:49:20', '2024-12-05 20:49:20'),
(143, 1, 'afternoon', 'time_in', '2024-12-05 20:50:08', '2024-12-05 20:50:08'),
(144, 1, 'morning', 'time_in', '2024-12-08 19:50:26', '2024-12-08 19:50:26'),
(145, 1, 'morning', 'time_out', '2024-12-08 19:51:14', '2024-12-08 19:51:14'),
(146, 1, 'afternoon', 'time_in', '2024-12-08 19:51:23', '2024-12-08 19:51:23'),
(147, 1, 'afternoon', 'time_out', '2024-12-08 19:51:26', '2024-12-08 19:51:26'),
(148, 2, 'morning', 'time_in', '2024-12-08 19:52:08', '2024-12-08 19:52:08'),
(149, 2, 'morning', 'time_out', '2024-12-08 19:52:31', '2024-12-08 19:52:31'),
(150, 2, 'afternoon', 'time_in', '2024-12-08 19:52:43', '2024-12-08 19:52:43'),
(151, 2, 'afternoon', 'time_out', '2024-12-08 19:52:56', '2024-12-08 19:52:56'),
(152, 5, 'morning', 'time_in', '2024-12-08 20:06:20', '2024-12-08 20:06:20'),
(153, 1, 'morning', 'time_in', '2024-11-09 20:38:11', '2024-11-09 20:38:11'),
(154, 1, 'morning', 'time_out', '2024-11-09 20:38:15', '2024-11-09 20:38:15'),
(155, 1, 'afternoon', 'time_in', '2024-11-09 20:38:17', '2024-11-09 20:38:17'),
(156, 1, 'afternoon', 'time_out', '2024-11-09 20:38:20', '2024-11-09 20:38:20'),
(157, 1, 'morning', 'time_in', '2024-12-09 20:40:29', '2024-12-09 20:40:29'),
(158, 1, 'morning', 'time_out', '2024-12-09 20:40:33', '2024-12-09 20:40:33'),
(159, 2, 'morning', 'time_in', '2024-12-09 20:40:35', '2024-12-09 20:40:35'),
(160, 5, 'morning', 'time_in', '2024-12-09 20:40:38', '2024-12-09 20:40:38'),
(161, 2, 'morning', 'time_out', '2024-12-09 20:40:40', '2024-12-09 20:40:40'),
(162, 2, 'afternoon', 'time_in', '2024-12-09 20:40:42', '2024-12-09 20:40:42'),
(163, 2, 'afternoon', 'time_out', '2024-12-09 20:40:45', '2024-12-09 20:40:45'),
(164, 5, 'morning', 'time_out', '2024-12-09 20:40:48', '2024-12-09 20:40:48'),
(165, 5, 'afternoon', 'time_in', '2024-12-09 20:40:50', '2024-12-09 20:40:50'),
(166, 5, 'afternoon', 'time_out', '2024-12-09 20:40:52', '2024-12-09 20:40:52'),
(167, 1, 'afternoon', 'time_in', '2024-12-09 20:40:55', '2024-12-09 20:40:55'),
(168, 1, 'afternoon', 'time_out', '2024-12-09 20:40:57', '2024-12-09 20:40:57'),
(169, 1, 'morning', 'time_in', '2024-12-11 22:20:28', '2024-12-11 22:20:28'),
(170, 1, 'morning', 'time_out', '2024-12-11 22:20:32', '2024-12-11 22:20:32'),
(171, 1, 'afternoon', 'time_in', '2024-12-11 22:20:37', '2024-12-11 22:20:37'),
(172, 1, 'afternoon', 'time_out', '2024-12-11 22:20:45', '2024-12-11 22:20:45'),
(173, 1, 'morning', 'time_in', '2024-12-12 14:30:11', '2024-12-12 14:30:11'),
(174, 1, 'morning', 'time_out', '2024-12-12 14:30:16', '2024-12-12 14:30:16'),
(175, 1, 'afternoon', 'time_in', '2024-12-12 14:30:17', '2024-12-12 14:30:17'),
(176, 1, 'afternoon', 'time_out', '2024-12-12 14:30:19', '2024-12-12 14:30:19'),
(177, 2, 'morning', 'time_in', '2024-12-12 14:30:23', '2024-12-12 14:30:23'),
(178, 2, 'morning', 'time_out', '2024-12-12 14:30:25', '2024-12-12 14:30:25'),
(179, 2, 'afternoon', 'time_in', '2024-12-12 14:30:26', '2024-12-12 14:30:26'),
(180, 2, 'afternoon', 'time_out', '2024-12-12 14:30:28', '2024-12-12 14:30:28'),
(181, 1, 'morning', 'time_in', '2024-12-13 13:57:59', '2024-12-13 13:57:59'),
(182, 1, 'morning', 'time_out', '2024-12-13 13:58:11', '2024-12-13 13:58:11'),
(183, 1, 'afternoon', 'time_in', '2024-12-13 13:58:18', '2024-12-13 13:58:18'),
(184, 1, 'afternoon', 'time_out', '2024-12-13 13:58:24', '2024-12-13 13:58:24');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_times`
--

CREATE TABLE `attendance_times` (
  `id` int(11) NOT NULL,
  `attendance_id` int(11) NOT NULL,
  `time_in_morning` time DEFAULT NULL,
  `time_out_morning` time DEFAULT NULL,
  `time_in_afternoon` time DEFAULT NULL,
  `time_out_afternoon` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `rfid_uid` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `department` varchar(50) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `name`, `email`, `rfid_uid`, `created_at`, `updated_at`, `job_title`, `department`, `status`) VALUES
(1, '111222', 'Carl', 'qweqwe@mail.com', '0624986411', '2024-11-28 11:57:19', NULL, 'Technician', 'Maintenance', 'inactive'),
(2, '22111', 'Ludy', 'ewq@mail.com', '2091137053', '2024-11-28 12:02:43', NULL, 'Engineer II', 'ICT', 'active'),
(5, '11122121', 'Kyle Jeremy', NULL, '2002861064', '2024-12-01 15:02:05', NULL, 'Programmer', 'IT', 'active'),
(6, 'Tech-1234', 'Carl Jeremy Nlakjshdkja', NULL, '3210934280', '2024-12-13 06:06:10', NULL, 'Technician/ICT', 'ICT/ Maintenance', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `attendance_times`
--
ALTER TABLE `attendance_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_id` (`attendance_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfid_uid` (`rfid_uid`),
  ADD UNIQUE KEY `rfid_uid_2` (`rfid_uid`),
  ADD UNIQUE KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `attendance_times`
--
ALTER TABLE `attendance_times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `attendance_times`
--
ALTER TABLE `attendance_times`
  ADD CONSTRAINT `attendance_times_ibfk_1` FOREIGN KEY (`attendance_id`) REFERENCES `attendance` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
