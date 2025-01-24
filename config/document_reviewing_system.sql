-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 24, 2025 at 01:23 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `document_reviewing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `commentor_id` int(11) NOT NULL,
  `comment_content` varchar(250) NOT NULL,
  `resolved_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `commentor_id` (`commentor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `uploader_id` int(11) NOT NULL,
  `file_path` varchar(250) NOT NULL,
  `date_uploaded` date NOT NULL,
  `date_approved` date DEFAULT NULL,
  `review_status` set('approved','pending','update','') NOT NULL,
  PRIMARY KEY (`doc_id`),
  KEY `uploader_id` (`uploader_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`doc_id`, `uploader_id`, `file_path`, `date_uploaded`, `date_approved`, `review_status`) VALUES
(8, 1, '../reviewer/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(9, 1, '../reviewer/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(10, 1, '../reviewer/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(11, 1, '../reviewer/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(12, 1, '../reviewer/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(13, 1, '../reviewer/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(14, 1, '../reviewer/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(15, 1, '../../../../config/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(16, 1, '../../../../config/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(17, 1, '../../../../config/pdf/LabActivity4-IT213 - Copy.pdf', '2025-01-23', NULL, 'pending'),
(18, 1, '../../../../config/pdf/LabActivity4-IT213 - Copy.pdf', '2025-01-23', NULL, 'pending'),
(19, 1, '../../../../config/pdf/LabActivity4-IT213.pdf', '2025-01-23', NULL, 'pending'),
(20, 1, '../../../../config/pdf/LabActivity4-IT213 - Copy - Copy.pdf', '2025-01-23', NULL, 'pending'),
(21, 1, '../../../../config/pdf/LabActivity4-IT213 - Copy (2).pdf', '2025-01-23', NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `review_logs`
--

DROP TABLE IF EXISTS `review_logs`;
CREATE TABLE IF NOT EXISTS `review_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `doc_id` (`doc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `account_type` set('Reviewer','Uploader','Admin') NOT NULL,
  `online_status` tinyint(1) NOT NULL DEFAULT '0',
  `forgot_pass` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `account_type`, `online_status`, `forgot_pass`) VALUES
(1, 'admin@slu.edu.ph', '$2y$10$uxHkj9ak7t0GrgSxlLaCOuAhxDFHJ4mCiJ7Dj0.dNpdKTw6//XeNy', 'Navi', 'Gator', 'Admin', 0, 0),
(2, '2227828@slu.edu.ph', '$2y$10$7DCRWeuTaVRtSGsXcmuWR.jZWMnehIh9U/dmK9aaQTk2LbbSVdFqe', 'Justin', 'Montemayor', 'Reviewer', 0, 0),
(3, '2222484@slu.edu.ph', '$2y$10$eN1APMZoUmd2PRhVnWjBq.LHmxBPD0W31lGP/1wopqOUEJEjaGFhW', 'Carl', 'Pascua', 'Uploader', 0, 0),
(4, '2202887@slu.edu.ph', '$2y$10$l8BEd//uS/MIewZovI/UHeUj3AlpbpsBit0wDd2QRejs0grZvmf7q', 'Justine', 'Lucas', 'Reviewer', 0, 0),
(5, '2224981@slu.edu.ph', '$2y$10$htGjXhDfjNUD0kwEuYufUOXw6wYISEzUm8S16jiOGm2j/bjDtvnEy', 'Jermin', 'Odcheo', 'Uploader', 0, 0),
(6, '2223448@slu.edu.ph', '$2y$10$AHFYI4jFwS/OtWwV6cd8muqCD4nIqaqfPKFHzGbQSeVCuFgqk3cAu', 'Dorothy', 'Lopez', 'Uploader', 0, 0),
(7, '2166734@slu.edu.ph', '$2y$10$oEyy91FfZWQzFNtX91S5Xu3PuGTnHzVEPPD/pcnz783m8BLIe0L/y', 'Harvey', 'Chua', 'Uploader', 0, 0),
(8, '2223258@slu.edu.ph', '$2y$10$CQxTApjgtogqvJgimzx6te6BD4QDa7cAtgiQBmQeK/9phGqJuhVg6', 'Mike', 'Mayangao', 'Reviewer', 0, 0),
(9, '2222783@slu.edu.ph', '$2y$10$3MnhbbaxXk4Spjs5U0fEeuJPzrnsOj7eO9XJ.ujhA9OR6Gk0utymO', 'Evan', 'Garcia', 'Uploader', 0, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `commentor_id` FOREIGN KEY (`commentor_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `review_logs`
--
ALTER TABLE `review_logs`
  ADD CONSTRAINT `doc_id` FOREIGN KEY (`doc_id`) REFERENCES `documents` (`doc_id`),
  ADD CONSTRAINT `reviewer_id` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
