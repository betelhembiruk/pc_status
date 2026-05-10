-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 10:59 PM
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
-- Database: `pc_status`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `serialNumber` varchar(100) DEFAULT NULL,
  `tagNumber` varchar(100) DEFAULT NULL,
  `pcModel` varchar(100) DEFAULT NULL,
  `hardwareType` varchar(100) DEFAULT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `priority` enum('Low','Medium','High','Critical') DEFAULT 'Medium',
  `issue` text DEFAULT NULL,
  `resolution` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone` varchar(30) DEFAULT NULL,
  `broughtBy` varchar(100) DEFAULT NULL,
  `slaDays` int(11) DEFAULT 3,
  `returnedBy` varchar(100) DEFAULT NULL,
  `returnedPerson` varchar(100) DEFAULT NULL,
  `maintenanceType` varchar(255) DEFAULT NULL,
  `maintenanceNotes` text DEFAULT NULL,
  `maintenanceReasonNotDone` text DEFAULT NULL,
  `returned_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `serialNumber`, `tagNumber`, `pcModel`, `hardwareType`, `branch`, `status`, `priority`, `issue`, `resolution`, `assigned_to`, `created_by`, `due_date`, `created_at`, `updated_at`, `phone`, `broughtBy`, `slaDays`, `returnedBy`, `returnedPerson`, `maintenanceType`, `maintenanceNotes`, `maintenanceReasonNotDone`, `returned_at`) VALUES
(1, '', '', '', 'Other', '', 'Pending', 'Medium', '', NULL, NULL, 1, NULL, '2026-05-10 13:27:36', '2026-05-10 18:57:25', '', '', 3, '', '', '', '', '', NULL),
(2, '', '', '', 'Printer', '', 'Closed', 'Medium', '', NULL, NULL, 1, NULL, '2026-05-10 13:35:26', '2026-05-10 19:18:25', '', '', 3, '', '', '', '', '', '2026-05-10 22:18:25'),
(3, 'sn', 'tg', 'hp', 'Scanner', 'Abiy Branch', 'Active', 'Medium', 'not', NULL, NULL, 1, NULL, '2026-05-10 15:16:03', '2026-05-10 19:32:10', '0939059500', 'Branch Staff', 3, '', '', '', '', '', NULL),
(4, 'sn', 'tg', 'hp', 'Printer', 'Ferensay Legasion Branch', 'Pending', 'Medium', 'not', NULL, NULL, 1, NULL, '2026-05-10 15:23:27', '2026-05-10 19:12:36', '0939059500', 'IT Department', 3, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'yb', 'tg', 'hp', 'PC', 'Finfine Branch', 'Pending', 'Medium', 'not', NULL, NULL, 1, NULL, '2026-05-10 15:56:53', '2026-05-10 19:06:12', '0939059500', 'IT Department', 3, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'sn-sn', 'tg', 'hp', 'Scanner', 'Dejach Wube Branch', 'Pending', 'Medium', 'not', NULL, 4, 1, NULL, '2026-05-10 16:04:13', '2026-05-10 20:07:49', '0939059500', 'IT Department', 3, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'sn', 'tg', 'hp', 'Printer', 'Arat Kilo Branch', 'Closed', 'Medium', 'not', NULL, 4, 1, NULL, '2026-05-10 16:09:45', '2026-05-10 20:19:58', '0939059500', 'Branch Staff', 3, NULL, NULL, NULL, NULL, NULL, '2026-05-10 23:19:58'),
(8, 'sn', 'tg', 'hp', 'Laptop', 'Finfine Branch', 'Closed', 'Medium', 'not', NULL, 4, 1, NULL, '2026-05-10 16:23:04', '2026-05-10 20:27:04', '0939059500', 'IT Department', 3, NULL, NULL, NULL, NULL, NULL, '2026-05-10 23:27:04'),
(9, 'sn', 'tg', 'hp', 'Laptop', 'Filwuha Branch', 'Pending', 'Medium', 'not', NULL, 7, 4, NULL, '2026-05-10 19:57:00', '2026-05-10 20:10:46', '0939059500', 'IT Department', 3, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'sn', 'tg', 'hp', 'Scanner', 'Gedam Sefer Branch', 'Closed', 'Medium', 'not', NULL, 1, 1, NULL, '2026-05-10 20:21:05', '2026-05-10 20:42:08', '0939059500', 'IT Department', 3, NULL, NULL, NULL, NULL, NULL, '2026-05-10 23:42:08'),
(11, 'sn', 'tg', 'hp', 'Laptop', 'Tilahun Abay Branch', 'Active', 'Medium', 'not', NULL, 1, 4, NULL, '2026-05-10 20:22:05', '2026-05-10 20:54:30', '0939059500', 'IT Department', 3, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_files`
--

CREATE TABLE `ticket_files` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `must_change_password` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `password`, `role`, `created_at`, `must_change_password`, `last_login`, `status`) VALUES
(1, 'Super Admin', '1234567', 'super_admin', '2026-05-10 11:38:32', 0, '2026-05-10 23:33:20', 'active'),
(4, 'henok', '1234567', 'user', '2026-05-10 17:23:47', 0, '2026-05-10 23:21:51', 'active'),
(6, 'mahlet', '123456', 'user', '2026-05-10 18:05:06', 1, NULL, 'active'),
(7, 'betty', '1234567', 'admin', '2026-05-10 20:05:51', 0, '2026-05-10 23:06:56', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `fk_assigned_user` (`assigned_to`);

--
-- Indexes for table `ticket_files`
--
ALTER TABLE `ticket_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ticket_files`
--
ALTER TABLE `ticket_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_assigned_user` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
