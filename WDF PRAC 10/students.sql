-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2026 at 06:41 PM
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
(6, 'Admin', 'admincharusat@studenthub.com', '9192939495', 'CHARUSAT', 1, 'B.ech IT', 25101, 'admin', '$2y$10$CXAae4C4KvTSpDJWlnXOY.vhNgGlDQtcDf3aNLlc7TWlO27cyAiJi', 'admin'),
(7, 'RAHUL PATEL', '25dit001@charusat.edu.in', '1234567891', 'DEPSTAR', 2, 'IT', 1, '25DIT001', '$2y$10$lxVuqLdX6JWVgUKubmSqT.PQWfd7AdbEJ7YXRB1RI6jhjI2NFdbr2', 'student'),
(8, 'RAM PATEL', '25dit002@charusat.edu.in', '1234567892', 'DEPSTAR', 2, 'IT', 2, '25DIT002', '$2y$10$Jvm8.QrAMnquzCTLw1HTluzxTXG3h4bk429mWOrVY2RPX3mHgTzTq', 'student'),
(9, 'BHAVYA PATEL', '25dit003@charusat.edu.in', '1234567893', 'DEPSTAR', 2, 'IT', 3, '25DIT003', '$2y$10$AYXYJAldJDg9bzUjU8srGO0w4SkdPFf8rjCuHrfUKpwH1KyFcllka', 'student'),
(10, 'DIVYA PATEL', '25dit004@charusat.edu.in', '1234567894', 'DEPSTAR', 2, 'IT', 4, '25DIT004', '$2y$10$zzGhTK7KeiwJn0ziwcvOlOMvo/XEnOkivBH902iKjj3wWZ3847Xmy', 'student'),
(11, 'MAN PATEL', '25dit005@charusat.edu.in', '1234567895', 'DEPSTAR', 2, 'IT', 5, '25DIT005', '$2y$10$AihnFJcnhdf1jKu6tskF1uFZPEa2Czsc3lQ.c.qgVMb22OPQ64PwK', 'student'),
(12, 'DHAIRYA PATEL', '25dit006@charusat.edu.in', '1234567896', 'DEPSTAR', 2, 'IT', 6, '25DIT006', '$2y$10$xdhE.6PYcM20yBTqgiFjpOe/w22MWEI2Ive0Wj5R6LCXlViKqsSRW', 'student'),
(13, 'DARSH PATEL', '25dit007@charusat.edu.in', '1234567897', 'DEPSTAR', 2, 'IT', 7, '25DIT007', '$2y$10$s4kUVOOqEpc1.QsdtXzsBO3fDALaf.F5ts03eQu4uzFVUzLm8HegS', 'student'),
(14, 'DAKSH PATEL', '25dit008@charusat.edu.in', '1234567898', 'DEPSTAR', 2, 'IT', 8, '25DIT008', '$2y$10$Tj9iCbzD8TelRiW.e6zZueDrVJiX4Ar1xiew8BGgSIn4Gi2cpBAeG', 'student'),
(15, 'ROHIT PATEL', '25dit009@charusat.edu.in', '1234567899', 'DEPSTAR', 2, 'IT', 9, '25DIT009', '$2y$10$8g1/K451ml6J6UIXOiUHKukP56uBh3Upep7sjqXisiRXoMaQIMvDa', 'student'),
(16, 'MADHAV PATEL', '25dit010@charusat.edu.in', '2345678912', 'DEPSTAR', 2, 'IT', 10, '25DIT010', '$2y$10$n6NrC58gWsHLEoz0ZHzyN..xUnqGWxeLhG3AIcbc0Qn8cuJSFbxB6', 'student'),
(17, 'KRISHNA PATEL', '25dit011@charusat.edu.in', '2345678913', 'DEPSTAR', 2, 'IT', 11, '25DIT011', '$2y$10$b1..gIzMIlgEmPGzmi8/m./8Kyb5izvOKbmoIvsKNFHcEcA5hovpi', 'student'),
(18, 'RIYA PATEL', '25dit012@charusat.edu.in', '2345678914', 'DEPSTAR', 2, 'IT', 12, '25DIT012', '$2y$10$ILT2GH9wsGbvdk/ty.kYpOap/9LxyRnNV52mGPtAejhPRU8Hx2Vt6', 'student'),
(19, 'RITU PATEL', '25dit013@charusat.edu.in', '2345678915', 'DEPSTAR', 2, 'IT', 13, '25DIT013', '$2y$10$kwEZnCh6ttIRI2LpKb2NteNyyCdzs3WmfLOoAzQZvr7W3o.Sko2Yy', 'student'),
(20, 'RINA PATEL', '25dit014@charusat.edu.in', '2345678915', 'DEPSTAR', 2, 'IT', 14, '25DIT014', '$2y$10$oO12vZyBJnX2.SJ2Yp0p7uUxcDjtT0yVf7l9PUCdUxGLBNAExDQga', 'student'),
(21, 'KIRTI PATEL', '25dit015@charusat.edu.in', '2345678916', 'DEPSTAR', 2, 'IT', 15, '25DIT015', '$2y$10$C60Hon0WWsmCs.TYOyMWyOg79XrKOrFNKfx.EHlS9fpbyL1l1t2QC', 'student'),
(22, 'KRUPA PATEL', '25dit016@charusat.edu.in', '2345678917', 'DEPSTAR', 2, 'IT', 16, '25DIT016', '$2y$10$ogNb9x/bVYosBfAO/Gcye.e/qS17QAKgRfcRvLuasX2vQlRGfxigy', 'student'),
(23, 'PANKTI PATEL', '25dit017@charusat.edu.in', '2345678918', 'DEPSTAR', 2, 'IT', 17, '25DIT017', '$2y$10$2HH0NOnHo7BMk52piJeK..ziq5CSG8UtiZFx.rK9pSu84.K/7mYE2', 'student'),
(24, 'ARCHI PATEL', '25dit018@charusat.edu.in', '2345678919', 'DEPSTAR', 2, 'IT', 18, '25DIT018', '$2y$10$ccrAweWqvKkq7iI0HZHIlOroJrrvQs/vsOEg4KITqAXnKkXDo8vnW', 'student'),
(25, 'PARI PATEL', '25dit019@charusat.edu.in', '3456789121', 'DEPSTAR', 2, 'IT', 19, '25DIT019', '$2y$10$Tl/FhIbT7PtIKld4CyrjN.RePrvrk7wQZbLYlH7Z42S1ZUuYKpj12', 'student'),
(26, 'TITHI PATEL', '25dit020@charusat.edu.in', '3456789123', 'DEPSTAR', 2, 'IT', 20, '25DIT020', '$2y$10$5LtHgjY9LW5J6CpjBHV0mO47AD0NZ6scpc4/.WjcLSRNVnke9MIUS', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
