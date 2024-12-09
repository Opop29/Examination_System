-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2024 at 05:44 AM
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
-- Database: `online_exam_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `first_login` tinyint(1) NOT NULL DEFAULT 1,
  `email` varchar(255) NOT NULL,
  `course` varchar(50) NOT NULL,
  `year_level` varchar(10) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `first_login`, `email`, `course`, `year_level`, `role`) VALUES
(2, 'opop', '$2y$10$dnw6z6nTv03pxDNN7ymdlO2/KQ0de.ihUL3ySUhgAJKzl/EZubgdG', '2024-11-21 01:46:05', 1, 'quidit@nbsc.edu.ph', 'Information Technology', '3rd Year', 'user'),
(4, 'lovely', '$2y$10$KRbZHwR.k2AbeQtFxi1NVO6htGAFAvIR7EZ..7XFrrgsIRpNU67Sq', '2024-11-21 11:10:41', 1, 'lovelydale@nbsc.edu.ph', 'BA (Business Administration)', '1st Year', 'user'),
(5, 'justine', '$2y$10$.tfSIxMwUt3BL7keIjiyreoJUXrtwMl.cOo21sthQG5Tuog5s/Nk6', '2024-11-21 11:27:49', 1, 'justine@nbsc.edu.ph', 'Information Technology', '1st Year', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
