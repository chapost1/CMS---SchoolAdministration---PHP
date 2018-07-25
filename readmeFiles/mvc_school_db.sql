-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2018 at 09:02 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvc_school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `administators`
--

CREATE TABLE `administators` (
  `administator_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `role` varchar(45) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` longtext,
  `image` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `administators`
--

INSERT INTO `administators` (`administator_id`, `name`, `phone`, `role`, `email`, `password`, `image`) VALUES
(5, 'Jerry Arechavala', '0507206050', 'Sales', 'jerry@tempmail.com', 'bdb49750f866234f98c1dc0585732a975f0e6577', 'uploads/admins/5.jpg'),
(6, 'Violya Thief', '0527904496', 'Sales', 'hotel@gmail.com', 'bdb49750f866234f98c1dc0585732a975f0e6577', 'uploads/admins/6.jpg'),
(11, 'Shahar Tal', '0526982308', 'Owner', 'stal@mvc.com', 'bdb49750f866234f98c1dc0585732a975f0e6577', 'uploads/admins/11.jpg'),
(12, 'Mark Adams', '0567205020', 'Manager', 'manager@adams.com', 'bdb49750f866234f98c1dc0585732a975f0e6577', 'uploads/admins/12.jpg'),
(13, 'Justin River', '0503309090', 'Manager', 'not@justin.maybe', 'bdb49750f866234f98c1dc0585732a975f0e6577', 'uploads/admins/13.jpg'),
(14, 'Stephen Forte', '0547908080', 'Sales', 'born@to.sell', 'bdb49750f866234f98c1dc0585732a975f0e6577', 'uploads/admins/14.jpg'),
(15, 'Christy Duty', '0506072021', 'Manager', 'christy@mvc.proffesional', 'bdb49750f866234f98c1dc0585732a975f0e6577', 'uploads/admins/15.jpg'),
(16, 'Seller Master^555^', '0502020606', 'Sales', 'baby@can.sell', 'bdb49750f866234f98c1dc0585732a975f0e6577', 'uploads/admins/16.jpg'),
(17, 'default image', '9999999999', 'Sales', 'default@admin.com', 'fab1c0dcbef96d3f1292cbee17baeb10d6a2d374', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` longtext,
  `image` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `name`, `description`, `image`) VALUES
(7, 'PHP', 'come learn how to use israeli programming language.', 'uploads/courses/7.jpg'),
(11, 'S.O.S', 'Default Image', NULL),
(12, 'zero', 'leave me alone', 'uploads/courses/12.jpg'),
(19, 'Quick Quick', 'alot of people just feel lack of time. is that so?', 'uploads/courses/19.jpg'),
(20, 'GQ', 'The breakfest is the most important meal of the day,\r\nafter your night festing, not a coincidence that it&#39;s called BREAKfest.\r\nThe breakfest is the most important meal of the day,\r\nafter your night festing, not a coincidence that it&#39;s called BREAKfest.', 'uploads/courses/20.jpg'),
(21, 'Js', 'come learn javascript\r\nlorem lorem lorem', 'uploads/courses/21.jpg'),
(22, 'Money', 'come learn how to save your money and make more of it.', 'uploads/courses/22.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `deals`
--

CREATE TABLE `deals` (
  `deal_id` int(11) NOT NULL,
  `course_id` longtext,
  `student_id` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`deal_id`, `course_id`, `student_id`) VALUES
(39, '19', '246'),
(40, '22', '246'),
(67, '7', '243'),
(68, '11', '243'),
(69, '19', '243'),
(79, '11', '248'),
(80, '21', '248'),
(81, '22', '248'),
(87, '7', '245'),
(88, '11', '245'),
(89, '19', '245'),
(91, '7', '244'),
(92, '7', '249'),
(93, '11', '249'),
(94, '19', '249'),
(95, '21', '249'),
(96, '22', '249'),
(116, '11', '251'),
(117, '7', '253'),
(118, '11', '253'),
(119, '20', '253'),
(120, '19', '252'),
(121, '22', '252'),
(122, '7', '250'),
(123, '11', '250'),
(124, '20', '250'),
(125, '21', '250'),
(126, '22', '250'),
(134, '7', '240'),
(135, '11', '240'),
(136, '19', '240'),
(137, '21', '240'),
(138, '22', '240');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `phone`, `email`, `image`) VALUES
(240, 'Jose Barkhouse', '0582304070', 'jose@learnAlot.com', 'uploads/students/240.jpg'),
(243, 'Ricky Scott', '0502321410', 'ricky@good.com', 'uploads/students/243.jpg'),
(244, 'David Stegena', '0528203050', 'david@student.com', 'uploads/students/244.jpg'),
(245, 'Masha Parievsky', '0528905981', 'gogo@hatul.com', 'uploads/students/245.jpg'),
(246, 'jerome williams', '0539999992', 'jerome@gever.com', 'uploads/students/246.jpg'),
(248, 'Maya Ailips', '0509901517', 'maya@callme.please', 'uploads/students/248.jpg'),
(249, 'Ariel Witten', '0522020320', 'ariel@toto.won', 'uploads/students/249.jpg'),
(250, 'Maria Leonard', '0527804239', 'maria@nosense.com', 'uploads/students/250.jpg'),
(251, 'Lisa Gordon', '0597017230', 'hi@name.what', 'uploads/students/251.jpg'),
(252, 'Moshiko Poshik', '0509909090', 'kombina@url.com', 'uploads/students/252.jpg'),
(253, 'Dan Ward', '0572465695', 'dan@ear.piercing', 'uploads/students/253.jpg'),
(254, 'default image', '9999999991', 'default@student.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administators`
--
ALTER TABLE `administators`
  ADD PRIMARY KEY (`administator_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`deal_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administators`
--
ALTER TABLE `administators`
  MODIFY `administator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `deals`
--
ALTER TABLE `deals`
  MODIFY `deal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
