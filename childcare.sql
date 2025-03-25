-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 15, 2025 at 12:50 PM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u865964754_childcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_img`
--

CREATE TABLE `activity_img` (
  `activity_imgid` int(10) NOT NULL,
  `activity_imgsrc` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_img`
--

INSERT INTO `activity_img` (`activity_imgid`, `activity_imgsrc`) VALUES
(62, '6776b876d490e-Screenshot (5).png'),
(62, '6776b87c58764-Screenshot (5).png'),
(62, '6776b8808d84f-Screenshot (5).png'),
(62, '6776b88522fdf-Screenshot (5).png'),
(63, '6780874dd5d1c-ART_9239.jpg'),
(63, '6780875173309-images.jpg'),
(63, '67808757bc0ae-images (1)sda.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `activity_report`
--

CREATE TABLE `activity_report` (
  `activity_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `date` varchar(10) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_report`
--

INSERT INTO `activity_report` (`activity_id`, `teacher_id`, `date`, `title`, `description`) VALUES
(63, 1, '2025-01', 'Drawing', 'The children enjoyed drawing while learning. They had fun expressing their creativity through pictures. This activity helped them develop important skills, such as hand-eye coordination and the ability to share their ideas visually.');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `appointment_date` varchar(20) DEFAULT NULL,
  `ref` varchar(50) DEFAULT NULL,
  `child_id` int(11) DEFAULT NULL,
  `session_time` varchar(30) NOT NULL,
  `seen` varchar(5) DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `user_id`, `session_id`, `appointment_date`, `ref`, `child_id`, `session_time`, `seen`) VALUES
(29, 238, 1, 'Jan 10, 2025', '013627 ', 268, 'Afternoon - (1:00 - 4:30 PM)', 'no'),
(30, 238, 1, 'Jan 10, 2025', '014409 ', 269, 'Afternoon - (1:00 - 4:30 PM)', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_record`
--

CREATE TABLE `attendance_record` (
  `attendance_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `shift` varchar(10) NOT NULL,
  `child_id` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  `time_leave` time NOT NULL,
  `seen` varchar(10) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_record`
--

INSERT INTO `attendance_record` (`attendance_id`, `teacher_id`, `date`, `shift`, `child_id`, `status`, `time_leave`, `seen`) VALUES
(51, 1, '2025-01-10', 'Morning', 268, 'present', '00:00:00', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `child_record`
--

CREATE TABLE `child_record` (
  `child_id` int(11) NOT NULL,
  `child_name` varchar(255) DEFAULT NULL,
  `child_age` varchar(15) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `place_of_birth` varchar(100) NOT NULL,
  `allergies` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `child_record`
--

INSERT INTO `child_record` (`child_id`, `child_name`, `child_age`, `gender`, `date_of_birth`, `address`, `place_of_birth`, `allergies`, `user_id`) VALUES
(267, 'Hazara L. Golosinda', '3 years old', 'Female', '2021-01-24', 'Macabalan, Cagayan de Oro City', 'Cagayan de Oro City', 'N/A', 237),
(268, 'Ronnel\\\'', '3 years old', 'Female', '2021-02-12', 'Macabalan', '09277976344', '09277976344', 238),
(269, 'Ronnela', '4 years old', 'Female', '2020-02-10', 'macabalan', 'cdo', 'n/a', 238);

-- --------------------------------------------------------

--
-- Table structure for table `contact_form`
--

CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` varchar(255) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `seen` varchar(10) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_form`
--

INSERT INTO `contact_form` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `phone`, `seen`) VALUES
(13, 'Bryce Jansen Oliver Nudalo', 'nudalo.brycejansenoliver2003@gmail.com', 'FEEDBACK', 'The system is effective, and the design is well-suited to the activities you are engaging in.', 'Jan 10, 2025 at 10:31:31 AM', '9876543451', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE `enrollment` (
  `enrollment_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `enrollment_status` varchar(50) DEFAULT NULL,
  `enrollment_date` varchar(100) DEFAULT NULL,
  `child_name` varchar(255) DEFAULT NULL,
  `child_id` int(11) DEFAULT NULL,
  `ref` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`enrollment_id`, `user_id`, `enrollment_status`, `enrollment_date`, `child_name`, `child_id`, `ref`, `remarks`) VALUES
(210, 237, 'accepted', 'Jan 10, 2025 at 10:12:10 AM', 'Hazara L. Golosinda', 267, 'USTP1103118CMC', ''),
(211, 238, 'accepted', 'Jan 10, 2025 at 01:28:17 PM', 'Ronnel\\\'', 268, 'USTP1102704CMC', ''),
(212, 238, 'accepted', 'Jan 10, 2025 at 01:40:49 PM', 'Ronnela', 269, 'USTP1103448CMC', '');

-- --------------------------------------------------------

--
-- Table structure for table `incident_report`
--

CREATE TABLE `incident_report` (
  `incident_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `location` varchar(50) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `child_id` int(11) DEFAULT NULL,
  `seen` varchar(10) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_report`
--

INSERT INTO `incident_report` (`incident_id`, `teacher_id`, `location`, `date`, `time`, `type`, `description`, `child_id`, `seen`) VALUES
(20, 1, 'Childmind Center', '2025-01-10', '13:33:00', 'Anecdotal Report', 'Hazara accidentally bumped into an object and sustained a small wound. The impact caused a minor injury, but it was not serious. Hazara was quickly attended to and the wound was cleaned and bandaged.', 267, 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `datesent` varchar(50) DEFAULT NULL,
  `status` text DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `from` varchar(10) NOT NULL,
  `parentseen` varchar(5) NOT NULL DEFAULT 'no',
  `teacherseen` varchar(5) NOT NULL DEFAULT 'no',
  `seen` varchar(10) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `user_id`, `teacher_id`, `message`, `datesent`, `status`, `type`, `from`, `parentseen`, `teacherseen`, `seen`) VALUES
(310, 237, NULL, 'We are pleased to inform you that your application for enrollment at the USTP Child Minding Center, with reference number USTP1103118CMC, is currently being reviewed. You will be notified once the processing is complete.', '2025-01-10', 'warning', 'notif', 'teacher', 'no', 'no', 'yes'),
(311, 237, NULL, 'We are pleased to inform you that your enrollment at the USTP Child Minding Center with reference number USTP1103118CMC has been successfully processed.', '2025-01-10', 'success', 'notif', 'teacher', 'no', 'no', 'yes'),
(312, 238, NULL, 'We are pleased to inform you that your application for enrollment at the USTP Child Minding Center, with reference number USTP1102704CMC, is currently being reviewed. You will be notified once the processing is complete.', '2025-01-10', 'warning', 'notif', 'teacher', 'no', 'no', 'yes'),
(313, 238, NULL, 'We are pleased to inform you that your enrollment at the USTP Child Minding Center with reference number USTP1102704CMC has been successfully processed.', '2025-01-10', 'success', 'notif', 'teacher', 'no', 'no', 'yes'),
(314, 238, NULL, 'We are pleased to inform you that your application for enrollment at the USTP Child Minding Center, with reference number USTP1103448CMC, is currently being reviewed. You will be notified once the processing is complete.', '2025-01-10', 'warning', 'notif', 'teacher', 'no', 'no', 'yes'),
(315, 238, NULL, 'We are pleased to inform you that your enrollment at the USTP Child Minding Center with reference number USTP1103448CMC has been successfully processed.', '2025-01-10', 'success', 'notif', 'teacher', 'no', 'no', 'yes'),
(316, 238, NULL, 'We are pleased to inform you that your enrollment at the USTP Child Minding Center with reference number USTP1103448CMC has been successfully processed.', '2025-01-10', 'success', 'notif', 'teacher', 'no', 'no', 'yes'),
(317, 237, 1, 'nangihi lang sya', '2025-01-10', 'danger', 'notif', 'teacher', 'no', 'no', 'yes'),
(318, 238, 1, 'nangihi lang sya', '2025-01-10', 'danger', 'notif', 'teacher', 'no', 'no', 'yes'),
(319, 238, 1, 'hiiii', 'Jan 10, 2025 at 01:47:14 PM', NULL, 'msg', 'parent', 'yes', 'yes', 'yes'),
(320, 238, 1, 'hello', 'Jan 10, 2025 at 01:47:25 PM', NULL, 'msg', 'teacher', 'yes', 'yes', 'yes'),
(321, 238, 1, 'hirgtt', 'Jan 10, 2025 at 01:49:46 PM', NULL, 'msg', 'parent', 'yes', 'yes', 'yes'),
(322, 238, 1, 'hekjfhkajef', 'Jan 10, 2025 at 01:50:03 PM', NULL, 'msg', 'teacher', 'yes', 'yes', 'yes'),
(323, 238, 1, 'hi', 'Jan 10, 2025 at 01:51:30 PM', NULL, 'msg', 'parent', 'yes', 'yes', 'yes'),
(324, 238, 1, 'hi', 'Jan 10, 2025 at 01:52:06 PM', NULL, 'msg', 'parent', 'yes', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `parental_information`
--

CREATE TABLE `parental_information` (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `home_address` varchar(255) DEFAULT NULL,
  `home_phone` varchar(50) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_home_address` varchar(255) DEFAULT NULL,
  `father_employment` varchar(255) DEFAULT NULL,
  `father_work_phone` varchar(50) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_home_address` varchar(255) DEFAULT NULL,
  `mother_employment` varchar(255) DEFAULT NULL,
  `mother_work_phone` varchar(50) DEFAULT NULL,
  `child_living_arrangements` text DEFAULT NULL,
  `child_legal_guardians` text DEFAULT NULL,
  `released_name1` varchar(100) NOT NULL,
  `released_address1` varchar(100) NOT NULL,
  `released_number1` varchar(100) NOT NULL,
  `released_relationtochild1` varchar(100) NOT NULL,
  `released_relationtoparent1` varchar(100) NOT NULL,
  `released_status` varchar(100) NOT NULL,
  `released_name2` varchar(100) NOT NULL,
  `released_address2` varchar(100) NOT NULL,
  `released_number2` varchar(100) NOT NULL,
  `released_relationtochild2` varchar(100) NOT NULL,
  `released_relationtoparent2` varchar(100) NOT NULL,
  `released_other` varchar(100) NOT NULL,
  `emergencyname_1` varchar(100) NOT NULL,
  `emergencyname_2` varchar(100) NOT NULL,
  `emergencyname_3` varchar(100) NOT NULL,
  `emergencynum_1` varchar(100) NOT NULL,
  `emergencynum_2` varchar(100) NOT NULL,
  `emergencynum_3` varchar(100) NOT NULL,
  `emergencyschool` varchar(100) NOT NULL,
  `emergencymid_parent` varchar(255) DEFAULT NULL,
  `emergencymid_parentdate` date DEFAULT NULL,
  `emergencymid_facilityadmin` varchar(255) DEFAULT NULL,
  `emergencymid_facilityadmindate` date DEFAULT NULL,
  `parental_agreement_facility_name` varchar(255) DEFAULT NULL,
  `parental_agreement_child_name` varchar(255) DEFAULT NULL,
  `parental_agreement_days_of_week` varchar(50) DEFAULT NULL,
  `parental_agreement_start_time` varchar(30) DEFAULT NULL,
  `parental_agreement_end_time` varchar(30) DEFAULT NULL,
  `parental_agreement_start_month` varchar(100) DEFAULT NULL,
  `parental_agreement_end_month` varchar(100) DEFAULT NULL,
  `parental_agreement_parent` varchar(255) DEFAULT NULL,
  `parental_agreement_parentdate` date DEFAULT NULL,
  `parental_agreement_facilityadmin` varchar(255) DEFAULT NULL,
  `parental_agreement_facilityadmindate` date DEFAULT NULL,
  `upload_2x2` varchar(255) DEFAULT NULL,
  `upload_birth` varchar(255) DEFAULT NULL,
  `upload_parentID` varchar(255) DEFAULT NULL,
  `upload_cor` varchar(255) DEFAULT NULL,
  `medical_condition` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parental_information`
--

INSERT INTO `parental_information` (`parent_id`, `child_id`, `email`, `home_address`, `home_phone`, `father_name`, `father_home_address`, `father_employment`, `father_work_phone`, `mother_name`, `mother_home_address`, `mother_employment`, `mother_work_phone`, `child_living_arrangements`, `child_legal_guardians`, `released_name1`, `released_address1`, `released_number1`, `released_relationtochild1`, `released_relationtoparent1`, `released_status`, `released_name2`, `released_address2`, `released_number2`, `released_relationtochild2`, `released_relationtoparent2`, `released_other`, `emergencyname_1`, `emergencyname_2`, `emergencyname_3`, `emergencynum_1`, `emergencynum_2`, `emergencynum_3`, `emergencyschool`, `emergencymid_parent`, `emergencymid_parentdate`, `emergencymid_facilityadmin`, `emergencymid_facilityadmindate`, `parental_agreement_facility_name`, `parental_agreement_child_name`, `parental_agreement_days_of_week`, `parental_agreement_start_time`, `parental_agreement_end_time`, `parental_agreement_start_month`, `parental_agreement_end_month`, `parental_agreement_parent`, `parental_agreement_parentdate`, `parental_agreement_facilityadmin`, `parental_agreement_facilityadmindate`, `upload_2x2`, `upload_birth`, `upload_parentID`, `upload_cor`, `medical_condition`) VALUES
(248, 267, 'hazaragolosinda1620@gmail.com', 'Macabalan, Cagayan de Oro City', '09277976344', 'Herman O. Golosinda', 'Pagatpat, Cagayan de Oro City', 'N/A', 'N/A', 'Mildred L. Ricafort', 'Macabalan, Cagayan de Oro City', 'USTP-CDO', '09956112208', 'Mother', 'Both Parents', 'Mildred L. RIcafort', 'Macabalan, Cagayan de Oro City', '09277976344', 'Parent', 'Parent', 'Job Order (JO)', '', '', '', '', '', '', 'Sonia A. Lauglaug', '', '', '09271404066', '', '', '', 'Mildred L. Ricafort', '2025-01-10', 'Crisanta M. Miro', '2025-01-10', '            Crisanta M. Miro', 'Hazara L. Golosinda', '4', '9:00am - 11:30am', '1:00pm - 4:30pm', '2025-01', '2028-06', 'Mildred L. Ricafort', '2025-01-10', 'Crisanta M. Miro', '2025-01-10', '678082af3cd6a5.84309896.jpg', '678082af3d0eb9.73183881.jpg', '678082af3d25f9.98644789.jpg', '678082af3d3c29.64624450.jpg', 'N/A'),
(249, 268, 'golosindahazara868@gmail.com', 'Macabalan', '09277976344', '09277976344', '09277976344', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'Both Parents', 'Both Parents', 'Mildred', 'Mildred', '09277976344', 'Parent', 'Parent', 'Job Order (JO)', '', '', '', '', '', '', 'lancce dacut', '', '', '0927797633', '', '', '', 'Mildred L. Ricafort', '2025-01-10', 'Crisanta M. Miro', '2025-01-10', '            Crisanta M. Miro', 'Ronnel\'', '4', '9:00am - 11:30am', '1:00pm - 4:30pm', '2025-01', '2027-05', 'Mildred L. Ricafort', '2025-01-10', 'Crisanta M. Miro', '2025-01-10', '6780b0e93ad147.38089279.PNG', '6780b0e93aff83.42397799.PNG', '6780b0e93b1349.58925165.PNG', '6780b0e93b2ab5.36448947.PNG', '09277976344'),
(250, 269, 'golosindahazara868@gmail.com', 'macabalan', '8234638274', '09277976344', '09277976344', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'Both Parents', 'Both Parents', 'Mildred', 'Mildred', '09277976344', 'Parent', 'Parent', 'Job Order (JO)', '', '', '', '', '', '', 'lancce dacut', '', '', '0927797633', '', '', '', 'Mildred L. Ricafort', '2025-01-10', 'Crisanta M. Miro', '2025-01-10', '            Crisanta M. Miro', 'Ronnela', '4', '9:00am - 11:30am', '1:00pm - 4:30pm', '2025-01', '2028-02', 'Mildred L. Ricafort', '2025-01-10', 'Crisanta M. Miro', '2025-01-10', '6780b3da6e2ba6.83149382.PNG', '6780b3da6e4814.68879081.PNG', '6780b3da6e5a41.79030962.PNG', '6780b3da6e6e58.85101789.PNG', 'n/a');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) DEFAULT NULL,
  `role_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_description`) VALUES
(1, 'Teacher', 'Teacher description'),
(2, 'Parent', 'Parent Desc');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `session_id` int(11) NOT NULL,
  `morning_slots` int(11) DEFAULT NULL,
  `afternoon_slots` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`session_id`, `morning_slots`, `afternoon_slots`) VALUES
(1, 10, 8);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `place_of_birth` varchar(100) DEFAULT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `user_id`, `fullname`, `date_of_birth`, `place_of_birth`, `civil_status`, `gender`, `address`, `contact`, `email_address`) VALUES
(1, 1, 'Crisanta M. Miro', '2008-03-14', 'University of Science and Technology of Southern Philippines - CDO Campus', 'single', 'female', 'University of Science and Technology of Southern Philippines - CDO Campus', '1152', 'crisanta.miro@ustp.edu.ph');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` longtext DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `role_id`, `username`, `password`, `email`) VALUES
(1, 1, 'teacheradmin', '$2y$10$AAimgkBvQzYU.STDVmBcIuiLFTBmGrYG3X7zzGDXvSYiq6jdS8zB2', 'crisanta.miro@ustp.edu.ph'),
(237, 2, 'mildred', '$2y$10$h5UZDwD0TyDefsCFcFL.TOtTAqXbw.PY.eSbBpT79wyuP0aGD0MsK', 'hazaragolosinda1620@gmail.com'),
(238, 2, 'USTP1102704CMC', '$2y$10$r/B0i9Pg8ToeaYJXfY1zXe7SJ0BMB9aFt7FqsX6r5tTADZwMqYkCq', 'golosindahazara868@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_report`
--
ALTER TABLE `activity_report`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_appointment_child_id` (`child_id`);

--
-- Indexes for table `attendance_record`
--
ALTER TABLE `attendance_record`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `fk_child_id` (`child_id`);

--
-- Indexes for table `child_record`
--
ALTER TABLE `child_record`
  ADD PRIMARY KEY (`child_id`),
  ADD KEY `child_record_ibfk_1` (`user_id`);

--
-- Indexes for table `contact_form`
--
ALTER TABLE `contact_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `child_id` (`child_id`);

--
-- Indexes for table `incident_report`
--
ALTER TABLE `incident_report`
  ADD PRIMARY KEY (`incident_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `parental_information`
--
ALTER TABLE `parental_information`
  ADD PRIMARY KEY (`parent_id`),
  ADD KEY `child_id` (`child_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_report`
--
ALTER TABLE `activity_report`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `attendance_record`
--
ALTER TABLE `attendance_record`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `child_record`
--
ALTER TABLE `child_record`
  MODIFY `child_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT for table `contact_form`
--
ALTER TABLE `contact_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `enrollment`
--
ALTER TABLE `enrollment`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `incident_report`
--
ALTER TABLE `incident_report`
  MODIFY `incident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT for table `parental_information`
--
ALTER TABLE `parental_information`
  MODIFY `parent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_report`
--
ALTER TABLE `activity_report`
  ADD CONSTRAINT `activity_report_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_appointment_child_id` FOREIGN KEY (`child_id`) REFERENCES `child_record` (`child_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `attendance_record`
--
ALTER TABLE `attendance_record`
  ADD CONSTRAINT `attendance_record_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_child_id` FOREIGN KEY (`child_id`) REFERENCES `child_record` (`child_id`) ON DELETE CASCADE;

--
-- Constraints for table `child_record`
--
ALTER TABLE `child_record`
  ADD CONSTRAINT `child_record_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`child_id`) REFERENCES `child_record` (`child_id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_report`
--
ALTER TABLE `incident_report`
  ADD CONSTRAINT `incident_report_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`) ON DELETE CASCADE;

--
-- Constraints for table `parental_information`
--
ALTER TABLE `parental_information`
  ADD CONSTRAINT `parental_information_ibfk_1` FOREIGN KEY (`child_id`) REFERENCES `child_record` (`child_id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
