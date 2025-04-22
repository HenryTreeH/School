-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 05:16 PM
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
-- Database: `scraped_data`
--
CREATE DATABASE IF NOT EXISTS `scraped_data` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `scraped_data`;

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250327101748', '2025-03-27 11:17:58', 65),
('DoctrineMigrations\\Version20250327140008', '2025-03-27 15:01:09', 33),
('DoctrineMigrations\\Version20250327140443', '2025-03-27 15:04:48', 10),
('DoctrineMigrations\\Version20250328131053', '2025-03-28 14:11:16', 52),
('DoctrineMigrations\\Version20250328131544', '2025-03-28 14:15:50', 92),
('DoctrineMigrations\\Version20250411123445', '2025-04-11 14:34:50', 91),
('DoctrineMigrations\\Version20250414093145', '2025-04-14 11:50:36', 72),
('DoctrineMigrations\\Version20250414094150', '2025-04-14 14:18:25', 9),
('DoctrineMigrations\\Version20250414094337', '2025-04-14 14:23:24', 70),
('DoctrineMigrations\\Version20250414130722', '2025-04-14 15:14:02', 161),
('DoctrineMigrations\\Version20250414131347', '2025-04-14 15:15:01', 137);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scraped_page`
--

DROP TABLE IF EXISTS `scraped_page`;
CREATE TABLE `scraped_page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(50) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `surface` varchar(50) DEFAULT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrape_config`
--

DROP TABLE IF EXISTS `scrape_config`;
CREATE TABLE `scrape_config` (
  `id` int(11) NOT NULL,
  `domain` longtext NOT NULL,
  `overview_xpath` longtext NOT NULL,
  `detail_xpath` longtext NOT NULL,
  `title_xpath` longtext NOT NULL,
  `price_xpath` longtext NOT NULL,
  `description_xpath` longtext NOT NULL,
  `surface_xpath` longtext NOT NULL,
  `bedrooms_xpath` varchar(255) NOT NULL,
  `photo_xpath` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scrape_config`
--

INSERT INTO `scrape_config` (`id`, `domain`, `overview_xpath`, `detail_xpath`, `title_xpath`, `price_xpath`, `description_xpath`, `surface_xpath`, `bedrooms_xpath`, `photo_xpath`) VALUES
(3, 'sadas', 'sadasd', 'asdad', 'asdasd', 'asdad', 'asdadas', 'asdasd', 'asdasd', 'asdasd'),
(4, 'sadasdas', 'asdasdas', 'asdasd', 'asdad', 'asdasd', 'asdad', 'asdasd', 'asdasd', 'asdasd'),
(5, 'asdddddd', 'sdad', 'ada', 'ada', 'adsa', 'adsa', 'asda', 'adad', 'asdad'),
(6, 'TuncayMakelaar.nl', 'DIVIDVIDIVIDVIVIDIVVID', 'dsfs', 'asdasd', 'asddddddddddd', 'saddddddddd', 'asdad', 'asdasd', 'sadddddddddd'),
(7, 'Yasirmakelaar.nl', 'contains[.,(per vrijdag)]', '12313', '12123', '12321', '12312', '12312', '12312', '12312'),
(8, 'RaufMakelaar.fr', 'asdasdasd', 'sadasdas', 'asdasdasd', 'adad', 'asdad', 'adsadas', 'asdada', 'asdasda'),
(9, 'gfd', 'sdfsd', 'asdad', 'asdasd', 'asdasd', 'saddddddddd', 'sadad', 'asdasd', 'adada'),
(10, 'Yasirmakelaar.nl', 'sadasd', 'sada', 'dadasd', 'adada', 'dasdad', 'adsasdas', 'dadsad', 'adsad'),
(11, 'asdas', 'dasdas', 'dasdas', 'dasdasd', 'adasd', 'adasda', 'dasdas', 'dadad', 'asdasda'),
(12, 'Yasirmakelaar.nl', 'saasdas', 'dasasa', 'sdasdasd', 'asdasda', 'dasda', 'dasdas', 'dasdasd', 'adasd'),
(13, 'sadasd', 'sdasdas', 'dasdadasd', 'asda', 'dsadadas', 'dasda', 'dasdadas', 'dasdada', 'dasdasd'),
(15, 'Yasirmakelaar.nl', 'sdfsd', 'fsdfs', 'fsfs', 'sdfs', 'fsdfs', 'fsdfs', 'sdfsf', 'sfsd');

-- --------------------------------------------------------

--
-- Table structure for table `scrape_log`
--

DROP TABLE IF EXISTS `scrape_log`;
CREATE TABLE `scrape_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `message` longtext DEFAULT NULL,
  `scraper_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scrape_log`
--

INSERT INTO `scrape_log` (`id`, `user_id`, `config_id`, `status`, `message`, `scraper_name`, `created_at`) VALUES
(1, 10, 6, 'goed', 'dsadada', 'XH-Highlight', '2025-04-14 15:58:37'),
(2, 11, NULL, 'started', NULL, 'ExampleScraper', '2025-04-14 16:05:40');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`) VALUES
(2, 'paraius@gmail.com', '[\"ROLE_LEAD\"]', '$2y$13$zg6z2ZSZuGVh4CcPFeo2QOaeWGsHKnV7uCsh69zaWvfm.8MjFcqR.', NULL),
(5, 'henry.ch.tsui@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$iCpnpiYAxYQ0K7aRAKdtieRitZf8FAOUwpbrCQpmzDNlNOTszorRW', 'Henry'),
(6, 'niels@treehouse.nl', '[\"ROLE_SCRAPER\"]', '$2y$13$geL28yC3Zu05JC3jVZHmi.pYuujmQHELljqupRv2KQQ.3eytjdRSG', 'Niels'),
(7, 'h.tsui@gmail.com', '[]', '$2y$13$0IumhmEe02ix3tYsPBhmF.6hEFr3wHnvYtKPcR7zHTNqqOV.LdDnS', 'Henry'),
(8, 't.m@paraius.nl', '[\"ROLE_SCRAPER\"]', '$2y$13$jfwEuxAIwDGuAFubN0KjaejnXGdbpcCcBarZ997x..9TNbKt993Fe', 'Tuncay'),
(9, 'y.r@paraius.nl', '[\"ROLE_SCRAPER\"]', '$2y$13$/Tngs68T7X1FttbpCSIZxek8HHUbL.KaMMMogVbNKhYOEHs7..8fy', 'Yasir'),
(10, 'a.l@paraius.nl', '[\"ROLE_SCRAPER\"]', '$2y$13$dJdk5N4JEsXGpz7FV6K7M.3LBPWLuFVSYPErlQ2hf5ERkywhGUxU.', 'Anthony'),
(11, 'benji@gmail.com', '[\"ROLE_SCRAPER\"]', '$2y$13$WOc54jeCX7gedCukqYnTIeUiquiiMXSWl5oZ7UAeVjhZy2O2xkTzC', 'benji');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `scraped_page`
--
ALTER TABLE `scraped_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrape_config`
--
ALTER TABLE `scrape_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrape_log`
--
ALTER TABLE `scrape_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E14E61EFA76ED395` (`user_id`),
  ADD KEY `IDX_E14E61EF24DB0683` (`config_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scraped_page`
--
ALTER TABLE `scraped_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scrape_config`
--
ALTER TABLE `scrape_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `scrape_log`
--
ALTER TABLE `scrape_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scrape_log`
--
ALTER TABLE `scrape_log`
  ADD CONSTRAINT `FK_E14E61EF24DB0683` FOREIGN KEY (`config_id`) REFERENCES `scrape_config` (`id`),
  ADD CONSTRAINT `FK_E14E61EFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
