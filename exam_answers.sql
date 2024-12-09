-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 05:27 PM
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
-- Table structure for table `exam_answers`
--

CREATE TABLE `exam_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `exam_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_answers`
--

INSERT INTO `exam_answers` (`id`, `user_id`, `question_id`, `answer`, `exam_id`) VALUES
(1, 5, 19, 'A', NULL),
(2, 5, 20, 'True', NULL),
(3, 5, 21, 'True', NULL),
(4, 5, 22, 'efde', NULL),
(5, 5, 23, 'wefw', NULL),
(6, 4, 24, 'B', NULL),
(7, 4, 25, 'True', NULL),
(8, 5, 19, 'A', NULL),
(9, 5, 20, 'True', NULL),
(10, 5, 21, 'True', NULL),
(11, 5, 22, 'efde', NULL),
(12, 5, 23, 'wefw', NULL),
(13, 5, 19, 'A', NULL),
(14, 5, 20, 'True', NULL),
(15, 5, 21, 'True', NULL),
(16, 5, 22, 'efde', NULL),
(17, 5, 23, 'wefw', NULL),
(18, 5, 19, 'B', NULL),
(19, 5, 20, 'False', NULL),
(20, 5, 21, 'True', NULL),
(21, 5, 22, 'asdsad', NULL),
(22, 5, 23, 'sdasd', NULL),
(23, 4, 26, 'sad', NULL),
(24, 4, 27, 'asdsa', NULL),
(25, 4, 28, 'dsad', NULL),
(26, 4, 26, 'dsdf', NULL),
(27, 4, 27, 'dfsf', NULL),
(28, 4, 28, 'dsfsd', NULL),
(29, 4, 24, 'A', NULL),
(30, 4, 25, '', NULL),
(31, 4, 24, 'C', NULL),
(32, 4, 25, '', NULL),
(33, 4, 24, 'C', NULL),
(34, 4, 25, '', NULL),
(35, 4, 26, 'dsfdsf', NULL),
(36, 4, 27, 'dsfds', NULL),
(37, 4, 28, 'fsdf', NULL),
(38, 5, 19, 'A', NULL),
(39, 5, 20, '', NULL),
(40, 5, 21, '', NULL),
(41, 5, 22, 'dsa', NULL),
(42, 5, 23, 'sdas', NULL),
(43, 5, 19, 'B', NULL),
(44, 5, 20, 'True', NULL),
(45, 5, 21, 'True', NULL),
(46, 5, 22, 'sdas', NULL),
(47, 5, 23, 'dasd', NULL),
(48, 5, 19, 'B', NULL),
(49, 5, 20, 'True', NULL),
(50, 5, 21, 'True', NULL),
(51, 5, 22, 'sdas', NULL),
(52, 5, 23, 'dasd', NULL),
(53, 5, 19, 'B', NULL),
(54, 5, 20, 'True', NULL),
(55, 5, 21, 'True', NULL),
(56, 5, 22, 'sdas', NULL),
(57, 5, 23, 'dasd', NULL),
(58, 5, 19, 'A', NULL),
(59, 5, 20, 'True', NULL),
(60, 5, 21, 'False', NULL),
(61, 5, 22, 'ds', NULL),
(62, 5, 23, 'sds', NULL),
(63, 5, 19, 'A', NULL),
(64, 5, 20, 'True', NULL),
(65, 5, 21, 'False', NULL),
(66, 5, 22, 'ds', NULL),
(67, 5, 23, 'sds', NULL),
(68, 5, 19, 'A', NULL),
(69, 5, 20, 'True', NULL),
(70, 5, 21, 'True', NULL),
(71, 5, 22, 'ds', NULL),
(72, 5, 23, 'sd', NULL),
(73, 5, 19, 'A', NULL),
(74, 5, 20, 'True', NULL),
(75, 5, 21, 'True', NULL),
(76, 5, 22, 'ds', NULL),
(77, 5, 23, 'sd', NULL),
(78, 5, 19, 'B', NULL),
(79, 5, 20, 'True', NULL),
(80, 5, 21, 'True', NULL),
(81, 5, 22, 'ds', NULL),
(82, 5, 23, 'ds', NULL),
(83, 5, 19, 'A', NULL),
(84, 5, 20, 'True', NULL),
(85, 5, 21, 'True', NULL),
(86, 5, 22, 'sa', NULL),
(87, 5, 23, 'as', NULL),
(88, 5, 19, 'A', NULL),
(89, 5, 20, 'True', NULL),
(90, 5, 21, 'True', NULL),
(91, 5, 22, 'sa', NULL),
(92, 5, 23, 'as', NULL),
(93, 5, 19, 'A', NULL),
(94, 5, 20, 'True', NULL),
(95, 5, 21, 'True', NULL),
(96, 5, 22, 'sa', NULL),
(97, 5, 23, 'as', NULL),
(98, 5, 19, 'A', NULL),
(99, 5, 20, 'True', NULL),
(100, 5, 21, 'False', NULL),
(101, 5, 22, 'sadsa', NULL),
(102, 5, 23, 'sadsa', NULL),
(103, 5, 19, 'A', NULL),
(104, 5, 20, 'True', NULL),
(105, 5, 21, 'False', NULL),
(106, 5, 22, 'sadsa', NULL),
(107, 5, 23, 'sadsa', NULL),
(108, 5, 19, 'A', NULL),
(109, 5, 20, 'True', NULL),
(110, 5, 21, 'False', NULL),
(111, 5, 22, 'sadsa', NULL),
(112, 5, 23, 'sadsa', NULL),
(113, 5, 19, 'A', NULL),
(114, 5, 20, 'True', NULL),
(115, 5, 21, 'False', NULL),
(116, 5, 22, 'sadsa', NULL),
(117, 5, 23, 'sadsa', NULL),
(118, 5, 19, 'A', NULL),
(119, 5, 20, 'True', NULL),
(120, 5, 21, 'True', NULL),
(121, 5, 19, 'A', NULL),
(122, 5, 20, 'True', NULL),
(123, 5, 21, 'True', NULL),
(124, 5, 19, 'A', NULL),
(125, 5, 20, 'True', NULL),
(126, 5, 21, 'True', NULL),
(127, 5, 19, 'A', NULL),
(128, 5, 20, 'True', NULL),
(129, 5, 21, 'True', NULL),
(130, 5, 22, '', NULL),
(131, 5, 23, '', NULL),
(132, 5, 19, 'A', NULL),
(133, 5, 20, 'True', NULL),
(134, 5, 21, 'True', NULL),
(135, 5, 22, 'dfsf', NULL),
(136, 5, 23, 'fsdfs', NULL),
(137, 5, 19, 'A', NULL),
(138, 5, 20, 'True', NULL),
(139, 5, 21, 'False', NULL),
(140, 5, 22, 'dsa', NULL),
(141, 5, 23, 'sadas', NULL),
(142, 5, 19, 'B', NULL),
(143, 5, 20, '0', NULL),
(144, 5, 21, '0', NULL),
(145, 5, 19, 'B', NULL),
(146, 5, 20, '0', NULL),
(147, 5, 21, '0', NULL),
(148, 5, 19, 'A', NULL),
(149, 5, 20, 'False', NULL),
(150, 5, 21, 'True', NULL),
(151, 5, 22, 'sdasd', NULL),
(152, 5, 23, 'das', NULL),
(153, 5, 19, 'A', NULL),
(154, 5, 19, 'A', NULL),
(155, 5, 20, 'False', NULL),
(156, 5, 21, 'True', NULL),
(157, 5, 22, 'dsads', NULL),
(158, 5, 23, 'sadsa', NULL),
(159, 5, 19, 'A', NULL),
(160, 5, 20, 'True', NULL),
(161, 5, 19, 'A', NULL),
(162, 5, 20, 'True', NULL),
(163, 5, 19, 'A', NULL),
(164, 5, 20, 'True', NULL),
(165, 5, 21, 'True', NULL),
(166, 5, 22, 'dassd', NULL),
(167, 5, 23, 'dsadsa', NULL),
(168, 5, 19, 'A', NULL),
(169, 5, 20, 'True', NULL),
(170, 5, 21, 'True', NULL),
(171, 5, 22, 'dassd', NULL),
(172, 5, 23, 'dsadsa', NULL),
(173, 5, 19, 'A', NULL),
(174, 5, 20, 'True', NULL),
(175, 5, 21, 'False', NULL),
(176, 5, 22, 'sadsa', NULL),
(177, 5, 23, 'sdsa', NULL),
(178, 5, 19, 'A', NULL),
(179, 5, 20, 'True', NULL),
(180, 5, 21, 'False', NULL),
(181, 5, 22, 'sds', NULL),
(182, 5, 23, 'sds', NULL),
(183, 5, 19, 'A', NULL),
(184, 5, 20, 'True', NULL),
(185, 5, 21, 'True', NULL),
(186, 5, 22, 'sds', NULL),
(187, 5, 23, 'sds', NULL),
(188, 5, 19, 'A', NULL),
(189, 5, 20, 'True', NULL),
(190, 5, 21, 'False', NULL),
(191, 5, 22, 'sds', NULL),
(192, 5, 23, 'dsd', NULL),
(193, 5, 19, 'A', NULL),
(194, 5, 20, 'True', NULL),
(195, 5, 21, 'False', NULL),
(196, 5, 22, 'sds', NULL),
(197, 5, 23, 'dsd', NULL),
(198, 5, 19, 'A', NULL),
(199, 5, 20, 'True', NULL),
(200, 5, 21, 'True', NULL),
(201, 5, 22, 'sds', NULL),
(202, 5, 23, 'dsd', NULL),
(203, 5, 19, 'A', NULL),
(204, 5, 20, 'True', NULL),
(205, 5, 21, 'True', NULL),
(206, 5, 22, 'sdsa', NULL),
(207, 5, 23, 'sadsa', NULL),
(208, 5, 19, 'A', NULL),
(209, 5, 20, 'True', NULL),
(210, 5, 21, 'True', NULL),
(211, 5, 22, 'sda', NULL),
(212, 5, 23, 'sadsa', NULL),
(213, 5, 19, 'A', NULL),
(214, 5, 20, 'True', NULL),
(215, 5, 21, 'True', NULL),
(216, 5, 22, 'sda', NULL),
(217, 5, 23, 'sadsa', NULL),
(218, 5, 19, 'A', NULL),
(219, 5, 20, 'False', NULL),
(220, 5, 21, 'True', NULL),
(221, 5, 22, 'ddf', NULL),
(222, 5, 23, 'sdfsd', NULL),
(223, 5, 19, 'A', NULL),
(224, 5, 20, 'False', NULL),
(225, 5, 21, 'True', NULL),
(226, 5, 22, 'ddf', NULL),
(227, 5, 23, 'sdfsd', NULL),
(228, 5, 19, 'A', NULL),
(229, 5, 20, 'False', NULL),
(230, 5, 21, 'True', NULL),
(231, 5, 22, 'ddf', NULL),
(232, 5, 23, 'sdfsd', NULL),
(233, 5, 19, 'A', NULL),
(234, 5, 20, 'False', NULL),
(235, 5, 21, 'True', NULL),
(236, 5, 22, 'ddf', NULL),
(237, 5, 23, 'sdfsd', NULL),
(238, 5, 19, 'A', NULL),
(239, 5, 20, 'False', NULL),
(240, 5, 21, 'True', NULL),
(241, 5, 22, 'ddf', NULL),
(242, 5, 23, 'sdfsd', NULL),
(243, 5, 19, 'A', NULL),
(244, 5, 20, 'True', NULL),
(245, 5, 21, 'True', NULL),
(246, 5, 22, 'sdas', NULL),
(247, 5, 23, 'sadsa', NULL),
(248, 5, 19, 'A', NULL),
(249, 5, 20, 'True', NULL),
(250, 5, 21, 'True', NULL),
(251, 5, 22, 'sdas', NULL),
(252, 5, 23, 'sadsa', NULL),
(253, 5, 19, 'A', NULL),
(254, 5, 20, 'True', NULL),
(255, 5, 21, 'True', NULL),
(256, 5, 22, 'sdas', NULL),
(257, 5, 23, 'sadsa', NULL),
(258, 5, 19, 'A', NULL),
(259, 5, 20, 'True', NULL),
(260, 5, 21, 'True', NULL),
(261, 5, 22, 'sdas', NULL),
(262, 5, 23, 'sadsa', NULL),
(263, 5, 29, 'True', NULL),
(264, 5, 30, 'True', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exam_answers`
--
ALTER TABLE `exam_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exam_answers`
--
ALTER TABLE `exam_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exam_answers`
--
ALTER TABLE `exam_answers`
  ADD CONSTRAINT `exam_answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `exam_questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
