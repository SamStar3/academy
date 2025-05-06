-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 06:24 AM
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
-- Database: `roriri_it_park`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddCourse` (IN `p_name` VARCHAR(100), IN `p_duration` VARCHAR(50), IN `p_fee` DECIMAL(10,2), IN `p_effective_from` DATE)   BEGIN
    DECLARE new_course_id INT;

    INSERT INTO course (name, duration, status) 
    VALUES (p_name, p_duration, 'Active');

    SET new_course_id = LAST_INSERT_ID();

    INSERT INTO course_fee (course_id, fee, effective_from) 
    VALUES (new_course_id, p_fee, p_effective_from);

    SELECT new_course_id AS course_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteCourse` (IN `p_course_id` INT)   BEGIN
    DELETE FROM course_subject WHERE course_id = p_course_id;
    DELETE FROM course_fee WHERE course_id = p_course_id;
    DELETE FROM course WHERE id = p_course_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_course_details` (IN `courseId` INT)   BEGIN
    -- Fetch course
    SELECT * FROM course WHERE id = courseId;

    -- Fetch latest course fee
    SELECT * FROM course_fee 
    WHERE course_id = courseId 
    ORDER BY effective_from DESC 
    LIMIT 1;

    -- Fetch all active subjects
    SELECT * FROM subject WHERE status = 'Active';

    -- Fetch selected subject IDs
    SELECT subject_id FROM course_subject 
    WHERE course_id = courseId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_course` (IN `p_course_id` INT, IN `p_course_name` VARCHAR(255), IN `p_duration` VARCHAR(100), IN `p_new_fee` DECIMAL(10,2), IN `p_subjects` TEXT)   BEGIN
    DECLARE v_old_fee DECIMAL(10,2);

    -- Check duplicate course name
    IF (SELECT COUNT(*) FROM course WHERE name = p_course_name AND id != p_course_id) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Course name already exists!';
    END IF;

    -- Update course info
    UPDATE course SET name = p_course_name, duration = p_duration WHERE id = p_course_id;

    -- Get current fee
    SELECT fee INTO v_old_fee FROM course_fee 
    WHERE course_id = p_course_id 
    ORDER BY effective_from DESC 
    LIMIT 1;

    -- Insert new fee if changed
    IF v_old_fee IS NULL OR v_old_fee != p_new_fee THEN
        INSERT INTO course_fee (course_id, fee, effective_from)
        VALUES (p_course_id, p_new_fee, CURDATE());
    END IF;

    -- Update course_subject mapping
    DELETE FROM course_subject WHERE course_id = p_course_id;

    -- Split and insert subjects
    WHILE LOCATE(',', p_subjects) > 0 DO
        INSERT INTO course_subject (course_id, subject_id, created_at, status)
        VALUES (p_course_id, SUBSTRING_INDEX(p_subjects, ',', 1), NOW(), 'Active');
        SET p_subjects = SUBSTRING(p_subjects, LOCATE(',', p_subjects) + 1);
    END WHILE;
    -- Insert last subject
    IF LENGTH(p_subjects) > 0 THEN
        INSERT INTO course_subject (course_id, subject_id, created_at, status)
        VALUES (p_course_id, p_subjects, NOW(), 'Active');
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `duration` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `name`, `description`, `created_at`, `status`, `duration`) VALUES
(1, 'MERN', NULL, '2025-04-02 06:22:20', 'Active', '10 months'),
(2, 'Full Stack PHP', NULL, '2025-04-02 06:22:20', 'Active', '9 months'),
(3, 'Full Stack Django', NULL, '2025-04-02 06:22:20', 'Active', '11 months'),
(4, 'Data Analytics', NULL, '2025-04-02 06:22:20', 'Active', '11 months'),
(5, 'UI/UX', NULL, '2025-04-02 06:22:20', 'Active', '9 months'),
(6, 'Mobile App', NULL, '2025-04-02 06:22:20', 'Active', '10 months'),
(7, 'Full Stack ', NULL, '2025-04-24 10:03:40', 'Active', '5 months');

-- --------------------------------------------------------

--
-- Table structure for table `course_fee`
--

CREATE TABLE `course_fee` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `fee` float(10,2) NOT NULL,
  `effective_from` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_fee`
--

INSERT INTO `course_fee` (`id`, `course_id`, `fee`, `effective_from`, `created_at`, `status`) VALUES
(1, 2, 14000.00, '2023-11-01', '2025-04-02 12:25:14', 'Active'),
(2, 4, 45000.00, '2024-07-01', '2025-04-02 12:25:14', 'Active'),
(3, 2, 7000.00, '2025-04-08', '2025-04-08 04:49:47', 'Active'),
(4, 4, 60000.00, '2024-04-08', '2025-04-08 04:51:09', 'Active'),
(5, 3, 7000.00, '2023-04-04', '2025-04-08 04:56:14', 'Active'),
(6, 3, 14000.00, '2023-04-04', '2025-04-08 04:56:14', 'Active'),
(7, 1, 14000.00, '2023-03-04', '2025-04-08 04:58:06', 'Active'),
(8, 1, 8000.00, '2023-04-04', '2025-04-08 04:58:06', 'Active'),
(9, 1, 18000.00, '2023-04-08', '2025-04-08 04:58:06', 'Active'),
(10, 5, 300000.00, '2025-04-24', '2025-04-24 10:02:17', 'Active'),
(11, 6, 450000.00, '2025-04-24', '2025-04-24 10:02:28', 'Active'),
(12, 7, 50000.00, '2025-04-24', '2025-04-24 10:03:40', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `course_incharge`
--

CREATE TABLE `course_incharge` (
  `id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_subject`
--

CREATE TABLE `course_subject` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_subject`
--

INSERT INTO `course_subject` (`id`, `course_id`, `subject_id`, `created_at`, `status`) VALUES
(15, 2, 1, '2025-04-24 10:01:38', 'Active'),
(16, 2, 2, '2025-04-24 10:01:38', 'Active'),
(17, 2, 3, '2025-04-24 10:01:38', 'Active'),
(18, 2, 4, '2025-04-24 10:01:38', 'Active'),
(19, 2, 5, '2025-04-24 10:01:38', 'Active'),
(20, 2, 10, '2025-04-24 10:01:38', 'Active'),
(21, 3, 1, '2025-04-24 10:01:50', 'Active'),
(22, 3, 3, '2025-04-24 10:01:50', 'Active'),
(23, 3, 4, '2025-04-24 10:01:50', 'Active'),
(24, 3, 5, '2025-04-24 10:01:50', 'Active'),
(25, 4, 1, '2025-04-24 10:02:04', 'Active'),
(26, 4, 2, '2025-04-24 10:02:04', 'Active'),
(27, 4, 3, '2025-04-24 10:02:04', 'Active'),
(28, 4, 4, '2025-04-24 10:02:04', 'Active'),
(29, 4, 6, '2025-04-24 10:02:04', 'Active'),
(30, 4, 11, '2025-04-24 10:02:04', 'Active'),
(31, 5, 12, '2025-04-24 10:02:17', 'Active'),
(32, 5, 13, '2025-04-24 10:02:17', 'Active'),
(33, 6, 7, '2025-04-24 10:02:28', 'Active'),
(34, 7, 1, '2025-04-24 10:03:40', 'Active'),
(35, 7, 2, '2025-04-24 10:03:40', 'Active'),
(36, 7, 3, '2025-04-24 10:03:40', 'Active'),
(37, 7, 4, '2025-04-24 10:03:40', 'Active'),
(79, 1, 1, '2025-04-25 04:22:43', 'Active'),
(80, 1, 2, '2025-04-25 04:22:43', 'Active'),
(81, 1, 3, '2025-04-25 04:22:43', 'Active'),
(82, 1, 4, '2025-04-25 04:22:43', 'Active'),
(83, 1, 8, '2025-04-25 04:22:43', 'Active'),
(84, 1, 10, '2025-04-25 04:22:43', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `status` enum('Active','In-Active') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `status`, `created_at`) VALUES
(1, 'Administration', NULL, '2025-04-02 05:17:33'),
(2, 'Development', NULL, '2025-04-02 05:17:33'),
(3, 'Marketing', NULL, '2025-04-02 05:17:33');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `document_name` varchar(50) DEFAULT NULL,
  `document_file` varchar(200) DEFAULT NULL,
  `created_by` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `listtrainee`
-- (See below for the actual view)
--
CREATE TABLE `listtrainee` (
`id` int(11)
,`name` varchar(50)
,`phone_number` varchar(15)
,`email` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `register_no` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `name`, `register_no`, `email`, `created_at`, `status`) VALUES
(1, 'Ragu', NULL, NULL, '2025-04-01 16:25:56', 'Active'),
(2, 'Priya', NULL, NULL, '2025-04-01 16:26:23', 'Active'),
(3, 'Asha', NULL, NULL, '2025-04-02 05:13:14', 'Active'),
(4, 'Sheeba', NULL, NULL, '2025-04-02 05:13:14', 'Active'),
(5, 'Selva Kumar', NULL, NULL, '2025-04-02 05:13:43', 'Active'),
(6, 'Shifana', NULL, NULL, '2025-04-02 05:13:43', 'Active'),
(7, 'Kalyani', NULL, NULL, '2025-04-02 05:14:01', 'Active'),
(8, 'Petchi Muthu', NULL, NULL, '2025-04-02 05:14:01', 'Active'),
(9, 'Nathiya', NULL, NULL, '2025-04-02 05:14:20', 'Active'),
(10, 'Veeralakshmi', NULL, NULL, '2025-04-02 05:14:20', 'Active'),
(11, 'Vignesh', NULL, NULL, '2025-04-02 05:14:49', 'Active'),
(12, 'Muthu Selvan', NULL, NULL, '2025-04-02 05:14:49', 'Active'),
(16, 'tete', NULL, 'tete@gmail.com', '2025-04-03 06:36:38', 'Active'),
(17, 'demoperson', NULL, 'demo@gmail.com', '2025-04-03 06:42:53', 'Active'),
(18, 'test1', NULL, 'test1@gmail.com', '2025-04-03 06:44:26', 'Active'),
(22, 'doggie', '123123', 'dodo@gmail.com', '2025-04-04 07:15:17', 'Active'),
(28, 'kjhgjrew', 'gfc675', 'gfyug@gmail.com', '2025-04-04 07:22:28', 'Active'),
(29, 'rtu', '543hv', 'yuyuyg@gmail.com', '2025-04-04 07:25:05', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `person_course`
--

CREATE TABLE `person_course` (
  `id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `course_fee_id` int(11) NOT NULL,
  `fee_amount` float(10,2) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person_course`
--

INSERT INTO `person_course` (`id`, `person_id`, `course_fee_id`, `fee_amount`, `status`, `created_at`) VALUES
(1, 7, 1, 10000.00, 'Active', '2025-04-02 12:33:57'),
(2, 9, 2, 40000.00, 'Active', '2025-04-02 12:33:57'),
(3, 17, 7, 14000.00, 'Active', '2025-04-08 05:05:28'),
(4, 18, 9, 15000.00, 'Active', '2025-04-08 05:05:28'),
(5, 22, 2, 14000.00, 'Active', '2025-04-08 05:05:28');

-- --------------------------------------------------------

--
-- Table structure for table `person_department`
--

CREATE TABLE `person_department` (
  `id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `person_details`
--

CREATE TABLE `person_details` (
  `id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `doj` date DEFAULT NULL,
  `gender` enum('Male','Female','Others') DEFAULT NULL,
  `blood_group` varchar(15) DEFAULT NULL,
  `profile` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person_details`
--

INSERT INTO `person_details` (`id`, `person_id`, `last_name`, `phone_number`, `address`, `dob`, `doj`, `gender`, `blood_group`, `profile`) VALUES
(1, 7, NULL, '1234567890', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 9, NULL, '1987654321', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 16, NULL, '1234560987', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 17, NULL, '1234567899', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 18, NULL, '1234554326', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 22, NULL, '1212343444', 'dododo', '0000-00-00', NULL, 'Male', 'o+ve', 'assets/images/profile/doggie.jpg'),
(13, 28, NULL, '1234567888', '', '0000-00-00', NULL, '', '', '../assets/images/profile/doggie.jpg'),
(14, 29, NULL, '1234543213', 'ds', '0000-00-00', NULL, '', '', 'assets/images/profile/doggie.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `person_fee_paid`
--

CREATE TABLE `person_fee_paid` (
  `id` int(11) NOT NULL,
  `person_course_id` int(11) DEFAULT NULL,
  `paid_amount` float(10,2) DEFAULT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person_fee_paid`
--

INSERT INTO `person_fee_paid` (`id`, `person_course_id`, `paid_amount`, `payment_mode`, `status`, `created_at`, `created_by`) VALUES
(1, 1, 7000.00, 'cash', 'Active', '2025-04-05 05:25:53', 6),
(2, 2, 30000.00, 'cash', 'Active', '2025-04-01 05:25:53', 3),
(3, 1, 3000.00, 'cash', 'Active', '2025-04-06 05:25:53', 3),
(4, 2, 5000.00, 'cash', 'Active', '2025-04-07 05:25:53', 6),
(5, 3, 14000.00, 'cash', 'Active', '2025-04-07 05:27:07', 6),
(6, 2, 5000.00, 'cash', 'Active', '2025-04-08 05:27:57', 6),
(7, 4, 10000.00, 'cash', 'Active', '2025-04-08 05:27:57', 6);

-- --------------------------------------------------------

--
-- Table structure for table `person_login`
--

CREATE TABLE `person_login` (
  `id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person_login`
--

INSERT INTO `person_login` (`id`, `person_id`, `username`, `password`, `created_at`) VALUES
(1, 1, 'Ragu', 'ragu', '2025-04-02 11:29:30'),
(2, 9, 'nathiy', '$2y$10$0kCvjiVRyWGW4UOVFP4Jb.gFBurx5XEwiORoigE.je57YueFdzr.G', '2025-04-09 09:27:27');

-- --------------------------------------------------------

--
-- Table structure for table `person_roles`
--

CREATE TABLE `person_roles` (
  `id` int(11) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person_roles`
--

INSERT INTO `person_roles` (`id`, `person_id`, `role_id`, `created_at`) VALUES
(1, 3, 1, '2025-04-02 05:19:44'),
(2, 7, 4, '2025-04-02 05:19:44'),
(3, 12, 5, '2025-04-02 05:19:44'),
(4, 9, 4, '2025-04-02 05:19:44'),
(5, 8, 9, '2025-04-02 05:19:44'),
(6, 2, 7, '2025-04-02 05:19:44'),
(7, 1, 6, '2025-04-02 05:19:44'),
(8, 4, 3, '2025-04-02 05:19:44'),
(9, 5, 10, '2025-04-02 05:20:07'),
(10, 6, 2, '2025-04-02 05:20:07'),
(11, 10, 5, '2025-04-02 05:20:43'),
(12, 11, 8, '2025-04-02 05:20:43'),
(13, 16, 4, '2025-04-03 06:36:38'),
(14, 17, 4, '2025-04-03 06:42:53'),
(15, 18, 4, '2025-04-03 06:44:26'),
(18, 22, 4, '2025-04-04 07:15:17'),
(19, 28, 4, '2025-04-04 07:22:28'),
(20, 29, 4, '2025-04-04 07:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`) VALUES
(1, 'Admin', '2025-04-02 05:15:36'),
(2, 'HR', '2025-04-02 05:15:36'),
(3, 'CEO', '2025-04-02 05:15:36'),
(4, 'Trainee', '2025-04-02 05:15:36'),
(5, 'Trainer', '2025-04-02 05:15:36'),
(6, 'MD', '2025-04-02 05:16:48'),
(7, 'Director', '2025-04-02 05:16:48'),
(8, 'Asset Manager', '2025-04-02 05:16:48'),
(9, 'Project Manager', '2025-04-02 05:16:48'),
(10, 'Stratagic Manager', '2025-04-02 05:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `name`, `description`, `create_at`, `status`) VALUES
(1, 'HTML', NULL, '2025-04-02 06:22:58', 'Active'),
(2, 'CSS', NULL, '2025-04-02 06:22:58', 'Active'),
(3, 'JavaScript', NULL, '2025-04-02 06:23:27', 'Active'),
(4, 'Bootstrap', NULL, '2025-04-02 06:23:27', 'Active'),
(5, 'PHP', NULL, '2025-04-02 06:23:40', 'Active'),
(6, 'Python', NULL, '2025-04-02 06:23:40', 'Active'),
(7, 'Flutter', NULL, '2025-04-02 06:56:36', 'Active'),
(8, 'React JS', NULL, '2025-04-02 06:56:36', 'Active'),
(9, 'Node JS', NULL, '2025-04-02 06:56:36', 'Active'),
(10, 'MySQL', NULL, '2025-04-02 06:56:36', 'Active'),
(11, 'Power BI', NULL, '2025-04-02 06:56:36', 'Active'),
(12, 'Photoshop', NULL, '2025-04-02 06:56:56', 'Active'),
(13, 'Canva', NULL, '2025-04-02 06:56:56', 'Active');

-- --------------------------------------------------------

--
-- Stand-in structure for view `subject_with_syllabus`
-- (See below for the actual view)
--
CREATE TABLE `subject_with_syllabus` (
`subject_id` int(11)
,`subject_name` varchar(50)
,`syllabus_id` int(11)
,`syllabus_status` enum('Active','Inactive')
,`syllabus_description` text
,`syllabus_created_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `syllabus`
--

CREATE TABLE `syllabus` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `syllabus`
--

INSERT INTO `syllabus` (`id`, `subject_id`, `name`, `description`, `created_at`, `status`) VALUES
(1, 1, 'Introduction', NULL, '2025-04-02 06:44:06', 'Active'),
(2, 1, 'Syntax', NULL, '2025-04-02 06:44:06', 'Active'),
(3, 1, 'Table', NULL, '2025-04-02 06:44:06', 'Active'),
(4, 2, 'Introduction', NULL, '2025-04-02 06:45:04', 'Active'),
(5, 2, 'Syntax', NULL, '2025-04-02 06:45:04', 'Active'),
(6, 2, 'Media query', NULL, '2025-04-02 06:45:04', 'Active'),
(7, 3, 'Introduction', NULL, '2025-04-02 06:45:32', 'Active'),
(8, 3, 'Syntax', NULL, '2025-04-02 06:45:32', 'Active'),
(9, 3, 'Array', NULL, '2025-04-02 06:45:32', 'Active'),
(10, 4, 'Introduction', NULL, '2025-04-02 06:46:06', 'Active'),
(11, 4, 'Carosoul', NULL, '2025-04-02 06:46:06', 'Active'),
(12, 4, 'Cards', NULL, '2025-04-02 06:46:06', 'Active'),
(13, 5, 'Introduction', NULL, '2025-04-02 06:46:38', 'Active'),
(14, 5, 'Syntax', NULL, '2025-04-02 06:46:38', 'Active'),
(15, 5, 'Array', NULL, '2025-04-02 06:46:38', 'Active'),
(16, 6, 'Introduction', NULL, '2025-04-02 06:46:59', 'Active'),
(17, 6, 'Syntax', NULL, '2025-04-02 06:46:59', 'Active'),
(18, 6, 'OOPS', NULL, '2025-04-02 06:46:59', 'Active');

-- --------------------------------------------------------

--
-- Stand-in structure for view `viewtrainee`
-- (See below for the actual view)
--
CREATE TABLE `viewtrainee` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_course_details`
-- (See below for the actual view)
--
CREATE TABLE `view_course_details` (
`course_id` int(11)
,`course_name` varchar(30)
,`subjects` mediumtext
,`duration` varchar(20)
,`fee` float(10,2)
);

-- --------------------------------------------------------

--
-- Structure for view `listtrainee`
--
DROP TABLE IF EXISTS `listtrainee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `listtrainee`  AS SELECT `p`.`id` AS `id`, `p`.`name` AS `name`, `pd`.`phone_number` AS `phone_number`, `p`.`email` AS `email` FROM (((`person` `p` join `person_details` `pd` on(`p`.`id` = `pd`.`person_id`)) join `person_roles` `pr` on(`p`.`id` = `pr`.`person_id`)) join `role` `r` on(`pr`.`role_id` = `r`.`id`)) WHERE `r`.`name` = 'Trainee' ;

-- --------------------------------------------------------

--
-- Structure for view `subject_with_syllabus`
--
DROP TABLE IF EXISTS `subject_with_syllabus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `subject_with_syllabus`  AS SELECT `subject`.`id` AS `subject_id`, `subject`.`name` AS `subject_name`, `syllabus`.`id` AS `syllabus_id`, `syllabus`.`status` AS `syllabus_status`, `syllabus`.`description` AS `syllabus_description`, `syllabus`.`created_at` AS `syllabus_created_at` FROM (`subject` left join `syllabus` on(`subject`.`id` = `syllabus`.`subject_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `viewtrainee`
--
DROP TABLE IF EXISTS `viewtrainee`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewtrainee`  AS SELECT `p`.`id` AS `person_id`, `p`.`name` AS `person_name`, `p`.`email` AS `email`, `p`.`register_no` AS `register_no`, `pd`.`phone_number` AS `phone_number`, `pd`.`address` AS `address`, `pd`.`dob` AS `dob`, `pd`.`doj` AS `doj`, `pd`.`profile` AS `profile`, `pd`.`gender` AS `gender`, `pd`.`blood_group` AS `blood_group`, `pl`.`id` AS `login_id`, `pl`.`username` AS `username`, `pl`.`password` AS `password`, `pc`.`id` AS `person_course_id`, `pc`.`fee_amount` AS `course_amount`, `cf`.`id` AS `course_fee_id`, `cf`.`fee` AS `total_amount`, `cf`.`duration` AS `duration`, `c`.`id` AS `course_id`, `c`.`name` AS `course_name`, `c`.`description` AS `course_description`, `c2`.`incharge` AS `incharger`, `pfp`.`id` AS `fee_paid_id`, `pfp`.`paid_amount` AS `paid_amount`, `pfp2`.`created_by` AS `created_by` FROM ((((((((`person` `p` join `person_details` `pd` on(`p`.`id` = `pd`.`person_id`)) left join `person_login` `pl` on(`p`.`id` = `pl`.`person_id`)) left join `person_course` `pc` on(`p`.`id` = `pc`.`person_id`)) left join `course_fee` `cf` on(`cf`.`id` = `pc`.`course_fee_id`)) left join `course` `c` on(`c`.`id` = `cf`.`course_id`)) left join `course` `c2` on(`c2`.`incharge` = `p`.`id`)) left join `person_fee_paid` `pfp` on(`pfp`.`person_course_id` = `pc`.`id`)) left join `person_fee_paid` `pfp2` on(`pfp2`.`created_by` = `p`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_course_details`
--
DROP TABLE IF EXISTS `view_course_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_course_details`  AS SELECT `c`.`id` AS `course_id`, `c`.`name` AS `course_name`, group_concat(distinct `s`.`name` separator ', ') AS `subjects`, `c`.`duration` AS `duration`, (select `cf`.`fee` from `course_fee` `cf` where `cf`.`course_id` = `c`.`id` order by `cf`.`effective_from` desc limit 1) AS `fee` FROM ((`course` `c` left join `course_subject` `cs` on(`c`.`id` = `cs`.`course_id`)) left join `subject` `s` on(`cs`.`subject_id` = `s`.`id` and `s`.`status` = 'Active')) WHERE `c`.`status` = 'Active' GROUP BY `c`.`id`, `c`.`name`, `c`.`duration` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_fee`
--
ALTER TABLE `course_fee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_incharge`
--
ALTER TABLE `course_incharge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_subject`
--
ALTER TABLE `course_subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `person_course`
--
ALTER TABLE `person_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `course_fee_id` (`course_fee_id`);

--
-- Indexes for table `person_department`
--
ALTER TABLE `person_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `person_details`
--
ALTER TABLE `person_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `person_fee_paid`
--
ALTER TABLE `person_fee_paid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_course_id` (`person_course_id`),
  ADD KEY `fk_created_by` (`created_by`);

--
-- Indexes for table `person_login`
--
ALTER TABLE `person_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_person` (`person_id`);

--
-- Indexes for table `person_roles`
--
ALTER TABLE `person_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `person_id` (`person_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `syllabus`
--
ALTER TABLE `syllabus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subject` (`subject_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `course_fee`
--
ALTER TABLE `course_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `course_incharge`
--
ALTER TABLE `course_incharge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_subject`
--
ALTER TABLE `course_subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `person_course`
--
ALTER TABLE `person_course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `person_department`
--
ALTER TABLE `person_department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `person_details`
--
ALTER TABLE `person_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `person_fee_paid`
--
ALTER TABLE `person_fee_paid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `person_login`
--
ALTER TABLE `person_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `person_roles`
--
ALTER TABLE `person_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `syllabus`
--
ALTER TABLE `syllabus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_fee`
--
ALTER TABLE `course_fee`
  ADD CONSTRAINT `course_fee_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- Constraints for table `course_incharge`
--
ALTER TABLE `course_incharge`
  ADD CONSTRAINT `course_incharge_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `course_incharge_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`);

--
-- Constraints for table `course_subject`
--
ALTER TABLE `course_subject`
  ADD CONSTRAINT `course_subject_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `course_subject_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `document_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`);

--
-- Constraints for table `person_course`
--
ALTER TABLE `person_course`
  ADD CONSTRAINT `person_course_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `person_course_ibfk_2` FOREIGN KEY (`course_fee_id`) REFERENCES `course_fee` (`id`);

--
-- Constraints for table `person_department`
--
ALTER TABLE `person_department`
  ADD CONSTRAINT `person_department_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `person_department_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `person_details`
--
ALTER TABLE `person_details`
  ADD CONSTRAINT `person_details_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`);

--
-- Constraints for table `person_fee_paid`
--
ALTER TABLE `person_fee_paid`
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `person` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `person_fee_paid_ibfk_1` FOREIGN KEY (`person_course_id`) REFERENCES `person_course` (`id`);

--
-- Constraints for table `person_login`
--
ALTER TABLE `person_login`
  ADD CONSTRAINT `fk_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`);

--
-- Constraints for table `person_roles`
--
ALTER TABLE `person_roles`
  ADD CONSTRAINT `person_roles_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `person_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `syllabus`
--
ALTER TABLE `syllabus`
  ADD CONSTRAINT `fk_subject` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
