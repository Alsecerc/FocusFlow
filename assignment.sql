-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2025 at 08:43 AM
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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `user_id`, `file_name`, `file_type`, `file_size`, `file_path`, `uploaded_at`) VALUES
(5, 1, 'MoodleQ3.drawio.png', 'image/png', 69159, 'uploads/MoodleQ3.drawio.png', '2025-02-20 01:20:51'),
(6, 1, '19_2_202.sql', 'application/octet-stream', 2349, 'uploads/19_2_202.sql', '2025-02-20 01:31:13'),
(7, 2, 'UseCaseDiagramQ1.drawio.png', 'image/png', 81964, 'uploads/UseCaseDiagramQ1.drawio.png', '2025-02-20 01:41:06'),
(8, 1, 'MoodleQ3.drawio.png', 'image/png', 69159, 'uploads/MoodleQ3.drawio.png', '2025-02-20 05:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `goal_id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `goal_title` varchar(255) NOT NULL,
  `goal_description` text DEFAULT NULL,
  `goal_type` enum('short-term','long-term') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `progress` int(11) DEFAULT 0 CHECK (`progress` between 0 and 100),
  `status` enum('in-progress','completed','failed') DEFAULT 'in-progress',
  `reminder_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`goal_id`, `user_id`, `goal_title`, `goal_description`, `goal_type`, `start_date`, `end_date`, `progress`, `status`, `reminder_time`, `created_at`) VALUES
(1, 1, 'Get Fit', 'Run 10 miles everyweek for a month', 'short-term', '2025-01-01', '2025-02-02', 100, 'completed', '2025-01-01 01:01:00', '2025-02-20 01:56:47'),
(2, 1, 'Get Fit', 'Run 10 miles everyweek for a month', 'short-term', '2025-01-01', '2025-02-02', 50, 'in-progress', '2025-01-01 01:01:00', '2025-02-20 01:57:36'),
(3, 1, 'Study', 'learn Java', 'long-term', '2025-04-10', '2025-05-10', 50, 'in-progress', '2025-01-01 11:11:00', '2025-02-20 05:18:29'),
(4, 2, 'Yoga Class', 'Attend class', 'long-term', '2025-02-28', '2025-03-08', 0, 'in-progress', '2025-02-27 14:55:00', '2025-02-20 06:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `message_text` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `status` enum('sent','delivered','read') DEFAULT 'sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `sender_id`, `receiver_id`, `message_text`, `sent_at`, `status`) VALUES
(1, 1, 2, 'asd', '2025-02-20 13:55:01', 'sent'),
(2, 1, 2, 'hello', '2025-02-20 13:58:10', 'sent'),
(3, 1, 2, 'hello', '2025-02-20 13:58:22', 'sent'),
(4, 1, 2, 'hello', '2025-02-20 13:59:23', 'sent'),
(5, 1, 2, 'hello', '2025-02-20 14:00:01', 'sent'),
(6, 1, 2, 'hello   sp', '2025-02-20 14:00:05', 'sent'),
(7, 1, 2, 'hello', '2025-02-20 14:00:41', 'sent'),
(8, 1, 2, 'hello', '2025-02-20 14:02:46', 'sent'),
(9, 1, 2, 'asd', '2025-02-20 14:03:20', 'sent'),
(10, 1, 2, 'asd', '2025-02-20 14:03:55', 'sent'),
(11, 1, 2, 'asd', '2025-02-20 14:04:27', 'sent'),
(12, 1, 2, 'asd', '2025-02-20 14:06:27', 'sent'),
(13, 1, 2, 'hello', '2025-02-20 14:06:42', 'sent'),
(14, 1, 2, 'wassup', '2025-02-20 14:18:25', 'sent'),
(15, 1, 2, 'wassup', '2025-02-20 14:18:34', 'sent'),
(16, 1, 3, 'Sup', '2025-02-20 14:22:55', 'sent'),
(17, 1, 3, 'hello', '2025-02-20 14:22:59', 'sent'),
(18, 2, 1, 'Hello CL', '2025-02-20 14:23:56', 'sent'),
(19, 1, 4, 'ay wassup', '2025-02-20 14:49:15', 'sent'),
(20, 2, 4, 'Hi im Jane', '2025-02-20 14:53:05', 'sent');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `message_id` int(10) UNSIGNED DEFAULT NULL,
  `sender_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('message','system') NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey_responses`
--

CREATE TABLE `survey_responses` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profession_role` varchar(255) NOT NULL,
  `ease_of_use` varchar(255) NOT NULL,
  `most_used_feature` varchar(255) NOT NULL,
  `impact` varchar(255) NOT NULL,
  `suggestions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `survey_responses`
--

INSERT INTO `survey_responses` (`id`, `username`, `email`, `profession_role`, `ease_of_use`, `most_used_feature`, `impact`, `suggestions`) VALUES
(9, 'John Doe', 'user1@gmail.com', 'entrepreneur', 'easy', 'calendar', 'significantly-more', 'test'),
(10, 'Username_123', 'user1@gmail.com', 'corporate-executive', 'very-easy', 'calendar', 'significantly-more', 'Test'),
(11, 'Username_123', 'user1@gmail.com', 'corporate-executive', 'very-easy', 'calendar', 'significantly-more', 'Test'),
(12, 'Username_123', 'user1@gmail.com', 'corporate-executive', 'very-easy', 'calendar', 'significantly-more', 'Test'),
(13, 'Poopies', 'poopies@gmail.com', 'entrepreneur', 'easy', 'task-management', 'significantly-more', 'Hello');

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
(20, 'asd', 'asd', '2025-02-18', '01:01:00', '05:01:00', '2025-02-17 01:57:23', 1),
(21, 'Testing', 'Testing Desc', '2025-04-04', '12:12:00', '15:15:00', '2025-02-17 13:54:04', 1),
(28, 'Help CL', 'help his php', '2025-02-20', '11:01:00', '12:12:00', '2025-02-20 05:20:10', 1);

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
(1, 'John Doe', 'poopie@gmail.com', 'Secure@123', '2025-02-17 01:11:39', 1, '2025-02-17 06:21:59'),
(2, 'Jane Smith', 'janesmith@example.com', 'J@ne2024!', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59'),
(3, 'Michael Brown', 'michaelbrown@example.com', 'M!ke7890', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59'),
(4, 'Sarah Lee', 'sarahlee@example.com', 'S@rah2024#', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59'),
(5, 'David Clark', 'davidclark@example.com', 'D@vid2024$', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59'),
(17, 'test', 'test@gmail.com', 'asd123', '2025-02-17 13:20:03', 0, '2025-02-17 13:20:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`message_id`) REFERENCES `message` (`message_id`) ON DELETE SET NULL;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
