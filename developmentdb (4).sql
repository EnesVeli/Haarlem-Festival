-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Feb 16, 2026 at 12:55 PM
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
-- Table structure for table `history_content`
--

CREATE TABLE `history_content` (
  `id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_left` varchar(255) DEFAULT NULL,
  `image_right` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `history_content`
--

INSERT INTO `history_content` (`id`, `section`, `title`, `subtitle`, `image`, `image_left`, `image_right`) VALUES
(1, 'hero', 'A Journey Through Haarlem\'s Legacy', 'Discover the city of painters, merchants, and hidden courtyards.', 'hero-history.png', NULL, NULL),
(2, 'intro', 'The Golden City of the North', 'Long before Amsterdam rose to global fame, Haarlem thrived.', NULL, 'grote-markt.jpg', 'historic-buildings.jpg'),
(3, 'walk', 'Better Your Walk', 'Guided tours available for a deeper experience.', 'walk-guide.jpg', NULL, NULL),
(4, 'cta', 'Ready to plan your festival weekend?', 'Combine history with other festival events.', 'cta-bg.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `history_details`
--

CREATE TABLE `history_details` (
  `id` int(11) NOT NULL,
  `highlight_id` int(11) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `founded_year` varchar(50) DEFAULT NULL,
  `style_type` varchar(100) DEFAULT NULL,
  `meta_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `history_details`
--

INSERT INTO `history_details` (`id`, `highlight_id`, `slug`, `page_title`, `hero_image`, `location`, `founded_year`, `style_type`, `meta_description`) VALUES
(1, 3, 'teylers-museum', 'Teylers Museum', 'teylers-museum-hero.jpg', 'Haarlem City Center', '1784', 'Art & Science', 'Discover Teylers Museum, the oldest museum in the Netherlands, featuring art, science, and natural history collections.'),
(2, 2, 'st-bavos-cathedral', 'St. Bavo\'s Cathedral', 'st-bavos-hero.jpg', 'Haarlem City Center', '1570-1520', 'Gothic', 'Explore St. Bavo\'s Cathedral, the magnificent Gothic church with the famous Müller Organ.');

-- --------------------------------------------------------

--
-- Table structure for table `history_detail_facts`
--

CREATE TABLE `history_detail_facts` (
  `id` int(11) NOT NULL,
  `detail_id` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `label` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `history_detail_facts`
--

INSERT INTO `history_detail_facts` (`id`, `detail_id`, `icon`, `label`, `value`, `sort_order`) VALUES
(1, 1, '1784', 'YEAR FOUNDED', 'Oldest in Holland', 1),
(2, 1, '150k+', 'OBJECTS', 'In collection', 2),
(3, 1, '1370', 'CONSTRUCTION START', 'Gothic Era', 3),
(4, 1, '#2', 'ON WALKING ROUTE', 'Next Stop', 4),
(5, 2, '75m', 'TOWER HEIGHT', 'Dominates Skyline', 1),
(6, 2, '5k+', 'ORGAN PIPES', 'Müller Organ', 2),
(7, 2, '1370', 'CONSTRUCTION START', 'Gothic Era', 3),
(8, 2, '#1', 'ON WALKING ROUTE', 'Start Point', 4);

-- --------------------------------------------------------

--
-- Table structure for table `history_detail_gallery`
--

CREATE TABLE `history_detail_gallery` (
  `id` int(11) NOT NULL,
  `detail_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `history_detail_gallery`
--

INSERT INTO `history_detail_gallery` (`id`, `detail_id`, `image_path`, `caption`, `sort_order`) VALUES
(1, 1, 'teylers-gallery-1.jpg', 'Main exhibition hall', 1),
(2, 1, 'teylers-gallery-2.jpg', 'Historic interior', 2),
(3, 1, 'teylers-gallery-3.jpg', 'Riverside view', 3),
(4, 2, 'st-bavos-gallery-1.jpg', 'Exterior architecture', 1),
(5, 2, 'st-bavos-gallery-2.jpg', 'Interior dome', 2),
(6, 2, 'st-bavos-gallery-3.jpg', 'Waterfront boats', 3);

-- --------------------------------------------------------

--
-- Table structure for table `history_detail_sections`
--

CREATE TABLE `history_detail_sections` (
  `id` int(11) NOT NULL,
  `detail_id` int(11) NOT NULL,
  `section_type` varchar(50) NOT NULL,
  `section_title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `history_detail_sections`
--

INSERT INTO `history_detail_sections` (`id`, `detail_id`, `section_type`, `section_title`, `content`, `image_path`, `sort_order`) VALUES
(1, 1, 'about', 'About Teylers Museum', 'Teylers Museum stands as Holland\'s oldest museum and a testament to the Enlightenment ideals of the 18th century. Founded in 1784, it was created from the fortune of Pieter Teyler van der Hulst, a wealthy Haarlem cloth merchant and banker of Scottish descent who dedicated his fortune to the advancement of religion, art, and science.\n\nWalking through Teylers is like stepping into a cabinet of curiosities from a more elegant age. The museum houses an extraordinary collection spanning art, science, and natural history—from masterworks by Michelangelo, Raphael, and Rembrandt to remarkable fossils including some of the first specimens of Archaeopteryx ever discovered.', NULL, 1),
(2, 1, 'highlight', 'Science & Fossils', 'Its scientific instruments tell the story of humanity\'s quest to understand the natural world. The collection includes minerals, fossils, and brilliant 18th-century physics instruments.\n\nOne of the museum\'s treasures is its collection of scientific drawings and early discoveries that shaped our understanding of the world.', NULL, 2),
(3, 1, 'highlight', 'Art & Drawings', 'The collection encompasses works by Old Masters such as Michelangelo, Raphael, alongside significant holdings from Dutch artists including Rembrandt and Hendrik Willem Mesdag.\n\nThe museum houses over 25,000 drawings spanning from the Renaissance to the 19th century—an unparalleled resource for art historians and enthusiasts.', NULL, 3),
(4, 1, 'special', 'Pieter Teyler\'s Legacy', 'Pieter Teyler was a wealthy silk merchant and banker who had no children. In his will, he established a foundation that would use his fortune for the benefit of the public through research and artistic creation. His legacy continues to inspire scholarly work and artistic development.\n\nThe institution he founded remains committed to his vision of making knowledge and beauty accessible to all.', NULL, 4),
(5, 2, 'about', 'About St. Bavo\'s Church', 'The Grote Kerk, known as St. Bavo\'s Church, dominates Haarlem\'s skyline from the central market square. A church has stood on this site since at least the 10th century, but the current Gothic building was constructed between 1370 and 1520, transforming it into Haarlem\'s most prominent landmark.\n\nThe church briefly served as a Catholic cathedral from 1559 to 1578 before the Protestant Reformation reached Haarlem. During the 1578 uprising known as the \"Haarlemse Noon,\" the building was seized and converted to Protestant use. Since then, it has been known simply as the Grote Kerk, meaning \"Great Church,\" reflecting its role as a Protestant place of worship rather than its former Catholic dedication to Saint Bavo.', NULL, 1),
(6, 2, 'special', 'The Müller Organ', 'The church houses one of Europe\'s most celebrated organs, built between 1735 and 1738 by Amsterdam organ builder Christian Müller. At the time of its completion, it was the largest organ in the world, featuring over 5,000 pipes and standing nearly 30 meters tall.\n\nThe instrument\'s gilded case is adorned with 32 life-size sculptures, making it as visually impressive as it is musically significant. Renowned composers like George Frideric Handel and a 10-year-old Wolfgang Amadeus Mozart traveled to Haarlem specifically to play it.', 'muller-organ.jpg', 2),
(7, 2, 'history', 'Historical Significance', 'St. Bavo\'s witnessed crucial moments in Dutch history, particularly during the Protestant Reformation when religious control of the Netherlands shifted dramatically. The building\'s architectural evolution from medieval times through the Renaissance period tells the story of Haarlem\'s changing fortunes and religious landscape.\n\nToday, it continues to serve both as a Protestant church and as a cultural venue hosting concerts and exhibitions, making it a living monument to the city\'s past and present.', NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `history_highlights`
--

CREATE TABLE `history_highlights` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `history_highlights`
--

INSERT INTO `history_highlights` (`id`, `title`, `description`, `image`) VALUES
(2, 'St. Bavo Church', 'Famous Gothic church overlooking the square.', 'bavo-church.jpg'),
(3, 'Teylers Museum', 'The oldest museum in the Netherlands.', 'teylers.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `history_tickets`
--

CREATE TABLE `history_tickets` (
  `id` int(11) NOT NULL,
  `time_slot` varchar(50) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `available_spots` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `history_tickets`
--

INSERT INTO `history_tickets` (`id`, `time_slot`, `price`, `available_spots`) VALUES
(1, '10:00 AM', 12.50, 15),
(2, '01:00 PM', 12.50, 10),
(3, '04:00 PM', 15.00, 8);

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
(2, 'hanish.Suresh@gmail.com', '$2y$12$wxpjxiDKEnslhZHsKGueB.XGxkLvQpplqzaD8z7sdUuhg4NEyS716', 'fuvk mer ', 'customer', NULL, '2026-02-09 09:55:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `history_content`
--
ALTER TABLE `history_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_details`
--
ALTER TABLE `history_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `highlight_id` (`highlight_id`);

--
-- Indexes for table `history_detail_facts`
--
ALTER TABLE `history_detail_facts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_id` (`detail_id`);

--
-- Indexes for table `history_detail_gallery`
--
ALTER TABLE `history_detail_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_id` (`detail_id`);

--
-- Indexes for table `history_detail_sections`
--
ALTER TABLE `history_detail_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_id` (`detail_id`);

--
-- Indexes for table `history_highlights`
--
ALTER TABLE `history_highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_tickets`
--
ALTER TABLE `history_tickets`
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
-- AUTO_INCREMENT for table `history_content`
--
ALTER TABLE `history_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `history_details`
--
ALTER TABLE `history_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `history_detail_facts`
--
ALTER TABLE `history_detail_facts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `history_detail_gallery`
--
ALTER TABLE `history_detail_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `history_detail_sections`
--
ALTER TABLE `history_detail_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `history_highlights`
--
ALTER TABLE `history_highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `history_tickets`
--
ALTER TABLE `history_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `history_details`
--
ALTER TABLE `history_details`
  ADD CONSTRAINT `history_details_ibfk_1` FOREIGN KEY (`highlight_id`) REFERENCES `history_highlights` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_detail_facts`
--
ALTER TABLE `history_detail_facts`
  ADD CONSTRAINT `history_detail_facts_ibfk_1` FOREIGN KEY (`detail_id`) REFERENCES `history_details` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_detail_gallery`
--
ALTER TABLE `history_detail_gallery`
  ADD CONSTRAINT `history_detail_gallery_ibfk_1` FOREIGN KEY (`detail_id`) REFERENCES `history_details` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_detail_sections`
--
ALTER TABLE `history_detail_sections`
  ADD CONSTRAINT `history_detail_sections_ibfk_1` FOREIGN KEY (`detail_id`) REFERENCES `history_details` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
