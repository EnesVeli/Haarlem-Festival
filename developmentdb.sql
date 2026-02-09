-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Feb 09, 2026 at 01:00 PM
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
-- Table structure for table `jazz_experiences`
--

CREATE TABLE `jazz_experiences` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_experiences`
--

INSERT INTO `jazz_experiences` (`id`, `title`, `description`, `sort_order`, `is_active`) VALUES
(1, 'Late Night Jam', 'Improvised jam sessions guided by top musicians in the festival. Feels like a smoky underground room.', 1, 1),
(2, 'Jazz & Drinks', 'Soft instrumental sets paired with cocktails and lounge seating. Feels like a classy evening in a downtown bar.', 2, 1),
(3, 'Vinyl Sessions', 'Rediscover rare jazz records curated by local vinyl experts. Feels like stepping into a vintage record store.', 3, 1),
(4, 'Sunset Stage', 'Outdoor performances with golden-hour vibes. Feels like a perfect summer evening soundtrack.', 4, 1),
(5, 'Rhythm & Coffee', 'Start your morning with mellow live jazz performed in cozy café corners across Haarlem. Feels like: smooth jazz floats through the air.', 5, 1);

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
-- Indexes for table `jazz_experiences`
--
ALTER TABLE `jazz_experiences`
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
-- AUTO_INCREMENT for table `jazz_experiences`
--
ALTER TABLE `jazz_experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
