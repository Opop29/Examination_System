-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 10:14 AM
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
-- Database: `examsystem`
--

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
(30, 20, 'wqewq', 'tf', NULL, 'True', '2024-11-24 16:22:13'),
(31, 21, 'sdsad', 'tf', NULL, 'True', '2024-11-24 18:13:07'),
(32, 21, 'dasd', 'tf', NULL, 'True', '2024-11-24 18:13:07'),
(33, 22, 'ewqe', 'mcq', '{\"A\":\"wqe\",\"B\":\"wqe\"}', 'A', '2024-11-24 18:57:40'),
(34, 22, 'weqew', 'mcq', '{\"A\":\"asd\",\"B\":\"das\",\"C\":\"das\"}', 'A', '2024-11-24 18:57:40'),
(35, 23, 'sada', 'mcq', '{\"A\":\"sadas\",\"B\":\"sdas\"}', 'A', '2024-11-24 19:48:41'),
(36, 24, 'dfsefgsdf', 'mcq', '{\"A\":\"dsfsdf\",\"B\":\"sdfsdf\"}', 'B', '2024-11-26 00:49:34'),
(37, 24, 'dfsdf', 'tf', NULL, 'True', '2024-11-26 00:49:34'),
(38, 25, 'fsdfsdg', 'mcq', '{\"A\":\"gbbj\",\"B\":\"fewfs\",\"C\":\"sdfsd\"}', 'B', '2024-11-26 04:39:46'),
(39, 25, 'xgsdg', 'tf', NULL, 'True', '2024-11-26 04:39:46'),
(40, 25, 'safsfsdfsd', 'fill', NULL, 'sdgsdgsdg', '2024-11-26 04:39:46'),
(41, 26, 'gfg', 'tf', NULL, 'True', '2024-12-01 03:25:27'),
(42, 26, 'fgdfg', 'tf', NULL, 'True', '2024-12-01 03:25:28'),
(43, 27, 'sdasd', 'tf', NULL, 'True', '2024-12-01 03:32:31'),
(44, 27, 'sadsa', 'tf', NULL, 'True', '2024-12-01 03:32:32'),
(45, 27, 'sadsa', 'tf', NULL, 'True', '2024-12-01 03:32:32'),
(46, 28, 'sadsad', 'fill', NULL, 'sadsad', '2024-12-01 03:38:22'),
(47, 28, 'sadsa', 'fill', NULL, 'sadsa', '2024-12-01 03:38:22'),
(48, 29, '12', 'tf', NULL, 'True', '2024-12-02 08:52:36'),
(49, 31, 'dfgfd', 'fill', NULL, 'dsfsdf', '2024-12-02 08:57:08'),
(50, 32, 'fgdg', 'fill', NULL, 'fgdfg', '2024-12-02 09:11:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exam_questions`
--
ALTER TABLE `exam_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD CONSTRAINT `exam_questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam_meta` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
