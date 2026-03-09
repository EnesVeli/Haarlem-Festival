-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Mar 09, 2026 at 10:47 AM
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
-- Table structure for table `CartItem`
--

CREATE TABLE `CartItem` (
  `cart_item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_type` enum('single','daypass','allaccess') NOT NULL DEFAULT 'single',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
-- Table structure for table `home_content`
--

CREATE TABLE `home_content` (
  `id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `home_content`
--

INSERT INTO `home_content` (`id`, `key`, `value`) VALUES
(1, 'program_title', 'What Is My Program?'),
(2, 'program_description', 'The program is a build your own festival tool, that allows you to build you own festival with activities you like. It makes planning easier by having everything in one place. It allows you to buy and book all of the needed places in one website.'),
(3, 'events_intro', 'Discover the vibrant heart of Haarlem this July during The Festival, a unique four day celebration that transforms our historic city into a stage for culture, music, and culinary excellence. Designed to showcase Haarlem as a premier cultural capital, the program offers a diverse mix of activities that cater to every age and interest. Whether you are a history buff, a jazz enthusiast, or a foodie, you will find your perfect rhythm in our city.'),
(4, 'events_paragraph2', 'Immerse yourself in the sounds of the city with Haarlem Jazz, featuring performances at the Patronaat and a grand free concert on the Grote Markt, or feel the beat at DANCE!, where top DJs bring electronic energy to venues across town.'),
(5, 'events_paragraph3', 'No festival is complete without great flavors. Experience Yummy!, a culinary twist where Haarlem\'s finest restaurants offer exclusive festival menus ranging from gourmet dining to quick, delicious bites.'),
(6, 'events_paragraph4', 'For a quieter but equally captivating experience, join Stories in Haarlem to hear fascinating tales from local and international storytellers, or take A Stroll through History to uncover the secrets behind Haarlem\'s most iconic landmarks with expert guides.'),
(7, 'hero_image', 'Heroimage.png');

-- --------------------------------------------------------

--
-- Table structure for table `home_events`
--

CREATE TABLE `home_events` (
  `id` int(11) NOT NULL,
  `title` varchar(120) NOT NULL,
  `category` varchar(50) NOT NULL,
  `short_description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `venues` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bg_class` varchar(50) DEFAULT 'default-bg',
  `icon` varchar(50) DEFAULT 'bi-star',
  `url` varchar(255) DEFAULT '#',
  `button_label` varchar(80) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `home_events`
--

INSERT INTO `home_events` (`id`, `title`, `category`, `short_description`, `long_description`, `venues`, `image`, `bg_class`, `icon`, `url`, `button_label`, `sort_order`, `is_active`) VALUES
(1, 'Haarlem Jazz', 'Music', 'Experience world-class jazz performances across multiple venues. From smooth classics to contemporary fusion.', 'From soft saxophone melodies to energetic jam nights, Haarlem Jazz mixes tradition, modern sound, and warm summer nights...', 'Patronaat Haarlem, Grand Cafe Brinkman, New Vegas', 'Jazz.png', 'jazz-bg', 'bi-music-note-beamed', '/jazz', 'Jazz', 1, 1),
(2, 'Dance!', 'Music', 'Top DJs bring the energy with electrifying performances. Get ready to move to the best electronic beats.', 'Dance is the electronic music experience of The Festival: three nights filled with house, techno and trance across Haarlem and Bloemendaal.', 'Various venues across Haarlem', 'Dance.png', 'dance-bg', 'bi-disc', '/dance', 'Dance', 2, 1),
(3, 'Yummy!', 'Food', 'Gourmet dining with a twist. Haarlem\'s finest restaurants present exclusive festival menus.', 'From fancy dining to a quick bite in one of the many restaurants, Haarlem has it all. The city is quite famous for its wide range of restaurants and bars, on wide range of themes...', 'Ratatouille, Restaurant ML, Urban Frenchy Bistro, Restaurant Fris', 'Yummy!.jpg', 'food-bg', 'bi-cup-hot', '/food', 'Yummy', 3, 1),
(4, 'A Stroll through History!', 'Culture', 'Walk through centuries of Dutch heritage. Discover Haarlem\'s historic landmarks with expert guides.', 'Discover the city of painters, merchants, and hidden courtyards. Experience 775 years of history in one unforgettable walk.', 'Grote Markt, Corrie ten Boom house', 'bavo-church.jpg', 'history-bg', 'bi-clock-history', '/history', 'History', 4, 1),
(5, 'Stories in Haarlem', 'Culture', 'Immerse yourself in captivating narratives. From local legends to international storytellers.', 'During the last weekend of July, Stories in Haarlem brings live stories, podcasts and family shows to different locations across the city.', 'Verhalenhuis Haarlem, Elswout Theater, De Schuur, Café de Roemer', 'Story.jpg', 'stories-bg', 'bi-book', '/stories', 'Stories', 5, 1);

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
-- Table structure for table `PasswordResetToken`
--

CREATE TABLE `PasswordResetToken` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
(4, 'hasan@costumer.com', '$2y$12$zP1tpSnNx/OP95eNm921t.VJb9sVhAEvJfdCYLXZmHo0kbGL25Zma', 'Hasan zaz', 'customer', NULL, '2026-02-09 09:44:09'),
(5, 'tim.sadko@gmail.com', '$2y$12$yIxXUap9pB4BZPdlpZE7jOfIpfCoZrNLgru7Rvc8TCgrpLzFtspD6', 'Timofii Sadko', 'customer', NULL, '2026-02-27 12:38:00'),
(6, 'elena.sadko@gmail.com', '$2y$12$Zg7P22jNmZtkM1EdZczHUe8EwCbiOGnO.XAPybPnES2ah1nBSlrtW', 'Elena Shkvarnytska', 'customer', NULL, '2026-02-27 12:41:38'),
(7, 'fff.fff@gmail.com', '$2y$12$FgVzZeZQ9wBDZsRRTdclReF782iykFNhv11yDmOLHK/hVYCPo91k.', 'ffff', 'customer', NULL, '2026-02-27 12:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `YummyFoodTypes`
--

CREATE TABLE `YummyFoodTypes` (
  `type_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `category` bit(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyFoodTypes`
--

INSERT INTO `YummyFoodTypes` (`type_id`, `name`, `category`) VALUES
(1, 'Restaurant', b'0000'),
(2, 'Bar', b'0000'),
(3, 'Cafe', b'0000'),
(4, 'Breakfast', b'0001'),
(5, 'Lunch', b'0001'),
(6, 'Dinner', b'0001'),
(7, 'European', b'0011'),
(8, 'Dutch', b'0011'),
(9, 'French', b'0011'),
(10, 'Asian', b'0011'),
(11, 'African', b'0011'),
(12, 'South/Central American', b'0011'),
(13, 'Seafood', b'0010'),
(14, 'Vegetarian', b'0010'),
(15, 'Vegan', b'0010');

-- --------------------------------------------------------

--
-- Table structure for table `YummyGuides`
--

CREATE TABLE `YummyGuides` (
  `guide_id` int(11) NOT NULL,
  `mini_img_path` varchar(63) NOT NULL,
  `mini_title` varchar(63) NOT NULL,
  `mini_text` varchar(255) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyGuides`
--

INSERT INTO `YummyGuides` (`guide_id`, `mini_img_path`, `mini_title`, `mini_text`, `active`) VALUES
(1, 'qwer323.png', 'Culinary Guide to Haarlem’s Finest Restaurants', 'Explore Haarlem’s top restaurants, from Michelin-starred Ratatouille to elegant Koper and creative Restaurant Fris. Discover what makes each unique and find the perfect spot for your next meal.', b'1'),
(2, 'a39d6fdad9e55759e55a35abf53babf4a68d69e7.jpg', 'Where to Drink in Haarlem: Top Bars and Pubs', 'Explore Haarlem’s lively bar scene, from cozy local pubs to stylish cocktail bars and craft beer spots perfect for relaxed evenings, social drinks, and vibrant nightlife in the city center.', b'1'),
(3, 'ec39dac2209ad74ecc7ddb524164516fb3acfdb3.jpg', 'Haarlem’s Coziest Cafés and Coffee Spots', 'Discover Haarlem’s charming cafés offering great coffee, home made cakes, relaxed brunches, and welcoming atmospheres ideal for a break while exploring the city or meeting friends.', b'1'),
(4, '11b05b574d74972f10ccb0f89d246c5aed797e7f.jpg', 'Where to Eat Cheap and Well in Haarlem', 'Find affordable restaurants in Haarlem serving tasty meals without breaking the budget, from casual eateries to quick bites perfect for students, families, and budget friendly dining.', b'1'),
(5, '11b05b574d74972f10ccb0f89d246c5aed797e7f.jpg\r\n', 'Where to Eat Cheap and Well in Haarlem', 'Find affordable restaurants in Haarlem serving tasty meals without breaking the budget, from casual eateries to quick bites perfect for students, families, and budget friendly dining.', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `YummyRestaurantFoodTypes`
--

CREATE TABLE `YummyRestaurantFoodTypes` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyRestaurantFoodTypes`
--

INSERT INTO `YummyRestaurantFoodTypes` (`id`, `restaurant_id`, `type_id`) VALUES
(104, 1, 1),
(105, 1, 6),
(106, 1, 7),
(107, 1, 9),
(108, 1, 13),
(109, 2, 1),
(110, 2, 5),
(111, 2, 6),
(112, 2, 7),
(113, 4, 1),
(114, 4, 2),
(115, 4, 4),
(116, 4, 5),
(117, 4, 6),
(118, 4, 7),
(119, 4, 8),
(120, 5, 3),
(121, 5, 4),
(122, 5, 5),
(123, 5, 7),
(124, 5, 8),
(125, 5, 14),
(126, 6, 1),
(127, 6, 6),
(128, 6, 7),
(129, 6, 9),
(130, 6, 13),
(131, 7, 3),
(132, 7, 4),
(133, 7, 5),
(134, 7, 6),
(135, 7, 7),
(136, 7, 8),
(137, 7, 9),
(138, 7, 14),
(139, 8, 1),
(140, 8, 3),
(141, 8, 4),
(142, 8, 5),
(143, 8, 6),
(144, 8, 7),
(145, 8, 8),
(146, 8, 9),
(147, 8, 14),
(148, 9, 1),
(149, 9, 5),
(150, 9, 6),
(151, 9, 7),
(152, 9, 9),
(153, 9, 13),
(154, 9, 15);

-- --------------------------------------------------------

--
-- Table structure for table `YummyRestaurants`
--

CREATE TABLE `YummyRestaurants` (
  `restaurant_id` int(16) NOT NULL,
  `mini_img_path` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `mini_text` varchar(256) NOT NULL,
  `rating` float NOT NULL,
  `cost_rating` bit(4) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyRestaurants`
--

INSERT INTO `YummyRestaurants` (`restaurant_id`, `mini_img_path`, `name`, `mini_text`, `rating`, `cost_rating`, `active`) VALUES
(1, 'rff.png', 'Ratatouille', 'Elegant fine-dining restaurant with a refined French cuisine in a historic riverside building. Perfect for special occasions and memorable dinner experiences. ', 4.6, b'0011', b'1'),
(2, 'trft.png', 'Restaurant Fris', 'Contemporary restaurant known for modern, creative dishes in a welcoming setting, combining innovative flavors with relaxed dining.', 4, b'0011', b'1'),
(4, '4ecb8fc1f9639bc4fd8c85c461a90507d25987c6.png', 'New Vegas', 'A lively restaurant and bar offering a relaxed atmosphere, comfort food, and drinks — great for casual meetups, meals with friends, or an easy night out. ', 3.4, b'0010', b'1'),
(5, '84ebc9c296006b843e884811ba26ba5c0f48e87a.png', 'Grand Cafe Brinkman', 'Classic Haarlem café-restaurant perfect for lunch, dinner, drinks, or people-watching in the city centre with a warm, inviting vibe. ', 3.8, b'0010', b'1'),
(6, 'c1f770e71ad26341a02236bdbfaa8764d78382e7.png', 'Koper', 'Elegant dining with refined dishes rooted in classic European cuisine, ideal for a memorable dinner night out or special occasion in beautifully styled surroundings. ', 5, b'0011', b'1'),
(7, 'a96586c89bdcf8bd35ca11c1fa519a7f35b3451b.png', 'Café de Roemer', 'Cozy cafe serving light bites, drinks and casual fare in a historic Haarlem spot ideal for coffee breaks or relaxed socializing. ', 4.1, b'0011', b'1'),
(8, 'eccbb8f0cb382e19ddd12930d34f2c1bb32a6fd0.png', 'Restaurant ML', 'A charming café/restaurant blending relaxed dining with a casual menu and friendly service great for informal meals or coffee.', 4.5, b'0011', b'1'),
(9, '84703904c0b0b04ff368246f347530bbcb94c1bf.png', 'Urban Frenchy Bistro Toujours', 'A lively Mediterranean-inspired spot on Haarlem’s Grote Markt, perfect for sharing flavourful cocktails, and relaxed meals with friends or family.', 3.2, b'0001', b'1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `CartItem`
--
ALTER TABLE `CartItem`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indexes for table `home_content`
--
ALTER TABLE `home_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `home_events`
--
ALTER TABLE `home_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `PasswordResetToken`
--
ALTER TABLE `PasswordResetToken`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `YummyFoodTypes`
--
ALTER TABLE `YummyFoodTypes`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `YummyGuides`
--
ALTER TABLE `YummyGuides`
  ADD PRIMARY KEY (`guide_id`);

--
-- Indexes for table `YummyRestaurantFoodTypes`
--
ALTER TABLE `YummyRestaurantFoodTypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `YummyRestaurants`
--
ALTER TABLE `YummyRestaurants`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `CartItem`
--
ALTER TABLE `CartItem`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_content`
--
ALTER TABLE `cms_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

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
-- AUTO_INCREMENT for table `home_content`
--
ALTER TABLE `home_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `home_events`
--
ALTER TABLE `home_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `YummyFoodTypes`
--
ALTER TABLE `YummyFoodTypes`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `YummyGuides`
--
ALTER TABLE `YummyGuides`
  MODIFY `guide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `YummyRestaurantFoodTypes`
--
ALTER TABLE `YummyRestaurantFoodTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `YummyRestaurants`
--
ALTER TABLE `YummyRestaurants`
  MODIFY `restaurant_id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CartItem`
--
ALTER TABLE `CartItem`
  ADD CONSTRAINT `cartitem_user_fk` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE;

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

--
-- Constraints for table `PasswordResetToken`
--
ALTER TABLE `PasswordResetToken`
  ADD CONSTRAINT `PasswordResetToken_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`);

--
-- Constraints for table `YummyRestaurantFoodTypes`
--
ALTER TABLE `YummyRestaurantFoodTypes`
  ADD CONSTRAINT `YummyRestaurantFoodTypes_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `YummyRestaurants` (`restaurant_id`),
  ADD CONSTRAINT `YummyRestaurantFoodTypes_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `YummyFoodTypes` (`type_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
