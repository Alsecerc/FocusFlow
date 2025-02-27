-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2025 at 04:33 PM
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
  `team_name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `user_id`, `team_name`, `file_name`, `file_type`, `file_size`, `file_path`, `uploaded_at`) VALUES
(1, 1, 'Team Alpha', 'SYAD flight route (1).png', 'image/png', 243872, 'uploads/SYAD flight route (1).png', '2025-02-27 03:42:13');

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
(4, 2, 'Yoga Class', 'Attend class', 'long-term', '2025-02-28', '2025-03-08', 50, 'in-progress', '2025-02-27 14:55:00', '2025-02-20 06:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `group_tasks`
--

CREATE TABLE `group_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `assigned_by` int(10) UNSIGNED NOT NULL,
  `assigned_to` int(10) UNSIGNED NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text NOT NULL,
  `status` enum('pending','in progress','completed') DEFAULT 'pending',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 1, 2, 'Hey Jane, how are you?', '2025-02-20 10:00:00', 'sent'),
(2, 2, 1, 'Hi John! I\'m doing well, thanks!', '2025-02-20 10:05:00', 'sent'),
(3, 3, 4, 'Sarah, did you finish the report?', '2025-02-20 11:00:00', 'sent'),
(4, 4, 3, 'Michael, I\'m still working on it.', '2025-02-20 11:15:00', 'sent'),
(5, 5, 6, 'Alex, are you free for a call?', '2025-02-20 12:00:00', 'sent'),
(6, 6, 5, 'Sure David, give me a moment.', '2025-02-20 12:05:00', 'sent'),
(7, 7, 8, 'Daniel, check your email.', '2025-02-20 13:00:00', 'sent'),
(8, 8, 7, 'Thanks Sophia, I got it!', '2025-02-20 13:10:00', 'sent'),
(9, 9, 10, 'Liam, are you coming to the meeting?', '2025-02-20 14:00:00', 'sent'),
(10, 10, 9, 'Yes Olivia, I\'ll be there.', '2025-02-20 14:05:00', 'sent'),
(11, 11, 12, 'Mason, I sent the documents.', '2025-02-20 15:00:00', 'sent'),
(12, 12, 11, 'Thanks Emma, I\'ll review them.', '2025-02-20 15:15:00', 'sent'),
(13, 13, 14, 'James, did you complete the task?', '2025-02-20 16:00:00', 'sent'),
(14, 14, 13, 'Almost done Ava!', '2025-02-20 16:20:00', 'sent'),
(15, 15, 16, 'Ethan, let\'s catch up later.', '2025-02-20 17:00:00', 'sent'),
(16, 16, 15, 'Sure Mia, see you later!', '2025-02-20 17:10:00', 'sent'),
(17, 17, 1, 'Testing message functionality.', '2025-02-20 18:00:00', 'sent'),
(21, 2, 3, 'sup bro', '2025-02-20 16:53:33', 'sent'),
(22, 3, 2, 'hello', '2025-02-20 16:54:37', 'sent'),
(23, 2, 1, 'Hello John', '2025-02-24 13:54:20', 'sent'),
(24, 1, 3, 'Wassup Micheal', '2025-02-27 09:48:15', 'sent'),
(25, 1, 4, 'Wassup Sarah', '2025-02-27 09:48:26', 'sent'),
(26, 1, 2, 'Helllllooo', '2025-02-27 09:52:04', 'sent'),
(27, 1, 4, 'aagagaaga', '2025-02-27 09:52:33', 'sent');

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
  `notification_message` varchar(255) DEFAULT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message_id`, `sender_id`, `type`, `notification_message`, `status`, `created_at`) VALUES
(1, 2, 1, 1, 'message', 'You have a new message from John Doe.', 'unread', '2025-02-20 02:00:05'),
(2, 1, 2, 2, 'message', 'Jane Smith sent you a message.', 'unread', '2025-02-20 02:05:10'),
(3, 4, 3, 3, 'message', 'Michael Brown messaged you.', 'unread', '2025-02-20 03:00:05'),
(4, 3, NULL, NULL, 'system', 'System Update: Scheduled maintenance at 2 AM.', 'unread', '2025-02-20 03:15:10'),
(5, 6, 5, 5, 'message', 'David Clark sent you a message.', 'unread', '2025-02-20 04:00:05'),
(6, 5, NULL, NULL, 'system', 'New Feature: Dark Mode is now available!', 'unread', '2025-02-20 04:10:10'),
(7, 8, 7, 7, 'message', 'Olivia Clark sent you a message.', 'unread', '2025-02-20 05:00:05'),
(8, 7, 8, 8, 'message', 'Liam Jackson replied to your message.', 'read', '2025-02-20 05:10:10'),
(9, 10, NULL, NULL, 'system', 'Security Alert: Unusual login detected.', 'unread', '2025-02-20 06:00:05'),
(10, 9, 10, 10, 'message', 'Emma White messaged you.', 'unread', '2025-02-20 06:05:10'),
(11, 12, 11, 11, 'message', 'Mason Harris sent you a message.', 'unread', '2025-02-20 07:00:05'),
(12, 11, 12, 12, 'message', 'Ava Mitchell replied to your message.', 'read', '2025-02-20 07:15:10'),
(13, 14, 13, 13, 'message', 'James Anderson sent you a message.', 'unread', '2025-02-20 08:00:05'),
(14, 13, NULL, NULL, 'system', 'Reminder: Update your profile for better security.', 'unread', '2025-02-20 08:20:10'),
(15, 16, 15, 15, 'message', 'Mia Taylor messaged you.', 'unread', '2025-02-20 09:00:05'),
(16, 15, 16, 16, 'message', 'Ethan Brown replied to your message.', 'read', '2025-02-20 09:10:10'),
(17, 1, NULL, NULL, 'system', 'Your subscription will expire soon. Renew now!', 'unread', '2025-02-20 10:00:05'),
(19, 1, 23, 2, 'message', 'You have a new message', 'unread', '2025-02-24 05:54:20'),
(20, 3, 24, 1, 'message', 'You have a new message', 'unread', '2025-02-27 01:48:15'),
(21, 4, 25, 1, 'message', 'You have a new message', 'unread', '2025-02-27 01:48:26'),
(22, 2, 26, 1, 'message', 'You have a new message', 'unread', '2025-02-27 01:52:04'),
(23, 4, 27, 1, 'message', 'You have a new message', 'unread', '2025-02-27 01:52:33');

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
  `user_id` int(10) UNSIGNED NOT NULL,
  `status` enum('Incomplete','Complete','Timeout') DEFAULT 'Incomplete',
  `category` varchar(255) NOT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_title`, `task_desc`, `start_date`, `start_time`, `end_time`, `created_at`, `user_id`, `status`, `category`, `end_date`) VALUES
(3, 'Submit English Essay', 'Upload final essay draft on portal.', '2025-02-28', '23:00:00', '23:30:00', '2025-02-25 13:34:25', 1, 'Complete', 'Academics', NULL),
(4, 'Group Presentation', 'Prepare slides for History project.', '2025-02-26', '16:00:00', '17:30:00', '2025-02-25 13:34:25', 2, 'Incomplete', 'Collaboration', NULL),
(5, 'Read Research Papers', 'Review 3 research papers for thesis.', '2025-03-01', '14:00:00', '15:30:00', '2025-02-25 13:34:25', 2, 'Complete', 'Research', NULL),
(6, 'Study for Biology Test', 'Memorize cell structures and functions.', '2025-02-27', '19:00:00', '21:00:00', '2025-02-25 13:34:25', 2, 'Incomplete', 'Exam Preparation', NULL),
(7, 'Weekly Standup Meeting', 'Discuss project updates with team.', '2025-03-02', '09:00:00', '09:45:00', '2025-02-25 13:34:25', 3, 'Incomplete', 'Meetings', NULL),
(8, 'Code Review', 'Check and approve teammates’ code.', '2025-03-03', '10:30:00', '11:30:00', '2025-02-25 13:34:25', 3, 'Complete', 'Software Development', NULL),
(9, 'Sprint Planning', 'Plan next development sprint tasks.', '2025-03-04', '14:00:00', '15:00:00', '2025-02-25 13:34:25', 3, 'Incomplete', 'Project Management', NULL),
(10, 'Client Report', 'Prepare and send progress report.', '2025-03-05', '16:00:00', '17:00:00', '2025-02-25 13:34:25', 4, 'Complete', 'Work Reports', NULL),
(11, 'Brainstorming Session', 'Come up with ideas for marketing.', '2025-03-06', '13:00:00', '14:30:00', '2025-02-25 13:34:25', 4, 'Incomplete', 'Creativity', NULL),
(12, 'Social Media Strategy', 'Plan posts for next month.', '2025-03-07', '15:00:00', '16:30:00', '2025-02-25 13:34:25', 4, 'Incomplete', 'Marketing', NULL),
(13, 'Budget Review', 'Analyze last quarter expenses.', '2025-03-08', '10:00:00', '11:30:00', '2025-02-25 13:34:25', 5, 'Incomplete', 'Finance', NULL),
(14, 'Employee Feedback Survey', 'Collect responses from team.', '2025-03-09', '12:00:00', '13:30:00', '2025-02-25 13:34:25', 5, 'Incomplete', 'HR', NULL),
(15, 'Plan Team Retreat', 'Finalize location and agenda.', '2025-03-10', '14:00:00', '15:30:00', '2025-02-25 13:34:25', 5, 'Complete', 'Event Planning', NULL),
(25, 'asd', 'asd', '2025-02-26', '01:01:00', '14:03:00', '2025-02-26 02:46:53', 1, 'Complete', 'Academics', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `leader_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `team_name`, `leader_id`, `member_id`, `created_at`) VALUES
(1, 'Alpha Squad', 1, 2, '2025-02-27 02:13:57'),
(2, 'Alpha Squad', 1, 3, '2025-02-27 02:13:57'),
(3, 'Beta Crew', 4, 5, '2025-02-27 02:13:57'),
(4, 'Beta Crew', 4, 6, '2025-02-27 02:13:57'),
(5, 'Gamma Force', 7, 8, '2025-02-27 02:13:57'),
(6, 'Gamma Force', 7, 9, '2025-02-27 02:13:57'),
(7, 'Delta Unit', 10, 11, '2025-02-27 02:13:57'),
(8, 'Delta Unit', 10, 12, '2025-02-27 02:13:57'),
(9, 'Team Alpha', 1, 2, '2025-02-27 02:16:06'),
(10, 'Team Alpha', 1, 3, '2025-02-27 02:16:06'),
(11, 'Team Alpha', 1, 4, '2025-02-27 02:16:06'),
(12, 'Team Beta', 2, 5, '2025-02-27 02:16:06'),
(13, 'Team Beta', 2, 6, '2025-02-27 02:16:06'),
(14, 'Team Beta', 2, 3, '2025-02-27 02:16:06'),
(15, 'Team Gamma', 3, 7, '2025-02-27 02:16:06'),
(16, 'Team Gamma', 3, 8, '2025-02-27 02:16:06'),
(17, 'Team Gamma', 3, 2, '2025-02-27 02:16:06'),
(18, 'Team Delta', 4, 9, '2025-02-27 02:16:06'),
(19, 'Team Delta', 4, 10, '2025-02-27 02:16:06'),
(20, 'Team Delta', 4, 5, '2025-02-27 02:16:06'),
(21, 'Team Omega', 5, 1, '2025-02-27 02:16:06'),
(22, 'Team Omega', 5, 3, '2025-02-27 02:16:06'),
(23, 'Team Omega', 5, 6, '2025-02-27 02:16:06');

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
(6, 'alex_walker', 'alex.walker@example.com', 'password6', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(7, 'sophia_martin', 'sophia.martin@example.com', 'password7', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(8, 'daniel_ross', 'daniel.ross@example.com', 'password8', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(9, 'olivia_clark', 'olivia.clark@example.com', 'password9', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(10, 'liam_jackson', 'liam.jackson@example.com', 'password10', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(11, 'emma_white', 'emma.white@example.com', 'password11', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(12, 'mason_harris', 'mason.harris@example.com', 'password12', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(13, 'ava_mitchell', 'ava.mitchell@example.com', 'password13', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(14, 'james_anderson', 'james.anderson@example.com', 'password14', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(15, 'mia taylor', 'mia.taylor@example.com', 'password15', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(16, 'ethan_brown', 'ethan.brown@example.com', 'password16', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23'),
(17, 'test', 'test@gmail.com', 'asd123', '2025-02-17 13:20:03', 0, '2025-02-17 13:20:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `group_tasks`
--
ALTER TABLE `group_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_by` (`assigned_by`),
  ADD KEY `assigned_to` (`assigned_to`);

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
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leader_id` (`leader_id`),
  ADD KEY `member_id` (`member_id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `group_tasks`
--
ALTER TABLE `group_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `group_tasks`
--
ALTER TABLE `group_tasks`
  ADD CONSTRAINT `group_tasks_ibfk_1` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`leader_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_timeout_event` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-02-27 23:29:09' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE tasks
  SET status = 'Timeout'
  WHERE CONCAT(end_date, ' ', end_time) <= NOW()
    AND status = 'Incomplete'$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
