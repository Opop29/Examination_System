-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 08:38 PM
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
-- Table structure for table `exam_meta`
--

CREATE TABLE `exam_meta` (
  `id` int(11) NOT NULL,
  `exam_title` varchar(255) NOT NULL,
  `course` varchar(50) NOT NULL,
  `points_per_question` int(11) NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `num_questions` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_meta`
--

INSERT INTO `exam_meta` (`id`, `exam_title`, `course`, `points_per_question`, `year_level`, `num_questions`, `created_at`) VALUES
(1, 'try', 'IT', 2, '3rd Year', 3, '2024-11-20 18:44:52'),
(2, 'try', 'IT', 2, '3rd Year', 10, '2024-11-20 18:52:40'),
(3, 'dfsdaf', 'IT', 2, '3rd Year', 10, '2024-11-20 18:57:03'),
(4, 'dfsdaf', 'IT', 2, '3rd Year', 10, '2024-11-20 18:57:47'),
(5, 'dfsdaf', 'IT', 2, '3rd Year', 10, '2024-11-20 18:58:15'),
(6, 'dfsdaf', 'IT', 2, '3rd Year', 10, '2024-11-20 18:58:45'),
(7, 'dfsdaf', 'IT', 2, '3rd Year', 10, '2024-11-20 19:05:31'),
(8, 'dfsdaf', 'IT', 2, '3rd Year', 10, '2024-11-20 19:07:36'),
(9, 'dsads', 'IT', 2, '3rd Year', 5, '2024-11-20 19:08:12'),
(10, 'dfsdaf', 'IT', 2, '3rd Year', 4, '2024-11-20 19:18:48'),
(11, 'dsf', 'IT', 2, '3rd Year', 3, '2024-11-20 19:23:33'),
(12, 'try', 'TEP', 2, '3rd Year', 3, '2024-11-20 19:26:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exam_meta`
--
ALTER TABLE `exam_meta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exam_meta`
--
ALTER TABLE `exam_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
