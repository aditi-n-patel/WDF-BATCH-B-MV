-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2026 at 03:13 PM
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
-- Database: `studenthub`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `venue` varchar(150) NOT NULL,
  `poster` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `category`, `event_date`, `venue`, `poster`) VALUES
(1, 'Tech Fest 2026', 'Technical events and competitions', 'Technical', '2026-10-20', 'CHARUSAT CAMPUS', NULL),
(3, 'Web Development Workshop', 'Hands-on HTML, CSS, JavaScript and PHP workshop.', 'Technical', '2026-10-20', 'CSPIT SEMINAR HALL', NULL),
(5, 'POST NAVARATRI', 'TIME: 8 30 T0 12 30', 'Cultural', '2026-10-23', 'CHARUSAT Campus', ''),
(6, 'Codethon 2026', 'A team coding competition to solve programming challenges.', 'Technical', '2026-10-30', 'DEPSTAR SEMINAR HALL-329', ''),
(7, 'Tech innovation Expo', 'Showcase innovative student projects, robotics and technical models.', 'Technical', '2026-11-02', 'CSPIT', ''),
(8, 'Inter-Department Cricket Tournament', 'Cricket tournament between college departments.', 'Sports', '2026-11-06', 'Sports ground', ''),
(9, 'General Knowledge Quiz', 'An inter-department quiz covering technology, science and general knowledge.', 'Technical', '2026-11-15', 'DEPSTAR SEMINAR HALL-329', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
