-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 05:37 PM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'group4', '$2y$10$DCKmH/oOQvRHjrLi4pSy6OTevLDKktcbq4RAodHE56pbW3gKfK2V.', '2024-11-21 03:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `exam_answers`
--

CREATE TABLE `exam_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_answers`
--

INSERT INTO `exam_answers` (`id`, `user_id`, `question_id`, `answer`) VALUES

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
(14, 'dsadsa', 'TEP', 1, '2nd Year', 3, '2024-11-21 02:07:55'),
(15, 'sadsa', 'BS', 2, '2nd Year', 1, '2024-11-21 03:36:50'),
(16, 'sad', 'IT', 2, '1st Year', 4, '2024-11-21 03:59:08'),
(17, 'weeqw', 'Information Technology', 2, '1st Year', 5, '2024-11-21 05:29:44'),
(18, 'dsfsdd', 'BA (Business Administration)', 2, '1st Year', 2, '2024-11-21 06:43:27'),
(19, 'wqewq', 'BA (Business Administration)', 2, '1st Year', 3, '2024-11-24 14:21:52'),
(20, 'gdgsdfgds', 'Information Technology', 3, '1st Year', 2, '2024-11-24 16:21:57');

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE `exam_questions` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('mcq','tf','fill') NOT NULL,
  `options` text DEFAULT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_questions`
--

INSERT INTO `exam_questions` (`id`, `exam_id`, `question_text`, `question_type`, `options`, `correct_answer`, `created_at`) VALUES
(12, 14, 'qwe', 'mcq', '{\"A\":\"wqe\",\"B\":\"wqe\",\"C\":\"weq\"}', 'C', '2024-11-21 02:08:10'),
(13, 14, 'weqew', 'tf', NULL, 'False', '2024-11-21 02:08:10'),
(14, 14, 'wqeqw', 'fill', NULL, 'wqeqw', '2024-11-21 02:08:10'),
(15, 16, 'sczc', 'mcq', '{\"A\":\"csz\",\"B\":\"scz\"}', 'A', '2024-11-21 03:59:28'),
(16, 16, 'czsc', 'mcq', '{\"A\":\"zsc\",\"B\":\"zcs\",\"C\":\"czs\"}', 'A', '2024-11-21 03:59:28'),
(17, 16, 'czs', 'tf', NULL, 'False', '2024-11-21 03:59:28'),
(18, 16, 'czs', 'fill', NULL, 'czs', '2024-11-21 03:59:28'),
(19, 17, '433', 'mcq', '{\"A\":\"eqw\",\"B\":\"ew\"}', 'B', '2024-11-21 05:30:02'),
(20, 17, 'weqw', 'tf', NULL, 'False', '2024-11-21 05:30:02'),
(21, 17, 'weq', 'tf', NULL, 'True', '2024-11-21 05:30:02'),
(22, 17, 'weqw', 'fill', NULL, 'qwe', '2024-11-21 05:30:02'),
(23, 17, 'qwewq', 'fill', NULL, 'wqe', '2024-11-21 05:30:02'),
(24, 18, 'sada', 'mcq', '{\"A\":\"asdsa\",\"B\":\"sa\",\"C\":\"sads\"}', 'B', '2024-11-21 06:43:37'),
(25, 18, 'sad', 'tf', NULL, 'False', '2024-11-21 06:43:37'),
(26, 19, 'dsfds', 'fill', NULL, 'dsfsdf', '2024-11-24 14:22:00'),
(27, 19, 'dsfsdf', 'fill', NULL, 'fsdf', '2024-11-24 14:22:00'),
(28, 19, 'dfdsfds', 'fill', NULL, 'dsfsdf', '2024-11-24 14:22:00'),
(29, 20, 'qwe', 'tf', NULL, 'False', '2024-11-24 16:22:13'),
(30, 20, 'wqewq', 'tf', NULL, 'True', '2024-11-24 16:22:13');

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `total_correct` int(11) NOT NULL,
  `total_points` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_questions` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_results`
--

INSERT INTO `exam_results` (`id`, `user_id`, `exam_id`, `total_correct`, `total_points`, `created_at`, `total_questions`) VALUES
(12, 5, 17, 2, 4, '2024-11-24 16:05:48', 5),
(13, 5, 17, 2, 4, '2024-11-24 16:07:37', 5),
(14, 5, 17, 1, 2, '2024-11-24 16:08:10', 5),
(15, 5, 17, 1, 2, '2024-11-24 16:09:39', 5),
(16, 5, 17, 1, 2, '2024-11-24 16:12:01', 5),
(17, 5, 17, 1, 2, '2024-11-24 16:19:19', 5),
(18, 5, 20, 1, 3, '2024-11-24 16:22:35', 2),
(19, 5, 20, 1, 3, '2024-11-24 16:29:47', 2),
(20, 5, 20, 1, 3, '2024-11-24 16:30:27', 2),
(21, 5, 20, 1, 3, '2024-11-24 16:32:56', 2);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `randomfeedback`
--

CREATE TABLE `randomfeedback` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `randomfeedback`
--

INSERT INTO `randomfeedback` (`id`, `message`, `created_at`) VALUES
(1, 'sdfsdfsdf', '2024-11-24 13:47:35'),
(2, 'dasd', '2024-11-24 13:49:52');

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
(5, 'justine', '$2y$10$.tfSIxMwUt3BL7keIjiyreoJUXrtwMl.cOo21sthQG5Tuog5s/Nk6', '2024-11-21 11:27:49', 1, 'justine@nbsc.edu.ph', 'Information Technology', '1st Year', 'user'),
(6, 'lololo', '$2y$10$RMQNnC1nik4nuKwWHSaNk.9mEH2CD9GaFhLGqSpzTtAve1FUnGUHS', '2024-11-24 11:02:16', 1, 'lolo@nbsc.edu.ph', 'Information Technology', '1st Year', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `exam_answers`
--
ALTER TABLE `exam_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `exam_meta`
--
ALTER TABLE `exam_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `randomfeedback`
--
ALTER TABLE `randomfeedback`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exam_answers`
--
ALTER TABLE `exam_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT for table `exam_meta`
--
ALTER TABLE `exam_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `exam_questions`
--
ALTER TABLE `exam_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `randomfeedback`
--
ALTER TABLE `randomfeedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exam_answers`
--
ALTER TABLE `exam_answers`
  ADD CONSTRAINT `exam_answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `exam_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD CONSTRAINT `exam_questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam_meta` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD CONSTRAINT `exam_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_results_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exam_meta` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
