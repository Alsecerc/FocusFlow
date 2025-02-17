-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2025 at 07:39 AM
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
-- Database: `assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_title` varchar(255) NOT NULL,
  `task_desc` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_title`, `task_desc`, `start_date`, `start_time`, `end_time`, `created_at`, `user_id`) VALUES
(2, 'Send email to boss', 'Draft and send the project update email.', '2025-02-15', '09:30:00', '10:00:00', '2025-02-17 01:13:34', 1),
(4, 'Send email to team', 'Draft and send the email about the upcoming project deadline', '2025-02-15', '09:00:00', '09:30:00', '2025-02-17 01:23:35', 1),
(5, 'Prepare weekly report', 'Collect data and prepare the weekly performance report', '2025-02-16', '10:00:00', '12:00:00', '2025-02-17 01:23:35', 1),
(6, 'Meeting with client', 'Discuss project progress and deliverables with the client', '2025-02-17', '14:00:00', '15:00:00', '2025-02-17 01:23:35', 1),
(7, 'Prepare presentation', 'Create slides for the upcoming quarterly meeting', '2025-02-16', '10:00:00', '11:30:00', '2025-02-17 01:23:36', 2),
(8, 'Research competitors', 'Analyze competitors\' products and pricing strategies', '2025-02-17', '12:30:00', '14:00:00', '2025-02-17 01:23:36', 2),
(9, 'Write proposal', 'Write the business proposal for the new client', '2025-02-18', '09:00:00', '11:00:00', '2025-02-17 01:23:36', 2),
(10, 'Update website', 'Work on the new website design for the client', '2025-02-17', '13:00:00', '14:30:00', '2025-02-17 01:23:36', 3),
(11, 'Client call', 'Call the client to discuss their feedback on the website design', '2025-02-18', '15:00:00', '15:30:00', '2025-02-17 01:23:36', 3),
(12, 'Prepare demo', 'Prepare a demo for the new feature on the website', '2025-02-19', '10:00:00', '11:00:00', '2025-02-17 01:23:36', 3),
(13, 'Write project report', 'Write the final report for the project completion', '2025-02-18', '15:00:00', '16:00:00', '2025-02-17 01:23:36', 4),
(14, 'Team meeting', 'Conduct a meeting with the team to discuss progress', '2025-02-19', '09:30:00', '10:30:00', '2025-02-17 01:23:36', 4),
(15, 'Review code', 'Review the code written by the development team', '2025-02-20', '11:00:00', '12:30:00', '2025-02-17 01:23:36', 4),
(16, 'Conduct team meeting', 'Organize and lead a team meeting for the new project', '2025-02-19', '08:30:00', '09:00:00', '2025-02-17 01:23:36', 5),
(17, 'Project planning', 'Plan the timeline and resources for the new project', '2025-02-20', '14:00:00', '16:00:00', '2025-02-17 01:23:36', 5),
(18, 'Write documentation', 'Document the code and functionality for the project', '2025-02-21', '09:00:00', '10:30:00', '2025-02-17 01:23:36', 5),
(19, 'Testing', 'testing desc', '2025-02-17', '01:11:00', '02:11:00', '2025-02-17 01:57:03', 1),
(20, 'asd', 'asd', '2025-02-18', '01:01:00', '05:01:00', '2025-02-17 01:57:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `usertype` tinyint(1) DEFAULT 0,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `usertype`, `last_login`) VALUES
(1, 'John Doe', 'johndoe@example.com', 'P@ssw0rd123', '2025-02-17 01:11:39', 1, '2025-02-17 06:21:59'),
(2, 'Jane Smith', 'janesmith@example.com', 'J@ne2024!', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59'),
(3, 'Michael Brown', 'michaelbrown@example.com', 'M!ke7890', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59'),
(4, 'Sarah Lee', 'sarahlee@example.com', 'S@rah2024#', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59'),
(5, 'David Clark', 'davidclark@example.com', 'D@vid2024$', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59'),
(6, 'asd', 'asd', 'asd', '2025-02-17 06:22:45', 0, '2025-02-17 06:22:45'),
(14, '12', '12', 'asd', '2025-02-17 06:31:03', 0, '2025-02-17 06:31:03'),
(16, '12312312', '12312321', '123123', '2025-02-17 06:32:57', 0, '2025-02-17 06:32:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
