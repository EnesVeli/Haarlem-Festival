-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Mar 09, 2026 at 10:06 AM
-- Server version: 12.0.2-MariaDB-ubu2404
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_content`
--

CREATE TABLE `cms_content` (
  `id` int(11) NOT NULL,
  `page_key` varchar(50) NOT NULL,
  `block_type` varchar(50) NOT NULL,
  `performer_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `uniq_key` varchar(400) GENERATED ALWAYS AS (concat(`page_key`,'|',`block_type`,'|',ifnull(`performer_id`,0),'|',ifnull(`url`,''),'|',`title`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `cms_content`
--

INSERT INTO `cms_content` (`id`, `page_key`, `block_type`, `performer_id`, `title`, `subtitle`, `body`, `url`, `image_path`, `sort_order`, `is_active`) VALUES
(2, 'jazz_home', 'experience', 0, 'Jazz & Drinks', NULL, 'Soft instrumental sets paired with cocktails and lounge seating. Feels like a classy evening in a downtown bar.', '', NULL, 2, 1),
(3, 'jazz_home', 'experience', 0, 'Vinyl Sessions', NULL, 'Rediscover rare jazz records curated by local vinyl experts. Feels like stepping into a vintage record store.', '', NULL, 3, 1),
(4, 'jazz_home', 'experience', 0, 'Sunset Stage', NULL, 'Outdoor performances with golden-hour vibes. Feels like a perfect summer evening soundtrack.', '', NULL, 4, 1),
(5, 'jazz_home', 'experience', 0, 'Rhythm & Coffee', NULL, 'Start your morning with mellow live jazz performed in cozy café corners across Haarlem. Feels like: smooth jazz floats through the air.', '', NULL, 5, 1),
(8, 'jazz_home', 'performer', 1, 'Evolve', NULL, NULL, '', NULL, 1, 1),
(9, 'jazz_home', 'performer', 2, 'Fox & The Mayors', NULL, NULL, '', NULL, 2, 1),
(10, 'jazz_home', 'performer', 3, 'Gare du Nord', NULL, NULL, '', NULL, 3, 1),
(11, 'jazz_home', 'performer', 4, 'Gumbo Kings', NULL, NULL, '', NULL, 4, 1),
(12, 'jazz_home', 'performer', 5, 'Han Bennink', NULL, NULL, '', NULL, 5, 1),
(13, 'jazz_home', 'performer', 6, 'Jonna Frazer', NULL, NULL, '', NULL, 6, 1),
(14, 'jazz_home', 'performer', 7, 'Chris Allen', NULL, NULL, '', NULL, 7, 1),
(15, 'jazz_home', 'performer', 8, 'Lilith Merlot', NULL, NULL, '', NULL, 8, 1),
(16, 'jazz_home', 'performer', 9, 'Myles Sanko', NULL, NULL, '', NULL, 9, 1),
(17, 'jazz_home', 'performer', 10, 'Ntjam Rosie', NULL, NULL, '', NULL, 10, 1),
(18, 'jazz_home', 'performer', 11, 'Rilan & The Bombardiers', NULL, NULL, '', NULL, 11, 1),
(19, 'jazz_home', 'performer', 12, 'Ruis Soundsystem', NULL, NULL, '', NULL, 12, 1),
(20, 'jazz_home', 'performer', 13, 'Soul Six', NULL, NULL, '', NULL, 13, 1),
(21, 'jazz_home', 'performer', 14, 'The Family XL', NULL, NULL, '', NULL, 14, 1),
(22, 'jazz_home', 'performer', 15, 'The Nordanians', NULL, NULL, '', NULL, 15, 1),
(23, 'jazz_home', 'performer', 16, 'The Tom Thompson', NULL, NULL, '', NULL, 16, 1),
(24, 'jazz_home', 'performer', 17, 'Uncle Sue', NULL, NULL, '', NULL, 17, 1),
(25, 'jazz_home', 'performer', 18, 'Wicked Jazz Sounds', NULL, NULL, '', NULL, 18, 1),
(39, 'jazz_home', 'recommendation', 0, 'A Stroll Through History', NULL, 'Guided walking tour through historic Haarlem with local storytellers.', '/history', NULL, 1, 1),
(40, 'jazz_home', 'recommendation', 0, 'Stories', NULL, 'Immerse yourself in Haarlem’s spoken-word acts, storytelling, and narrative performances.', '/story', NULL, 2, 1),
(41, 'jazz_home', 'recommendation', 0, 'Yummy!', NULL, 'Explore Dutch cuisine and food history with tastings and local favorites.', '/yummy', NULL, 3, 1),
(42, 'jazz_home', 'recommendation', 0, 'Dance', NULL, 'Feel the energy of live DJs, dance shows, and late-night party vibes.', '/dance', NULL, 4, 1),
(43, 'jazz_home', 'recommendation', 0, 'Haarlem Jazz', NULL, 'Live jazz performances and unforgettable sessions across the city.', '/jazz', NULL, 5, 1),
(46, 'jazz_home', 'experience', 0, 'Late Night Chill Jam', '', 'Improvised jam sessions guided by top musicians in the festival. Feels like a smoky underground room.', '', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_performers`
--

CREATE TABLE `jazz_performers` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `bio` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_performers`
--

INSERT INTO `jazz_performers` (`id`, `name`, `bio`, `sort_order`, `is_active`, `image_path`) VALUES
(1, 'Evolve', NULL, 1, 1, NULL),
(2, 'Fox & The Mayors', NULL, 2, 1, NULL),
(3, 'Gare du Nord', NULL, 3, 1, NULL),
(4, 'Gumbo Kings', NULL, 4, 1, NULL),
(5, 'Han Bennink', NULL, 5, 1, NULL),
(6, 'Jonna Frazer', NULL, 6, 1, NULL),
(7, 'Chris Allen', NULL, 7, 1, NULL),
(8, 'Lilith Merlot', NULL, 8, 1, NULL),
(9, 'Myles Sanko', NULL, 9, 1, NULL),
(10, 'Ntjam Rosie', NULL, 10, 1, NULL),
(11, 'Rilan & The Bombardiers', NULL, 11, 1, NULL),
(12, 'Ruis Soundsystem', NULL, 12, 1, NULL),
(13, 'Soul Six', NULL, 13, 1, NULL),
(14, 'The Family XL', NULL, 14, 1, NULL),
(15, 'The Nordanians', NULL, 15, 1, NULL),
(16, 'The Tom Thompson', NULL, 16, 1, NULL),
(17, 'Uncle Sue', NULL, 17, 1, NULL),
(18, 'Wicked Jazz Sounds', NULL, 18, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` enum('customer','employee','admin') NOT NULL DEFAULT 'customer',
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `registered_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`user_id`, `email`, `password`, `name`, `role`, `profile_picture_url`, `registered_at`) VALUES
(1, 'Enes@gmail.com', '$2y$12$ykWvBL0DARXSigoiMGxw3.4ow.YKd/BUidn/IApoOEwgVz7RFQe/W', 'Enes', 'customer', NULL, '2026-02-06 18:36:26'),
(2, 'achraf@admin.com', '$2y$12$b8feJtwJ9Vg02pXHbV44gOvCuQKGwSeNwA0l9ug32ovMr3PEqR/Am', 'achraf derouich', 'admin', NULL, '2026-02-07 04:07:01'),
(3, 'achraf@custumer.com', '$2y$12$xNRPBJ1/XOl6sG6z4rNkFeOG3TlzWpbqAdieirQsXVXFjXlpRSmX.', 'achraf derouich', 'customer', NULL, '2026-02-08 02:52:34'),
(4, 'hasan@costumer.com', '$2y$12$zP1tpSnNx/OP95eNm921t.VJb9sVhAEvJfdCYLXZmHo0kbGL25Zma', 'Hasan zaz', 'customer', NULL, '2026-02-09 09:44:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_content`
--
ALTER TABLE `cms_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cms_block` (`page_key`,`block_type`,`performer_id`,`title`,`url`),
  ADD UNIQUE KEY `uq_cms_uniq_key` (`uniq_key`),
  ADD KEY `idx_page_block` (`page_key`,`block_type`),
  ADD KEY `idx_active_sort` (`is_active`,`sort_order`);

--
-- Indexes for table `jazz_performers`
--
ALTER TABLE `jazz_performers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_content`
--
ALTER TABLE `cms_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `jazz_performers`
--
ALTER TABLE `jazz_performers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
