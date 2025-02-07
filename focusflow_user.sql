-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2025 at 03:40 PM
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
-- Database: `focusflow_user`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity logs`
--

CREATE TABLE `activity logs` (
  `ACTLOG_ID` int(11) NOT NULL,
  `ACTION` varchar(500) DEFAULT NULL,
  `TIMESTAMP` datetime NOT NULL DEFAULT current_timestamp(),
  `ADMIN_ID` int(11) DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `ADMIN_ID` int(11) NOT NULL,
  `USERNAME_ADMIN` varchar(100) DEFAULT NULL,
  `EMAIL_ADMIN` varchar(100) DEFAULT NULL,
  `PASSWORD_ADMIN` varchar(255) DEFAULT NULL,
  `ROLE_ADMIN` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CATEGORY_ID` int(11) NOT NULL,
  `CATEGORY_NAME` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `COMMENT_ID` int(11) NOT NULL,
  `COMMENT` varchar(500) DEFAULT NULL,
  `TASK_ID` int(11) DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `FILE_ID` int(11) NOT NULL,
  `TASK_ID` int(11) DEFAULT NULL,
  `FILE_PATH` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goal`
--

CREATE TABLE `goal` (
  `GOAL_ID` int(11) NOT NULL,
  `GOAL_NAME` varchar(255) DEFAULT NULL,
  `DESCRIPTION` varchar(500) DEFAULT NULL,
  `PROGRESS` float NOT NULL DEFAULT 0,
  `STATUS` varchar(100) DEFAULT NULL,
  `DEADLINE` datetime DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `PROJECT_ID` int(11) NOT NULL,
  `PROJECT_NAME` varchar(255) DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `TASK_ID` int(11) NOT NULL,
  `TASK_NAME` varchar(255) DEFAULT NULL,
  `DESCRIPTION` varchar(500) DEFAULT NULL,
  `DEADLINE` datetime DEFAULT NULL,
  `PRIORITY` int(11) NOT NULL DEFAULT 0,
  `PROGRESS` float NOT NULL DEFAULT 0,
  `CATEGORY_ID` int(11) DEFAULT NULL,
  `PROJECT_ID` int(11) DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time`
--

CREATE TABLE `time` (
  `TIME_ID` int(11) NOT NULL,
  `START_TIME` datetime DEFAULT NULL,
  `END_TIME` datetime DEFAULT NULL,
  `TASK_ID` int(11) DEFAULT NULL,
  `USER_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `USER_ID` int(11) NOT NULL,
  `USERNAME_USER` int(100) DEFAULT NULL,
  `EMAIL_USER` int(100) DEFAULT NULL,
  `PASSWORD_USER` int(255) DEFAULT NULL,
  `ADMIN_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity logs`
--
ALTER TABLE `activity logs`
  ADD PRIMARY KEY (`ACTLOG_ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `ADMIN_ID` (`ADMIN_ID`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`ADMIN_ID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CATEGORY_ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`COMMENT_ID`),
  ADD KEY `TASK_ID` (`TASK_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`FILE_ID`),
  ADD KEY `TASK_ID` (`TASK_ID`);

--
-- Indexes for table `goal`
--
ALTER TABLE `goal`
  ADD PRIMARY KEY (`GOAL_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`PROJECT_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`TASK_ID`),
  ADD KEY `CATEGORY_ID` (`CATEGORY_ID`),
  ADD KEY `PROJECT_ID` (`PROJECT_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `time`
--
ALTER TABLE `time`
  ADD PRIMARY KEY (`TIME_ID`),
  ADD KEY `TASK_ID` (`TASK_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`),
  ADD KEY `ADMIN_ID` (`ADMIN_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity logs`
--
ALTER TABLE `activity logs`
  MODIFY `ACTLOG_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `ADMIN_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CATEGORY_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `COMMENT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `FILE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goal`
--
ALTER TABLE `goal`
  MODIFY `GOAL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `PROJECT_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `TASK_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time`
--
ALTER TABLE `time`
  MODIFY `TIME_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity logs`
--
ALTER TABLE `activity logs`
  ADD CONSTRAINT `activity logs_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`),
  ADD CONSTRAINT `activity logs_ibfk_2` FOREIGN KEY (`ADMIN_ID`) REFERENCES `admins` (`ADMIN_ID`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`TASK_ID`) REFERENCES `tasks` (`TASK_ID`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`TASK_ID`) REFERENCES `tasks` (`TASK_ID`);

--
-- Constraints for table `goal`
--
ALTER TABLE `goal`
  ADD CONSTRAINT `goal_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `categories` (`CATEGORY_ID`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`PROJECT_ID`) REFERENCES `projects` (`PROJECT_ID`),
  ADD CONSTRAINT `tasks_ibfk_3` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `time`
--
ALTER TABLE `time`
  ADD CONSTRAINT `time_ibfk_1` FOREIGN KEY (`TASK_ID`) REFERENCES `tasks` (`TASK_ID`),
  ADD CONSTRAINT `time_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`ADMIN_ID`) REFERENCES `admins` (`ADMIN_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
