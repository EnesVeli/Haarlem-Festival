-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Feb 15, 2026 at 06:18 PM
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
-- Table structure for table `event_recommendations`
--

CREATE TABLE `event_recommendations` (
  `id` int(11) NOT NULL,
  `event_key` varchar(50) NOT NULL,
  `title` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `event_recommendations`
--

INSERT INTO `event_recommendations` (`id`, `event_key`, `title`, `description`, `url`, `sort_order`, `is_active`) VALUES
(1, 'history', 'A Stroll Through History', 'Guided walking tour through historic Haarlem with local storytellers.', '/history', 1, 1),
(2, 'stories', 'Stories', 'Immerse yourself in Haarlem’s spoken-word acts, storytelling, and narrative performances.', '/story', 2, 1),
(3, 'yummy', 'Yummy!', 'Explore Dutch cuisine and food history with tastings and local favorites.', '/yummy', 3, 1),
(4, 'dance', 'Dance', 'Feel the energy of live DJs, dance shows, and late-night party vibes.', '/dance', 4, 1),
(5, 'jazz', 'Haarlem Jazz', 'Live jazz performances and unforgettable sessions across the city.', '/jazz', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_experiences`
--

CREATE TABLE `jazz_experiences` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_experiences`
--

INSERT INTO `jazz_experiences` (`id`, `title`, `description`, `image`, `url`, `sort_order`, `is_active`) VALUES
(1, 'Late Night Jam', 'Improvised jam sessions guided by top musicians in the festival. Feels like a smoky underground room.', NULL, NULL, 1, 1),
(2, 'Jazz & Drinks', 'Soft instrumental sets paired with cocktails and lounge seating. Feels like a classy evening in a downtown bar.', NULL, NULL, 2, 1),
(3, 'Vinyl Sessions', 'Rediscover rare jazz records curated by local vinyl experts. Feels like stepping into a vintage record store.', NULL, NULL, 3, 1),
(4, 'Sunset Stage', 'Outdoor performances with golden-hour vibes. Feels like a perfect summer evening soundtrack.', NULL, NULL, 4, 1),
(5, 'Rhythm & Coffee', 'Start your morning with mellow live jazz performed in cozy café corners across Haarlem. Feels like: smooth jazz floats through the air.', NULL, NULL, 5, 1);

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
(2, 'achraf@admin.com', '$2y$12$b8feJtwJ9Vg02pXHbV44gOvCuQKGwSeNwA0l9ug32ovMr3PEqR/Am', 'achraf derouich', 'customer', NULL, '2026-02-07 04:07:01'),
(3, 'achraf@custumer.com', '$2y$12$xNRPBJ1/XOl6sG6z4rNkFeOG3TlzWpbqAdieirQsXVXFjXlpRSmX.', 'achraf derouich', 'customer', NULL, '2026-02-08 02:52:34'),
(4, 'hasan@costumer.com', '$2y$12$zP1tpSnNx/OP95eNm921t.VJb9sVhAEvJfdCYLXZmHo0kbGL25Zma', 'Hasan zaz', 'customer', NULL, '2026-02-09 09:44:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event_recommendations`
--
ALTER TABLE `event_recommendations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_event_key` (`event_key`);

--
-- Indexes for table `jazz_experiences`
--
ALTER TABLE `jazz_experiences`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `event_recommendations`
--
ALTER TABLE `event_recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jazz_experiences`
--
ALTER TABLE `jazz_experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
