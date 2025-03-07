-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 06:25 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookmark`
--

INSERT INTO `bookmark` (`user_id`, `playlist_id`) VALUES
('JSRhOku2xNEJAjx8gKpH', 'DOHc6dJ9cwJnC4npkOPY'),
('JSRhOku2xNEJAjx8gKpH', 'SQRq4r1M2eWtPeTbCUIs'),
('hXbyTUrijIH6xp2rEfji', 'SQRq4r1M2eWtPeTbCUIs');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `strand`, `created_at`, `tutor_id`, `class_name`) VALUES
(19, '', 'HUMMS', '2025-02-28 06:59:25', 6, 'Mine'),
(20, '', 'HUMMS', '2025-02-28 06:59:40', 6, 'Class 1');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content_id`, `user_id`, `tutor_id`, `comment`, `date`) VALUES
('SKaIj4M25caXBUKFpbIL', 'wa4vsOSbg3LgVbaQBHk0', 'JSRhOku2xNEJAjx8gKpH', 'tbF8VMuQPOsI193khB6A', 'solid shet!', '2024-12-01'),
('EHp5OxY57CVwB8JxihlY', 'Fl1Dm8rjEInH52R72Tu2', 'hXbyTUrijIH6xp2rEfji', 'tbF8VMuQPOsI193khB6A', 'ang lupet!', '2024-12-01'),
('jBONW38gH8P0KUjnMBYa', 'wa4vsOSbg3LgVbaQBHk0', 'hXbyTUrijIH6xp2rEfji', 'tbF8VMuQPOsI193khB6A', 'idol!', '2024-12-01'),
('65mMIwrOc7i6z18T5d4k', 'x40irtkiLXivkqlFaCXs', 'JSRhOku2xNEJAjx8gKpH', 'tbF8VMuQPOsI193khB6A', 'sdjksdk', '2024-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `name` varchar(60) NOT NULL,
  `email` varchar(50) NOT NULL,
  `number` int(12) NOT NULL,
  `message` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `video` varchar(10000) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'deactive',
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `tutor_id`, `playlist_id`, `title`, `description`, `video`, `thumb`, `date`, `status`, `file`) VALUES
('wa4vsOSbg3LgVbaQBHk0', 'tbF8VMuQPOsI193khB6A', 'SQRq4r1M2eWtPeTbCUIs', 'wag na', 'sdas', 'M9Ic03PBUIHx3c6Pjq1R.mp4', 'kjifg5VJfsp4mVni837d.jpg', '2024-12-01', 'active', NULL),
('Fl1Dm8rjEInH52R72Tu2', 'tbF8VMuQPOsI193khB6A', 'SQRq4r1M2eWtPeTbCUIs', 'hi guys', 'its 1 am in the morning', 'HZKcW4nBrNsAsod1cf83.mp4', 'S4JuJG8YpO5HQRQZhXDn.jpg', '2024-12-01', 'active', NULL),
('HRwuQjh3a7EVyPRnD0nb', 'x3Iv0FN6UwMHMfN97MgQ', 'DOHc6dJ9cwJnC4npkOPY', 'Hello Guys', 'making 3am vlog', 'wgrx4hVKuY7Oang1BWNr.mp4', 'SrOKmI4WBr963OvmwQPa.png', '2024-12-01', 'active', NULL),
('x40irtkiLXivkqlFaCXs', 'tbF8VMuQPOsI193khB6A', 'KupID4O5wGevRC4nRqUl', 'sdas', 'dddd', 's0xTkJeDGumzH8DIq0S6.mp4', 'FgNWL9g0oImneMOv3qs6.jpg', '2024-12-01', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `user_id` varchar(20) NOT NULL,
  `tutor_id` varchar(20) NOT NULL,
  `content_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`user_id`, `tutor_id`, `content_id`) VALUES
('JSRhOku2xNEJAjx8gKpH', 'tbF8VMuQPOsI193khB6A', 'wa4vsOSbg3LgVbaQBHk0'),
('hXbyTUrijIH6xp2rEfji', 'tbF8VMuQPOsI193khB6A', 'wa4vsOSbg3LgVbaQBHk0'),
('JSRhOku2xNEJAjx8gKpH', 'tbF8VMuQPOsI193khB6A', 'x40irtkiLXivkqlFaCXs'),
('43JXfVsAM5cMkEIamv8I', 'x3Iv0FN6UwMHMfN97MgQ', 'HRwuQjh3a7EVyPRnD0nb');

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
  `strand` varchar(255) NOT NULL,
  `class` varchar(50) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`id`, `tutor_id`, `title`, `description`, `thumb`, `date`, `status`, `strand`, `class`, `class_id`) VALUES
('DOHc6dJ9cwJnC4npkOPY', 'x3Iv0FN6UwMHMfN97MgQ', 'justine', 'asdasd', '7GSsBuuxbpWu9zhB5ZKX.png', '2024-11-16', 'active', '', '', 0),
('SQRq4r1M2eWtPeTbCUIs', 'tbF8VMuQPOsI193khB6A', 'Activity 1', 'asksks', 'cUOCzH6ZrCRKzzGsy3f5.jpg', '2024-11-30', 'active', '', '', 0),
('KupID4O5wGevRC4nRqUl', 'tbF8VMuQPOsI193khB6A', 'PHILOSOPHY', 'ACTIVITY', 'iAFRdIufHz6HEfDWKmmu.docx', '2024-12-01', 'active', '', '', 0),
('U8GogTKHTJGxzBDAKWWB', '6lV8r7nZiOUhMfOyJntS', 'Math', 'hi everyone', 'OoLFU3CYtegh2kvMCcph.png', '2025-02-06', 'active', '', '', 0),
('fXtp3FFDOU34v5LcC6aB', '6lV8r7nZiOUhMfOyJntS', 'Myk', '1234', 'Jhqsctxr09xAYQ2LuJEw.png', '2025-02-11', 'active', 'ICT', 'class1', 0),
('EbtxbHrOo0cdtLlbQtxP', '6lV8r7nZiOUhMfOyJntS', 'Help', 'hi', 'iy4muYTJbnGGKifttiZo.png', '2025-02-28', 'active', 'HUMMS', '', 20);

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `profession` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `strand` varchar(50) NOT NULL,
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
