-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Nov 05, 2024 at 03:21 AM
-- Server version: 8.0.40
-- PHP Version: 8.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `messageboards`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `sender_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `recipient_id`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Hello Renza!', '2024-10-31 10:39:42', '2024-10-31 10:39:42'),
(2, 2, 1, 'Hello, How is your day?  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam est dolor, scelerisque nec augue vitae, congue tristique augue. Aliquam sed neque consectetur, tristique libero at,  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam est dolor, scelerisque nec augue vitae, congue tristique augue. Aliquam sed neque consectetur, tristique libero at,', '2024-10-31 10:40:39', '2024-10-31 10:40:39'),
(5, 1, 2, 'What is that?', '2024-11-04 10:22:50', '2024-11-04 10:22:50'),
(6, 1, 3, 'Hai! Raffy.', '2024-11-04 14:57:13', '2024-11-04 14:57:13'),
(7, 3, 1, 'Hello Renzo', '2024-11-04 14:58:59', '2024-11-04 14:58:59'),
(8, 2, 1, 'Its about Lorem ipum. Please read it.', '2024-11-04 17:14:40', '2024-11-04 17:14:40'),
(9, 1, 2, 'Ah i see okay.', '2024-11-04 17:17:03', '2024-11-04 17:17:03'),
(10, 2, 1, 'Tell me what you understand later.', '2024-11-04 17:17:37', '2024-11-04 17:17:37'),
(11, 1, 2, 'Okay, i will read it.', '2024-11-04 17:18:12', '2024-11-04 17:18:12'),
(12, 1, 2, 'Can you send more.', '2024-11-04 17:23:14', '2024-11-04 17:23:14'),
(13, 2, 1, 'Yes, later i will send more.', '2024-11-04 17:26:53', '2024-11-04 17:26:53'),
(14, 1, 2, 'Thank you.', '2024-11-05 09:30:11', '2024-11-05 09:30:11'),
(15, 2, 1, 'No problem.', '2024-11-05 09:30:41', '2024-11-05 09:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `profile_url` varchar(100) DEFAULT NULL,
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hubby` text,
  `last_login_time` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created_ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `modified_ip` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `profile`, `profile_url`, `name`, `gender`, `birthdate`, `email`, `password`, `hubby`, `last_login_time`, `created`, `modified`, `created_ip`, `modified_ip`) VALUES
(1, 'assets/uploads/17.avif', 'http://localhost/cakephp/assets/uploads/17.avif', 'Renzo Bancud', 'Male', '1996-02-06', 'zo@gmail.com', '$2a$10$Ljod8cpEqqPfxyqGT4cjUe.JY4AT7x/yA57cvy/f7myeJqAW0Hl9S', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam est dolor, scelerisque nec augue vitae, congue tristique augue. Aliquam sed neque consectetur, tristique libero at, laoreet arcu. Fusce faucibus quis risus eget interdum. Duis suscipit justo at venenatis rhoncus. Duis egestas posuere scelerisque. Nulla maximus nulla egestas est aliquet malesuada. Nunc sed erat nec nunc tempus elementum. Vestibulum ut iaculis massa, ut placerat purus. Nam facilisis ante vel congue elementum. Praesent ipsum elit, cursus sit amet leo vel, venenatis posuere velit. Donec eros nulla, rutrum molestie pretium vitae, scelerisque dictum nulla.', '2024-11-05 10:50:44', '2024-11-04 15:14:56', '2024-11-05 10:50:44', '192.168.65.1', '192.168.65.1'),
(2, 'assets/uploads/13.avif', 'http://localhost/cakephp/assets/uploads/13.avif', 'Renza Bancud', 'Female', '1996-02-06', 'za@gmail.com', '$2a$10$FI7ddef18C6FzuJ5c6I51uTfZsF668.7KbSprxaxqXTi8/ZzDs8MO', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam est dolor, scelerisque nec augue vitae, congue tristique augue. Aliquam sed neque consectetur, tristique libero at, laoreet arcu. Fusce faucibus quis risus eget interdum. Duis suscipit justo at venenatis rhoncus. Duis egestas posuere scelerisque. Nulla maximus nulla egestas est aliquet malesuada. Nunc sed erat nec nunc tempus elementum. Vestibulum ut iaculis massa, ut placerat purus. Nam facilisis ante vel congue elementum. Praesent ipsum elit, cursus sit amet leo vel, venenatis posuere velit. Donec eros nulla, rutrum molestie pretium vitae, scelerisque dictum nulla.', '2024-11-05 11:21:05', '2024-10-31 11:12:15', '2024-11-05 11:21:05', '192.168.65.1', '192.168.65.1'),
(3, 'assets/uploads/16.avif', 'http://localhost/cakephp/assets/uploads/16.avif', 'Raffy Bancud', 'Male', '1996-02-06', 'raffy@gmail.com', '$2a$10$FI7ddef18C6FzuJ5c6I51uTfZsF668.7KbSprxaxqXTi8/ZzDs8MO', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam est dolor, scelerisque nec augue vitae, congue tristique augue. Aliquam sed neque consectetur, tristique libero at, laoreet arcu. Fusce faucibus quis risus eget interdum. Duis suscipit justo at venenatis rhoncus. Duis egestas posuere scelerisque. Nulla maximus nulla egestas est aliquet malesuada. Nunc sed erat nec nunc tempus elementum. Vestibulum ut iaculis massa, ut placerat purus. Nam facilisis ante vel congue elementum. Praesent ipsum elit, cursus sit amet leo vel, venenatis posuere velit. Donec eros nulla, rutrum molestie pretium vitae, scelerisque dictum nulla.', '2024-11-04 14:58:46', '2024-10-31 13:04:55', '2024-11-04 14:58:46', '192.168.65.1', '192.168.65.1'),
(4, 'assets/uploads/12.avif', 'http://localhost/cakephp/assets/uploads/12.avif', 'Roxy Bancud', 'Female', '2006-11-08', 'roxy@gmail.com', '$2a$10$RLQCd5BYRnxPoiJ92NuPc.eGRm3vixTbUHkc0tf6IooxMBlE2mrAu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam est dolor, scelerisque nec augue vitae, congue tristique augue. Aliquam sed neque consectetur, tristique libero at, laoreet arcu. Fusce faucibus quis risus eget interdum. Duis suscipit justo at venenatis rhoncus. Duis egestas posuere scelerisque. Nulla maximus nulla egestas est aliquet malesuada. Nunc sed erat nec nunc tempus elementum. Vestibulum ut iaculis massa, ut placerat purus. Nam facilisis ante vel congue elementum. Praesent ipsum elit, cursus sit amet leo vel, venenatis posuere velit. Donec eros nulla, rutrum molestie pretium vitae, scelerisque dictum nulla.', '2024-10-31 13:16:09', '2024-10-31 13:17:10', '2024-10-31 13:17:10', '192.168.65.1', '192.168.65.1'),
(5, 'assets/uploads/7.avif', 'http://localhost/cakephp/assets/uploads/7.avif', 'Rona Bancud', 'Female', '1980-10-15', 'rona@gmail.com', '$2a$10$4B5mUA1SqOtusvY4XGUigOZ6ZpXPy/./e0GWOpDvYdz7u0GP47wli', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam est dolor, scelerisque nec augue vitae, congue tristique augue. Aliquam sed neque consectetur, tristique libero at, laoreet arcu. Fusce faucibus quis risus eget interdum. Duis suscipit justo at venenatis rhoncus. Duis egestas posuere scelerisque. Nulla maximus nulla egestas est aliquet malesuada. Nunc sed erat nec nunc tempus elementum. Vestibulum ut iaculis massa, ut placerat purus. Nam facilisis ante vel congue elementum. Praesent ipsum elit, cursus sit amet leo vel, venenatis posuere velit. Donec eros nulla, rutrum molestie pretium vitae, scelerisque dictum nulla.', '2024-11-04 10:49:25', '2024-10-31 13:36:37', '2024-11-04 10:49:25', '192.168.65.1', '192.168.65.1'),
(6, NULL, NULL, 'Sofronio Bancud', NULL, NULL, 'ronnie@gmail.com', '$2a$10$MEcN/89VaIKFg2ZKMrYWyeRXb5lm.Fcz17LP7I3EoApB32rbe4MlG', NULL, '2024-11-04 09:13:45', '2024-11-04 09:13:45', '2024-11-04 09:13:45', '192.168.65.1', '192.168.65.1'),
(7, NULL, NULL, 'Edna Bancud', NULL, NULL, 'edna@gmail.com', '$2a$10$jvXdgMm/gcysj55JaJ42mOIdbpcg190rb49QUoWRWO2jSgC7zCsdy', NULL, '2024-11-04 09:14:47', '2024-11-04 09:14:47', '2024-11-04 09:14:47', '192.168.65.1', '192.168.65.1'),
(8, NULL, NULL, 'Kevin Galicia', NULL, NULL, 'kevin@gmail.com', '$2a$10$CZcF20wgashq2MQDTfBpMe33AGT301lK9eOKxrXMo05nAijq0TpC6', NULL, '2024-11-04 10:25:13', '2024-11-04 10:25:13', '2024-11-04 10:25:13', '192.168.65.1', '192.168.65.1'),
(9, NULL, NULL, 'Laila Cabrera', NULL, NULL, 'laila@gmail.com', '$2a$10$d4RUbAS44NT1TiYsZnsYJuqXKZ/CAQ2O13l3FXRfoaWh5EnNYTxr2', NULL, '2024-11-04 10:50:29', '2024-11-04 10:50:29', '2024-11-04 10:50:29', '192.168.65.1', '192.168.65.1'),
(10, NULL, NULL, 'Liza Cabrera', NULL, NULL, 'liza@gmail.com', '$2a$10$RGHe.e/7dCC70DplXyWrsun1z05KuKm5GvujHCOcon4C9ZVsoUciS', NULL, '2024-11-04 10:51:04', '2024-11-04 10:51:04', '2024-11-04 10:51:04', '192.168.65.1', '192.168.65.1'),
(11, NULL, NULL, 'Jaylord Cabrera', NULL, NULL, 'jaylord@gmail.com', '$2a$10$3fKBs3j3/G0QeKDRP/rmEe/N3ccKqLPmPbkD8/99CPjZ7ImF1Y19e', NULL, '2024-11-04 10:51:45', '2024-11-04 10:51:45', '2024-11-04 10:51:45', '192.168.65.1', '192.168.65.1'),
(12, NULL, NULL, 'Valerie Bancud', NULL, NULL, 'valerie@gmail.com', '$2a$10$nt2K8kGxOZyWWiW2O1BH4.wal7.Y3QjLkh.ssHail0.r7S9A/mUv6', NULL, '2024-11-04 10:52:21', '2024-11-04 10:52:21', '2024-11-04 10:52:21', '192.168.65.1', '192.168.65.1'),
(14, NULL, NULL, 'Soffy Cabrera', NULL, NULL, 'soffy@gmail.com', '$2a$10$2CFqCn5eCDt5hbq8J8w4TekN4DZ6JH6ZBlnxL36XxEkECiMwERPTK', NULL, '2024-11-04 16:57:24', '2024-11-04 16:57:24', '2024-11-04 16:57:24', '192.168.65.1', '192.168.65.1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_message` (`message`(100));

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
