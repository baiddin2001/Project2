-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 11:06 AM
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
-- Database: `course_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmark`
--

CREATE TABLE `bookmark` (
  `user_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `strand` enum('HUMMS','ICT') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `tutor_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `strand`, `created_at`, `tutor_id`, `class_name`) VALUES
(4, '', 'ICT', '2025-03-07 09:09:30', 0, 'Class 1'),
(5, '', 'HUMMS', '2025-03-07 09:21:02', 0, 'Class 1'),
(6, '', 'HUMMS', '2025-03-07 09:21:06', 0, 'Class 2'),
(7, '', 'HUMMS', '2025-03-07 09:21:13', 0, 'Class 3'),
(8, '', 'ICT', '2025-03-07 09:21:25', 0, 'Class 2-B');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` int(10) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `playlist_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `video` varchar(100) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'deactive',
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `tutor_id`, `playlist_id`, `title`, `description`, `video`, `thumb`, `file`, `date`, `status`, `class_id`) VALUES
('leDmu0gtJKtxZEwBWAXY', 'Rzxn8MXYOjQQfHXuaPdM', 'upZYihdmDJh3d324JsAg', 'Lesson 1', 'A brief history of art movements', '6Cr2PFqfQbXWvGMUanYu.mp4', 'nuBIY0mcCXKuaPzigss5.jpg', 'none', '2025-03-07', 'active', NULL),
('98tgNiD8YuGJp4cMFnk7', 'Rzxn8MXYOjQQfHXuaPdM', 'lShpTiqz0YfvhYxltWfz', 'Social Sciences Lesson 1', 'Introduction To Social Science', 'O6UIzDU4CBesSpq4kcjU.mp4', 'SRdjsBlDFy8Dd3HFfeU6.jpg', 'lJSP1ycP8PdJeYCQtxgI.pdf', '2025-03-07', 'active', NULL),
('C1zuoMnMku8AGs74GTdG', 'Rzxn8MXYOjQQfHXuaPdM', 'YP9pKrl5zybD1tuah3oJ', 'Social Sciences Lesson 1', ' INTRODUCTION TO SOCIAL\r\n SCIENCE', '84475RQr1b17tTkN72H8.mp4', 'rhpDILuE6uPZYCzFFcH2.jpg', 'sTKBdjYLBvPOZ8Gph20D.pdf', '2025-03-07', 'active', NULL),
('CpHzvRlYIFBsCEsnKZJE', 'Rzxn8MXYOjQQfHXuaPdM', '2hT5QpnDGMcKZdc9Lfr9', 'Basics of Computer – Skill of Introduction', 'An overview of the parts of computers and how they work together. Describes the fundamentals of computers and how they work on both a small and large scale. Explores the history of computing and programming and surveys how modern programming evolved from the early days.', 'VUiwgxv4b4VoNIDgpBQF.mp4', 'nhvF7cG8UfLAoqM2JrdG.jpg', 'FCkag2OSL29nsEyaB6Hh.pdf', '2025-03-07', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'deactive',
  `strand` varchar(10) NOT NULL,
  `class` varchar(255) DEFAULT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`id`, `tutor_id`, `title`, `description`, `thumb`, `date`, `status`, `strand`, `class`, `class_id`) VALUES
('2hT5QpnDGMcKZdc9Lfr9', 'Rzxn8MXYOjQQfHXuaPdM', 'Computer Science', 'Computer science is the study of computation, information, and automation. Computer science spans theoretical disciplines (such as algorithms, theory of computation, and information theory) to applied disciplines (including the design and implementation of hardware and software).', 'eMKjumsg0yPBgRbYn4J5.jpg', '2025-03-07', 'active', 'ICT', NULL, 4),
('7nXQyNTRORA7Ah23tar5', 'Rzxn8MXYOjQQfHXuaPdM', 'Oral and Written Communication class 1', 'Oral communication skills encompass the ability to express thoughts, opinions, and information through spoken words, while written communication skills involve conveying messages, instructions, or thoughts using written language.', 'Oy8KvwX0961OR47KxP6s.jpg', '2025-03-07', 'active', 'HUMMS', NULL, 5),
('pt0O23A6QNo7ymil6ndZ', 'Rzxn8MXYOjQQfHXuaPdM', 'Oral and Written Communication class 2', 'Oral communication skills encompass the ability to express thoughts, opinions, and information through spoken words, while written communication skills involve conveying messages, instructions, or thoughts using written language.', 'FmbXJgVZ8GKTzbdJfURn.jpg', '2025-03-07', 'active', 'HUMMS', NULL, 6),
('upZYihdmDJh3d324JsAg', 'Rzxn8MXYOjQQfHXuaPdM', 'Art & Art History', 'Art History is a branch of the Humanities that deals with the study of objects such as paintings, sculptures, prints, drawings, photographs, architecture, textiles, film, performance and installation art and how they reflect aesthetics, socio-political and economic ideals and contexts across time.', 'Ni8rjmDWAJvG0heQ2myB.jpg', '2025-03-07', 'active', 'HUMMS', NULL, 5),
('iLLwdqV06LSPw5Rt0Rwt', 'Rzxn8MXYOjQQfHXuaPdM', 'Mathematics', 'Mathematics, the science of structure, order, and relation that has evolved from counting, measuring, and describing the shapes of objects. Mathematics has been an indispensable adjunct to the physical sciences and technology and has assumed a similar role in the life sciences.', 'DKIEFvXXY9pa8pfyQWR4.jpg', '2025-03-07', 'active', 'HUMMS', NULL, 5),
('lShpTiqz0YfvhYxltWfz', 'Rzxn8MXYOjQQfHXuaPdM', 'Social Sciences', 'Social science involves academic disciplines that focus on how individuals behave within society.', 'htWpTFdS3PCKrxNiFCPO.jpg', '2025-03-07', 'active', 'HUMMS', NULL, 7),
('YP9pKrl5zybD1tuah3oJ', 'Rzxn8MXYOjQQfHXuaPdM', 'Social Sciences Class 1', 'Social science involves academic disciplines that focus on how individuals behave within society.', 'w46jYOvmBzgstgDPhUcU.jpg', '2025-03-07', 'active', 'HUMMS', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `profession` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`id`, `name`, `profession`, `email`, `password`, `image`) VALUES
('Rzxn8MXYOjQQfHXuaPdM', 'Tony Stark', 'Teacher', 'tony@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'U0d2Tnzci10TMRO5MTj3.webp'),
('z6R4AQ1wbpZkMsXzkTRx', 'Bruce Banner', 'Teacher', 'bruce@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'UuxvsORIL3cpUiaT7YIL.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  `class_section` varchar(50) NOT NULL,
  `strand` enum('HUMMS','ICT') NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`, `class_section`, `strand`, `class_id`) VALUES
('OYS6O1iLi7yvyWsAwwv8', 'Bench', 'bench@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '37lBN9eUHtYgxeDSN75g.jpg', '', 'ICT', 4),
('JPraPZI5Nnp5y1Zv26wj', 'Peter', 'peter@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '8WJFw8D0TWzPK4jOvt98.jpg', '', 'ICT', 8),
('vWqJcNpbl4Z6wQ2uVUJ5', 'Steven', 'steven@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'taBYKOvaZTS4GcnSIHKI.jpg', '', 'HUMMS', 5),
('GSKM19h5JLM2uRdozaJM', 'bob', 'bob@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'CXBu7tGiKroQuCRikXLX.jpg', '', 'ICT', 4),
('iE63WTLzwCgA9MWpqPxX', 'anna', 'anna@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'evZgOoT6hW038To2Y7pG.jpg', '', 'ICT', 4),
('C13uej5FP2ZUvMRsne4P', 'Jin', 'jin@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'p0BXr45XMMlgxmXRGwh6.jpg', '', 'HUMMS', 6),
('ijMBGkTkm3O80UXoOX9y', 'ken', 'ken@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'msAyQxgEOZFAkaQoWdmH.png', '', 'HUMMS', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD KEY `class_id` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
