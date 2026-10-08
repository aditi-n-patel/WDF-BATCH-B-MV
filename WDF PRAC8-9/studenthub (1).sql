-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2026 at 02:28 PM
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
  `venue` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `category`, `event_date`, `venue`) VALUES
(1, 'Tech Fest 2026', 'Technical events and competitions', 'Technical', '2026-10-20', 'CHARUSAT CAMPUS');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `student_id`, `event_id`, `registration_date`) VALUES
(1, 1, 1, '2026-10-06 18:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `institute` varchar(150) NOT NULL,
  `year` int(11) NOT NULL,
  `course` varchar(100) NOT NULL,
  `roll_no` int(30) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `mobile`, `institute`, `year`, `course`, `roll_no`, `username`, `password`, `role`) VALUES
(1, 'Aditi patel', 'aditi@gmail.com', '9476543201', 'CHARUSAT', 2, 'B.Tech IT', 46, '', '', 'student'),
(2, 'ADITI PATEL', '25dit046@charusat.edu.in', '9123456708', 'DEPSTAR', 2, 'IT', 46, '25DIT046', '$2y$10$3OMHCo1tbTif3jzy3brK4ey2mzweJsz3KoWc2dxAg1WbHk/SjCpL6', 'student'),
(3, 'RAHUL PATEL', '25dit047@charusat.edu.in', '9429594494', 'DEPSTAR', 2, 'IT', 47, '25DIT047', '$2y$10$T1H1RRsuSi7EIT7W1EMU1.K5zuNPGYS5xSwURq6Lpa8fgLUNVwaQK', 'student'),
(4, 'RAHUL PATEL', '25dit048@charusat.edu.in', '9429594495', 'DEPSTAR', 2, 'IT', 48, '25DIT048', '$2y$10$Hcpm7Yj5r6K8HjRxgPMPtuxqF6T9sgxssyhipisQ2mxDajlIjpX9u', 'student'),
(5, 'RIYA PATEL', '25dit049@charusat.edu.in', '1234567894', 'DEPSTAR', 2, 'IT', 48, '25DIT049', '$2y$10$kfDbUbflFtHeL3mlzIOmueQ9xxWPVQ0NBMhrMjj5dl7E6Ctd9mmsm', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student` (`student_id`),
  ADD KEY `fk_event` (`event_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `fk_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
