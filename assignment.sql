-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 04:12 PM
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
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ContactListID` int(11) NOT NULL,
  `FriendID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `created_at`, `ContactListID`, `FriendID`) VALUES
(1, '2025-03-30 05:10:38', 1, 1),
(2, '2025-03-30 05:10:41', 2, 1),
(3, '2025-03-30 22:03:02', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `contactlist`
--

CREATE TABLE `contactlist` (
  `ContactID` int(11) NOT NULL,
  `UserID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contactlist`
--

INSERT INTO `contactlist` (`ContactID`, `UserID`) VALUES
(1, 1),
(2, 17);

-- --------------------------------------------------------

--
-- Table structure for table `directmessage`
--

CREATE TABLE `directmessage` (
  `DirectMessageID` int(11) NOT NULL,
  `MessageText` varchar(255) NOT NULL,
  `MessageType` varchar(10) NOT NULL,
  `CreatedTime` datetime NOT NULL DEFAULT current_timestamp(),
  `SenderID` int(10) NOT NULL,
  `ReceiverID` int(10) NOT NULL,
  `FriendID` int(11) NOT NULL,
  `Status` enum('sent','delivered','read','failed') NOT NULL DEFAULT 'sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `directmessage`
--

INSERT INTO `directmessage` (`DirectMessageID`, `MessageText`, `MessageType`, `CreatedTime`, `SenderID`, `ReceiverID`, `FriendID`, `Status`) VALUES
(1, 'hallo', 'TEXT', '2025-03-30 05:10:41', 1, 17, 1, 'sent'),
(2, 'hi', 'TEXT', '2025-03-30 05:10:52', 17, 1, 1, 'sent'),
(3, 'weqwe', 'TEXT', '2025-03-30 05:36:52', 17, 1, 1, 'sent');

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
-- Table structure for table `friendrequests`
--

CREATE TABLE `friendrequests` (
  `request_id` int(11) NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `friend_id` int(10) UNSIGNED NOT NULL,
  `status` enum('None','Blocked','Accepted') NOT NULL DEFAULT 'None',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `user_id`, `friend_id`, `status`, `created_at`) VALUES
(1, 1, 17, 'Blocked', '2025-03-30 05:10:38'),
(2, 1, 18, 'None', '2025-03-30 22:03:02');

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
(2, 1, 'Get Fit', 'Run 10 miles everyweek for a month', 'short-term', '2025-01-01', '2025-02-02', 50, 'failed', '2025-01-01 01:01:00', '2025-02-20 01:57:36'),
(3, 1, 'Study', 'learn Java', 'long-term', '2025-04-10', '2025-05-10', 50, 'in-progress', '2025-01-01 11:11:00', '2025-02-20 05:18:29'),
(4, 2, 'Yoga Class', 'Attend class', 'long-term', '2025-02-28', '2025-03-08', 50, 'failed', '2025-02-27 14:55:00', '2025-02-20 06:54:09'),
(5, 17, 'ytytytytty', 'yt', 'long-term', '2049-12-01', '2049-12-28', 0, 'in-progress', '2025-03-08 22:22:00', '2025-03-08 14:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `groupbannedusers`
--

CREATE TABLE `groupbannedusers` (
  `id` int(11) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `GroupID` int(11) NOT NULL,
  `UserID` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupchat`
--

CREATE TABLE `groupchat` (
  `GroupMessageID` int(11) NOT NULL,
  `GroupMessage` varchar(255) NOT NULL,
  `CreatedTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `GroupID` int(11) NOT NULL,
  `GroupMessageStatus` enum('Seen','Delivered') NOT NULL DEFAULT 'Delivered',
  `GroupStatus` enum('muted','none') NOT NULL DEFAULT 'none',
  `GroupMessageType` enum('TEXT','FILE','VIDEO','AUDIO','IMAGE') DEFAULT 'TEXT',
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupchat`
--

INSERT INTO `groupchat` (`GroupMessageID`, `GroupMessage`, `CreatedTime`, `GroupID`, `GroupMessageStatus`, `GroupStatus`, `GroupMessageType`, `user_id`) VALUES
(1, 'hallo', '2025-03-29 21:27:58', 1, 'Delivered', 'none', 'TEXT', 1),
(2, '233232', '2025-03-29 21:42:51', 1, 'Delivered', 'none', 'TEXT', 17);

-- --------------------------------------------------------

--
-- Table structure for table `groupinfo`
--

CREATE TABLE `groupinfo` (
  `id` int(11) NOT NULL,
  `GroupName` varchar(50) NOT NULL,
  `GroupDesc` varchar(100) NOT NULL,
  `GroupMemberNo` int(11) NOT NULL DEFAULT 0,
  `GroupCreatedTime` datetime NOT NULL DEFAULT current_timestamp(),
  `GroupStatus` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupinfo`
--

INSERT INTO `groupinfo` (`id`, `GroupName`, `GroupDesc`, `GroupMemberNo`, `GroupCreatedTime`, `GroupStatus`) VALUES
(1, 'Sigma_1743283351', 'qwwqwq', 2, '2025-03-30 05:22:31', '');

-- --------------------------------------------------------

--
-- Table structure for table `groupusers`
--

CREATE TABLE `groupusers` (
  `GroupID` int(11) NOT NULL,
  `GroupRole` enum('ADMIN','CO_ADMIN','MEMBER') NOT NULL DEFAULT 'MEMBER',
  `GroupUserStatus` enum('Muted','Banned','None') NOT NULL DEFAULT 'None',
  `CreatedTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `UserID` int(10) UNSIGNED NOT NULL,
  `GroupInfoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupusers`
--

INSERT INTO `groupusers` (`GroupID`, `GroupRole`, `GroupUserStatus`, `CreatedTime`, `UserID`, `GroupInfoID`) VALUES
(2, 'ADMIN', 'None', '2025-03-29 21:22:31', 1, 1),
(4, 'MEMBER', 'None', '2025-03-29 21:22:58', 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_tasks`
--

CREATE TABLE `group_tasks` (
  `id` int(11) UNSIGNED NOT NULL,
  `team_name` varchar(255) NOT NULL,
  `assigned_by` int(10) UNSIGNED NOT NULL,
  `assigned_to` int(10) UNSIGNED NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text NOT NULL,
  `status` enum('pending','in progress','completed') DEFAULT 'pending',
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` date NOT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_tasks`
--

INSERT INTO `group_tasks` (`id`, `team_name`, `assigned_by`, `assigned_to`, `task_name`, `task_description`, `status`, `assigned_at`, `due_date`, `completed_at`) VALUES
(1, 'Team Alpha', 1, 2, 'new task', 'asd', 'pending', '2025-03-24 09:05:06', '2025-03-31', '2025-03-30 09:57:12'),
(2, 'asd', 1, 1, 'Test', 'asd', 'completed', '2025-03-24 09:23:49', '2025-05-02', '2025-03-30 09:57:11');

--
-- Triggers `group_tasks`
--
DELIMITER $$
CREATE TRIGGER `update_group_task_completed_time` BEFORE UPDATE ON `group_tasks` FOR EACH ROW BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        SET NEW.completed_at = NOW();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `sender_id` int(10) UNSIGNED DEFAULT NULL,
  `type` enum('DirectMessage','system','GroupMessage') NOT NULL,
  `notification_message` varchar(255) DEFAULT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message_id`, `sender_id`, `type`, `notification_message`, `status`, `created_at`) VALUES
(1, 17, NULL, NULL, 'system', 'Welcome back! Your account is now active.', 'unread', '2025-03-30 02:13:27'),
(2, 17, NULL, NULL, 'system', 'Welcome back! Your account is now active.', 'unread', '2025-03-30 02:33:56'),
(3, 1, NULL, NULL, 'system', 'Welcome back! Your account is now active.', 'unread', '2025-03-30 11:24:27'),
(4, 24, NULL, NULL, 'system', 'Welcome back! Your account is now active.', 'unread', '2025-03-30 14:11:16');

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
  `end_date` date DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_title`, `task_desc`, `start_date`, `start_time`, `end_time`, `created_at`, `user_id`, `status`, `category`, `end_date`, `completed_at`) VALUES
(4, 'Group Presentation', 'Prepare slides for History project.', '2025-02-26', '16:00:00', '17:30:00', '2025-02-25 13:34:25', 2, 'Timeout', 'Collaboration', '2025-03-16', NULL),
(6, 'Study for Biology Test', 'Memorize cell structures and functions.', '2025-02-27', '19:00:00', '21:00:00', '2025-02-25 13:34:25', 2, 'Timeout', 'Exam Preparation', '2025-03-16', NULL),
(7, 'Weekly Standup Meeting', 'Discuss project updates with team.', '2025-03-02', '09:00:00', '09:45:00', '2025-02-25 13:34:25', 3, 'Timeout', 'Meetings', '2025-03-16', NULL),
(14, 'Employee Feedback Survey', 'Collect responses from team.', '2025-03-09', '12:00:00', '13:30:00', '2025-02-25 13:34:25', 5, 'Timeout', 'HR', '2025-02-28', NULL),
(15, 'Plan Team Retreat', 'Finalize location and agenda.', '2025-03-10', '14:00:00', '15:30:00', '2025-02-25 13:34:25', 5, 'Complete', 'Event Planning', '2025-02-28', NULL),
(68, '111', 'CEHN', '2025-03-02', '13:40:23', '13:40:23', '2025-03-02 12:40:23', 1, 'Complete', '2222', '2025-03-03', '2025-03-24 17:10:12'),
(86, '111', '111', '2025-03-04', '21:26:31', '21:26:32', '2025-03-04 13:26:31', 21, 'Complete', 'chenyee2', '2025-03-04', NULL),
(89, '111', '111', '2025-03-04', '22:20:00', '22:21:00', '2025-03-04 14:20:00', 21, 'Timeout', 'chenyee', '2025-03-04', NULL),
(93, 'asdsad', 'asdskgjshfkjashkjcdhsfkjhsdkfjsdahkfjhds', '2025-03-05', '12:25:12', '12:25:12', '2025-03-05 04:25:12', 21, 'Timeout', 'chenyee', '2025-03-17', NULL),
(95, 'zxczxcxzxzmcnbxzcmzxbcxzcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '2025-03-06', '01:28:14', '01:29:14', '2025-03-05 17:28:14', 21, 'Complete', 'chenyee', '2025-03-06', NULL),
(96, 'zxczxcxzxzmcnbxzcmzxbcxzcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'QQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQ', '2025-03-06', '02:45:12', '03:45:12', '2025-03-05 18:45:12', 21, 'Complete', 'chenyee2', '2025-03-06', NULL),
(98, 'zxczxcxzxzmcnbxzcmzxbcxzcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '2025-03-06', '17:27:30', '17:27:30', '2025-03-06 09:27:30', 18, 'Timeout', '2212121122112', '2025-03-07', NULL),
(109, 'Team Meeting', 'Weekly team meeting', '2025-03-02', '10:00:00', '11:00:00', '2025-03-24 14:14:06', 2, 'Complete', 'Meeting', '2025-03-02', '2025-03-02 11:00:00'),
(110, 'Client Presentation', 'Prepare and present to the client', '2025-03-04', '14:00:00', '16:00:00', '2025-03-24 14:14:06', 3, 'Timeout', 'Work', '2025-03-04', NULL),
(111, 'Fix Website Bugs', 'Resolve reported issues in the system', '2025-03-06', '12:00:00', '17:00:00', '2025-03-24 14:14:06', 4, 'Timeout', 'Development', '2025-03-08', NULL),
(112, 'Database Optimization', 'Improve database performance', '2025-02-25', '08:00:00', '12:00:00', '2025-03-24 14:14:06', 5, 'Timeout', 'Development', '2025-02-27', NULL),
(113, 'Write Blog Post', 'Create content for the company blog', '2025-03-07', '15:00:00', '17:30:00', '2025-03-24 14:14:06', 6, 'Timeout', 'Content', '2025-03-09', NULL),
(114, 'Marketing Strategy Planning', 'Plan the next marketing campaign', '2025-03-03', '13:00:00', '16:00:00', '2025-03-24 14:14:06', 7, 'Complete', 'Marketing', '2025-03-03', '2025-03-03 16:00:00'),
(115, 'Product Launch Prep', 'Finalize launch details for new product', '2025-02-20', '09:00:00', '17:00:00', '2025-03-24 14:14:06', 8, 'Timeout', 'Product', '2025-02-25', NULL),
(124, '2222', NULL, '0000-00-00', '00:00:00', '00:00:00', '2025-03-29 18:41:07', 17, 'Incomplete', '', NULL, NULL),
(125, '333', NULL, '0000-00-00', '00:00:00', '00:00:00', '2025-03-29 18:42:37', 17, 'Incomplete', '', NULL, NULL),
(126, 'zxczxcxzxzmcnbxzcmzxbcxzcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '101021', '2025-03-30', '02:43:15', '02:43:15', '2025-03-29 18:43:15', 17, 'Incomplete', '333', '2025-03-31', NULL),
(127, 'zxczxcxzxzmcnbxzcmzxbcxzcAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'qwq', '2025-03-30', '02:43:45', '03:43:45', '2025-03-29 18:43:45', 17, 'Timeout', '333', '2025-03-30', NULL),
(143, '333', NULL, '0000-00-00', '00:00:00', '00:00:00', '2025-03-30 13:42:27', 1, 'Incomplete', '', NULL, NULL),
(144, '111', 'Hi', '2025-03-30', '21:43:05', '21:43:05', '2025-03-30 13:43:05', 1, 'Incomplete', '2222', '2025-03-31', NULL);

--
-- Triggers `tasks`
--
DELIMITER $$
CREATE TRIGGER `update_completed_time` BEFORE UPDATE ON `tasks` FOR EACH ROW BEGIN
    IF NEW.status = 'Complete' AND OLD.status != 'Complete' THEN
        SET NEW.completed_at = NOW();
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_task_status_timeout` BEFORE UPDATE ON `tasks` FOR EACH ROW BEGIN
    IF NEW.end_date < NOW() AND NEW.status != 'completed' THEN
        SET NEW.status = 'Timeout';
    END IF;
END
$$
DELIMITER ;

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
(23, 'Team Omega', 5, 6, '2025-02-27 02:16:06'),
(24, '1234', 17, 17, '2025-03-08 06:41:48'),
(30, 'asd', 1, 1, '2025-03-24 08:18:51');

-- --------------------------------------------------------

--
-- Table structure for table `userblocks`
--

CREATE TABLE `userblocks` (
  `BlockID` int(11) NOT NULL,
  `blocker_id` int(10) UNSIGNED NOT NULL,
  `blocked_id` int(10) UNSIGNED NOT NULL,
  `Created_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `last_login` timestamp NOT NULL DEFAULT current_timestamp(),
  `auth_token` varchar(255) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `UserStatus` enum('Active','Inactive','Suspended') NOT NULL DEFAULT 'Active',
  `suspension_end` datetime DEFAULT NULL,
  `suspension_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `usertype`, `last_login`, `auth_token`, `token_expires`, `UserStatus`, `suspension_end`, `suspension_reason`) VALUES
(1, 'John Doe', 'poopie@gmail.com', '$2y$10$PpJj7CANH.fQPNhlreEcGefr1axPIXHJPgje5kF9qRPa8ql0VaBxO', '2025-02-17 01:11:39', 0, '2025-03-30 14:12:26', '191f26c202933940b01f0d38b207ccc86b23951e09d46c11', '2025-04-29 22:06:09', 'Active', NULL, NULL),
(2, 'Jane Smith', 'janesmith@example.com', 'J@ne2024!', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59', NULL, NULL, '', NULL, NULL),
(3, 'Michael Brown', 'michaelbrown@example.com', 'M!ke7890', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59', NULL, NULL, '', NULL, NULL),
(4, 'Sarah Lee', 'sarahlee@example.com', 'S@rah2024#', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59', NULL, NULL, '', NULL, NULL),
(5, 'David Clark', 'davidclark@example.com', 'D@vid2024$', '2025-02-17 01:11:39', 0, '2025-02-17 06:21:59', NULL, NULL, '', NULL, NULL),
(6, 'alex_walker', 'alex.walker@example.com', 'password6', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, 'Inactive', '2025-03-30 00:18:07', ''),
(7, 'sophia_martin', 'sophia.martin@example.com', 'password7', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, 'Inactive', '2025-03-30 00:18:11', ''),
(8, 'daniel_ross', 'daniel.ross@example.com', 'password8', '2025-01-20 08:50:23', 0, '2025-03-23 08:50:23', NULL, NULL, 'Inactive', NULL, NULL),
(9, 'olivia_clark', 'olivia.clark@example.com', 'password9', '2025-02-20 08:50:23', 0, '2025-03-24 08:50:23', NULL, NULL, 'Inactive', NULL, NULL),
(10, 'liam_jackson', 'liam.jackson@example.com', 'password10', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, '', NULL, NULL),
(11, 'emma_white', 'emma.white@example.com', 'password11', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, '', NULL, NULL),
(12, 'mason_harris', 'mason.harris@example.com', 'password12', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, '', NULL, NULL),
(13, 'ava_mitchell', 'ava.mitchell@example.com', 'password13', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, '', NULL, NULL),
(14, 'james_anderson', 'james.anderson@example.com', 'password14', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, '', NULL, NULL),
(15, 'mia taylor', 'mia.taylor@example.com', 'password15', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, '', NULL, NULL),
(16, 'ethan_brown', 'ethan.brown@example.com', 'password16', '2025-02-20 08:50:23', 0, '2025-02-20 08:50:23', NULL, NULL, '', NULL, NULL),
(17, 'test', 'test@gmail.com', '$2y$10$OR6hXE2j5g6gTuFBKyLoJOiB5k0Glyqf1kgHhzqNSheDT8Y2k2Gs.', '2025-02-17 13:20:03', 2, '2025-03-30 02:33:56', '07048944dc0d0482902b2f6392de9c785a17c69f9c4d614f', '2025-04-29 10:34:05', 'Inactive', NULL, NULL),
(18, 'Yee', 'Nig@gmail.com', '$2y$10$F/.tbsbJOOK1ODoQGhYUAOPLKH4kmgW2e.X46rOoyJR/aWXl0KryG', '2025-03-03 15:46:03', 0, '2025-03-03 15:46:03', '5c5078cbe522a3e39cf0951d37a86839e4232c8c7ea2675f', '2025-04-05 17:09:40', 'Inactive', NULL, NULL),
(19, 'www', 'Ni@gmail.com', '$2y$10$gO144t7DGZm2ACSw80HHFeE5OWwfqOAwi8eLrjS30Yp6anAyvlB3i', '2025-03-03 15:55:45', 0, '2025-03-03 15:55:45', '4126a9fbe64562c304b75d6c10b7a96677ba42920f870a0a', '2025-04-29 00:21:07', 'Inactive', NULL, NULL),
(20, '', 'lol@gmail.com', '$2y$10$zgdFk0MJIu8Q1M8tEchu9e45S3HiSu5RnqxYSwVcJFSsibQ4z147S', '2025-03-03 16:02:23', 0, '2025-03-03 16:02:23', NULL, NULL, '', NULL, NULL),
(21, 'www', 'loltian8112@gmail.com', '$2y$10$S0Tw64FW1i/X6pTLBj//peUMeFTqmOdargIKXsgQp/1TXG5pMV966', '2025-03-03 18:21:08', 0, '2025-03-03 18:21:08', '237a0047c7d8d70571c0c9d4bf1defedb36b931794cae731', '2025-04-05 15:52:03', 'Inactive', NULL, NULL),
(22, 'test', 'asd@gmail.com', '$2y$10$5jYUnNCpbAz87/tRJ6RDku9VHJwj.Yrks9EL/6033H/cDvR4S5eRq', '2025-03-24 07:44:58', 0, '2025-01-22 07:44:58', '5652e4daa190d822bd759b219206fdb79b72dbccd5365c38', '2025-04-23 15:45:04', 'Inactive', '2025-03-30 00:18:01', ''),
(23, 'user1asd', 'aasdsadd@gmail.com', '$2y$10$WryLQd5vgsXuwT5jpTnFSe7ACmw5JCxCTenf3b.TdJpVxMyL/wOFu', '2025-03-24 11:52:38', 0, '2025-03-24 11:52:38', NULL, NULL, 'Inactive', NULL, ''),
(24, 'Admin', 'admin@focusflow.com', '$2y$10$LtNJs4M4XQzontpcMhwfQ.xrXq9N9mXysMxflSZyTPw85MNNVf8cq', '2025-03-29 15:38:53', 1, '2025-03-30 14:11:44', '770d54be8d2d848bd9499b4084f5e2cf5b7e4d49ecaac6a3', '2025-04-29 22:11:16', 'Active', NULL, NULL),
(25, 'ChenAlex', 'c@gmail.com', '$2y$10$Z.4rHhLgkWqoY.OSSHOU3OReXsroUmBIRUe3SdrL13Ao9sW0CwLim', '2025-03-30 13:58:16', 0, '2025-03-30 13:58:16', NULL, NULL, 'Inactive', NULL, NULL);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `activate_user_on_login` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    -- Check if the user is currently inactive and is logging in within 1 month
    IF OLD.UserStatus = 'Inactive' AND NEW.last_login >= NOW() - INTERVAL 1 MONTH THEN
        -- Set the status to Active
        SET NEW.UserStatus = 'Active';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_user_update` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF NEW.last_login < NOW() - INTERVAL 1 MONTH THEN
        SET NEW.UserStatus = 'Inactive';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `welcome_back_notification` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF OLD.UserStatus = 'Inactive' AND NEW.last_login >= NOW() - INTERVAL 1 DAY THEN

        SET NEW.UserStatus = 'Active';


        INSERT INTO notifications (user_id,type ,notification_message, created_at)
        VALUES (NEW.id, 'system','Welcome back! Your account is now active.', NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_log`
--

CREATE TABLE `user_activity_log` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Login','Logout','Signup') NOT NULL,
  `result` enum('Success','Fail') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity_log`
--

INSERT INTO `user_activity_log` (`log_id`, `user_id`, `timestamp`, `status`, `result`) VALUES
(1, 1, '2025-03-24 11:37:48', 'Login', 'Success'),
(2, 1, '2025-03-24 11:37:59', 'Login', 'Fail'),
(3, 1, '2025-03-24 11:42:57', 'Login', 'Success'),
(4, 1, '2025-03-24 11:45:37', 'Login', 'Success'),
(5, 1, '2025-03-24 11:48:07', 'Logout', 'Success'),
(6, 1, '2025-03-24 11:48:18', 'Login', 'Success'),
(7, 1, '2025-03-24 11:48:20', 'Logout', 'Success'),
(8, 23, '2025-03-24 11:52:38', 'Signup', 'Success'),
(9, 1, '2025-03-26 06:11:48', 'Login', 'Fail'),
(10, 1, '2025-03-26 06:13:18', 'Login', 'Success'),
(11, 1, '2025-03-27 11:33:45', 'Login', 'Success'),
(12, 1, '2025-03-27 18:33:09', 'Login', 'Success'),
(13, 1, '2025-03-28 12:40:28', 'Login', 'Success'),
(14, 1, '2025-03-29 08:20:51', 'Login', 'Success'),
(15, 1, '2025-03-29 15:49:16', 'Logout', 'Success'),
(16, 24, '2025-03-29 15:55:21', 'Logout', 'Success'),
(17, 24, '2025-03-29 15:59:59', 'Logout', 'Success'),
(18, 1, '2025-03-29 16:13:44', 'Logout', 'Success'),
(19, 19, '2025-03-29 16:15:14', 'Logout', 'Success'),
(20, 19, '2025-03-29 16:20:44', 'Logout', 'Success'),
(21, 19, '2025-03-29 16:21:11', 'Logout', 'Success'),
(22, 17, '2025-03-29 21:50:36', 'Logout', 'Success'),
(23, 17, '2025-03-30 02:13:27', 'Logout', 'Success'),
(24, 17, '2025-03-30 02:33:56', 'Logout', 'Success'),
(25, 1, '2025-03-30 11:24:27', 'Logout', 'Success'),
(26, 1, '2025-03-30 13:46:26', 'Logout', 'Success'),
(27, 25, '2025-03-30 13:58:16', 'Signup', 'Success'),
(28, 1, '2025-03-30 14:05:30', 'Logout', 'Success');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CONTACTLISTID` (`ContactListID`),
  ADD KEY `FRIEND_ID` (`FriendID`);

--
-- Indexes for table `contactlist`
--
ALTER TABLE `contactlist`
  ADD PRIMARY KEY (`ContactID`);

--
-- Indexes for table `directmessage`
--
ALTER TABLE `directmessage`
  ADD PRIMARY KEY (`DirectMessageID`),
  ADD KEY `RelationID` (`FriendID`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friendrequests`
--
ALTER TABLE `friendrequests`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `sender_id` (`sender_id`,`receiver_id`),
  ADD KEY `ReceiverID` (`receiver_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `groupbannedusers`
--
ALTER TABLE `groupbannedusers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `BannedUser` (`UserID`),
  ADD KEY `BannedUserGroup` (`GroupID`);

--
-- Indexes for table `groupchat`
--
ALTER TABLE `groupchat`
  ADD PRIMARY KEY (`GroupMessageID`),
  ADD KEY `GroupId` (`GroupID`),
  ADD KEY `USER_ID_FK` (`user_id`);

--
-- Indexes for table `groupinfo`
--
ALTER TABLE `groupinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupusers`
--
ALTER TABLE `groupusers`
  ADD PRIMARY KEY (`GroupID`),
  ADD KEY `UserIdForeignKey` (`UserID`),
  ADD KEY `GroupInfo_ID` (`GroupInfoID`);

--
-- Indexes for table `group_tasks`
--
ALTER TABLE `group_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_by` (`assigned_by`),
  ADD KEY `assigned_to` (`assigned_to`);

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
-- Indexes for table `userblocks`
--
ALTER TABLE `userblocks`
  ADD PRIMARY KEY (`BlockID`),
  ADD KEY `BlockerID` (`blocker_id`),
  ADD KEY `BlockedID` (`blocked_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_auth_token` (`auth_token`);

--
-- Indexes for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contactlist`
--
ALTER TABLE `contactlist`
  MODIFY `ContactID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `directmessage`
--
ALTER TABLE `directmessage`
  MODIFY `DirectMessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `friendrequests`
--
ALTER TABLE `friendrequests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `groupbannedusers`
--
ALTER TABLE `groupbannedusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groupchat`
--
ALTER TABLE `groupchat`
  MODIFY `GroupMessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `groupinfo`
--
ALTER TABLE `groupinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `groupusers`
--
ALTER TABLE `groupusers`
  MODIFY `GroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `group_tasks`
--
ALTER TABLE `group_tasks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `userblocks`
--
ALTER TABLE `userblocks`
  MODIFY `BlockID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `CONTACTLISTID` FOREIGN KEY (`ContactListID`) REFERENCES `contactlist` (`ContactID`),
  ADD CONSTRAINT `FRIEND_ID` FOREIGN KEY (`FriendID`) REFERENCES `friends` (`id`);

--
-- Constraints for table `directmessage`
--
ALTER TABLE `directmessage`
  ADD CONSTRAINT `RelationID` FOREIGN KEY (`FriendID`) REFERENCES `friends` (`id`);

--
-- Constraints for table `friendrequests`
--
ALTER TABLE `friendrequests`
  ADD CONSTRAINT `ReceiverID` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `SenderID` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `groupbannedusers`
--
ALTER TABLE `groupbannedusers`
  ADD CONSTRAINT `BannedUser` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `BannedUserGroup` FOREIGN KEY (`GroupID`) REFERENCES `groupinfo` (`id`);

--
-- Constraints for table `groupchat`
--
ALTER TABLE `groupchat`
  ADD CONSTRAINT `GroupId` FOREIGN KEY (`GroupID`) REFERENCES `groupinfo` (`id`),
  ADD CONSTRAINT `USER_ID_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `groupusers`
--
ALTER TABLE `groupusers`
  ADD CONSTRAINT `GroupInfo_ID` FOREIGN KEY (`GroupInfoID`) REFERENCES `groupinfo` (`id`),
  ADD CONSTRAINT `UserIdForeignKey` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Constraints for table `group_tasks`
--
ALTER TABLE `group_tasks`
  ADD CONSTRAINT `group_tasks_ibfk_1` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notificationsForDirectMessageID` FOREIGN KEY (`message_id`) REFERENCES `directmessage` (`DirectMessageID`),
  ADD CONSTRAINT `notificationsForGroupMessageID` FOREIGN KEY (`message_id`) REFERENCES `groupchat` (`GroupMessageID`),
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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

--
-- Constraints for table `userblocks`
--
ALTER TABLE `userblocks`
  ADD CONSTRAINT `BlockedID` FOREIGN KEY (`blocked_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `BlockerID` FOREIGN KEY (`blocker_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD CONSTRAINT `user_activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
