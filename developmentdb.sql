-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 07, 2026 at 04:47 AM
-- Server version: 12.1.2-MariaDB-ubu2404
-- PHP Version: 8.3.30

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

--
-- Dumping data for table `CartItem`
--

INSERT INTO `CartItem` (`cart_item_id`, `user_id`, `event_type`, `event_id`, `ticket_type`, `quantity`, `price`, `added_at`) VALUES
(1, 4, 'story', 8, 'single', 1, 0.00, '2026-03-14 16:29:24'),
(14, 11, 'stories', 12, 'single', 1, 7.50, '2026-04-01 10:39:24'),
(15, 11, 'stories', 3, 'single', 1, 14.00, '2026-04-01 10:40:20'),
(16, 11, 'stories', 3, 'single', 1, 10.00, '2026-04-01 10:40:45'),
(17, 11, 'stories', 12, 'single', 1, 10.00, '2026-04-01 10:40:59'),
(18, 11, 'stories', 12, 'single', 1, 10.00, '2026-04-01 10:42:48'),
(20, 11, 'stories', 3, 'single', 1, 10.00, '2026-04-01 10:44:16'),
(23, 11, 'yummy', 154, 'single', 1, 35.00, '2026-04-01 13:08:21'),
(24, 11, 'history', 138, 'single', 1, 0.00, '2026-04-01 14:54:29'),
(25, 11, 'history', 138, 'single', 1, 17.50, '2026-04-01 15:26:12'),
(34, 12, 'stories', 3, 'single', 1, 10.00, '2026-04-04 18:07:40'),
(35, 12, 'stories', 3, 'single', 1, 15.00, '2026-04-04 18:07:52'),
(47, 2, 'yummy', 188, 'single', 1, 140.00, '2026-04-07 04:32:10'),
(48, 2, 'yummy', 189, 'single', 1, 50.00, '2026-04-07 04:35:01');

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
-- Table structure for table `CMS_Content`
--

CREATE TABLE `CMS_Content` (
  `content_id` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body_html` text DEFAULT NULL COMMENT 'WYSIWYG editable content',
  `image_path` varchar(500) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `quote_text` varchar(500) DEFAULT NULL,
  `cta_text` varchar(255) DEFAULT NULL,
  `ticket_info_title_1` varchar(255) DEFAULT 'Pay as you like',
  `ticket_info_body_1` text DEFAULT NULL,
  `ticket_info_note_1` varchar(500) DEFAULT NULL,
  `ticket_info_title_2` varchar(255) DEFAULT 'HaarlemPas discount',
  `ticket_info_body_2` text DEFAULT NULL,
  `cta_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `CMS_Content`
--

INSERT INTO `CMS_Content` (`content_id`, `slug`, `title`, `body_html`, `image_path`, `subtitle`, `quote_text`, `cta_text`, `ticket_info_title_1`, `ticket_info_body_1`, `ticket_info_note_1`, `ticket_info_title_2`, `ticket_info_body_2`, `cta_description`) VALUES
(1, 'stories', 'Stories in Haarlem', '<p>During the last weekend of July, the streets of Haarlem transform into a living library. Stories in Haarlem brings a mix of live performances, intimate podcast recordings, and immersive family shows to unique locations across the city. From the whimsical adventures of Winnie the Pooh for our youngest listeners to the moving history of the Ten Boom family and the forward-thinking ideas of local circular entrepreneurs. Whether you are a history buff, a curious thinker, or a family seeking magic, there is a tale waiting for you.</p>', '/assets/images/stories/32c8142c12f271ca8980dce932be8fd7.jpeg', 'Last Weekend of July | Multiple Locations across Haarlem', 'Every street has a sound. Every building has a memory', 'Ready to plan your festival weekend?', 'Pay as you like', 'Some activities are priced pay as you like. We aim to keep these events as accessible as possible so that everyone has the opportunity to participate. We encourage visitors to donate based on how they valued the experience.', 'A reservation is required to guarantee entry.', 'HaarlemPas discount', 'People with the HaarlemPas receive a 25% discount on entry fees for all stories events with a fixed ticket price.', 'Combine Stories in Haarlem with other festival events across the city and build your perfect weekend program.');

-- --------------------------------------------------------

--
-- Table structure for table `Event`
--

CREATE TABLE `Event` (
  `event_id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1=Jazz 2=Dance 3=History 4=Yummy 5=Stories',
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL COMMENT 'URL: /stories/mister-anansi',
  `description` text DEFAULT NULL,
  `performer_name` varchar(255) DEFAULT NULL COMMENT 'Storyteller / presenter name',
  `performer_bio` text DEFAULT NULL COMMENT 'Bio shown on detail page',
  `language` varchar(10) NOT NULL DEFAULT 'NL' COMMENT 'NL or ENG',
  `age_group` varchar(20) NOT NULL DEFAULT 'All ages',
  `story_type` varchar(100) DEFAULT NULL COMMENT 'stories for the whole family, recording podcast with audience, stories with impact, best of',
  `is_pay_as_you_like` tinyint(1) DEFAULT 0,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `max_tickets` int(11) NOT NULL DEFAULT 30,
  `image_path` varchar(500) DEFAULT NULL,
  `gallery_image_1` varchar(500) DEFAULT NULL,
  `gallery_image_2` varchar(500) DEFAULT NULL,
  `audio_preview_path` varchar(500) DEFAULT NULL,
  `audio_title` varchar(255) DEFAULT NULL,
  `audio_transcript` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Event`
--

INSERT INTO `Event` (`event_id`, `venue_id`, `type`, `name`, `slug`, `description`, `performer_name`, `performer_bio`, `language`, `age_group`, `story_type`, `is_pay_as_you_like`, `start_time`, `end_time`, `max_tickets`, `image_path`, `gallery_image_1`, `gallery_image_2`, `audio_preview_path`, `audio_title`, `audio_transcript`) VALUES
(1, 1, 5, 'Winnie de Poeh', 'winnie-de-poeh', 'Join us for a magical storytelling session based on the beloved tales of Winnie the Pooh. Brought to life by an experienced family storyteller, this performance uses puppets, songs, and audience participation to enchant young listeners. Perfect for the youngest members of the family.', 'Marieke van der Werf', NULL, 'NL', '4+', 'stories for the whole family', 0, '2026-07-23 16:00:00', '2026-07-23 17:00:00', 50, '/assets/images/stories/ccd64b520f09f160ebcaa5ded87d27ae.jpeg', '', '', '', NULL, NULL),
(2, 2, 5, 'Omdenken Podcast', 'omdenken-podcast', 'Experience a live podcast recording with a studio audience. Omdenken — the Dutch art of \"flipping\" problems into opportunities — comes to the stage in an interactive session full of surprising perspectives and real audience participation. A unique chance to be part of a live recording.', NULL, NULL, 'NL', '16+', 'recording podcast with audience', 0, '2026-07-23 19:00:00', '2026-07-23 20:15:00', 40, '/assets/images/stories/2ba08353e7c37fc0acdd1b5401b5068b.jpeg', NULL, NULL, NULL, NULL, NULL),
(3, 3, 5, 'The Story of Buurderij Haarlem', 'story-of-buurderij-haarlem', 'How does your food travel from the soil to your plate? In this inspiring session, we dive into the world of short food supply chains. The \"Buurderij\" is not just a market; it\'s a movement connecting neighbors directly with local farmers. Learn how this circular approach is reshaping Haarlem\'s economy and reducing our carbon footprint.', 'Marieke van der Werf', 'Presented by Marieke van der Werf, founder of the Haarlem Green Collective. Marieke was awarded the \"Sustainable Entrepreneur of the Year 2024\" for her work in reducing food waste in North Holland. She has successfully launched three community farming initiatives and consults for the municipality on circular city planning.', 'EN', '16+', 'stories with impact', 1, '2026-07-23 20:30:00', '2026-07-23 21:45:00', 25, '/assets/images/stories/756822a1ee7b7f7085fed9468aa803c2.jpeg', '/assets/images/stories/0be260ee2117fa99f6f951ab2ed9dd32.jpeg', '/assets/images/stories/d3c08e71ce525a48370c9c8168d37134.jpeg', '/assets/audio/buurderij-preview.mp3', '\"The journey of a local apple\" - Interview Excerpt', 'Interviewer:Marieke, what is the biggest misconception people have about buying local?\r\nMarieke:People think it\'s expensive or complicated. But when you cut out the transport and the supermarkets, the farmer gets a fair price, and you get fresher food. It\'s actually a very simple, honest connection.\r\nInterviewer:And how does the Buurderij facilitate that connection?'),
(4, 4, 5, 'Corrie voor kinderen', 'corrie-voor-kinderen', 'An age-appropriate retelling of the remarkable true story of Corrie ten Boom and her family, who hid Jewish people in their Haarlem home during World War II. Presented at the actual Ten Boom house, this experience brings history to life for children in a meaningful and accessible way.', NULL, NULL, 'NL', '10+', 'stories for the whole family', 1, '2026-07-24 16:00:00', '2026-07-24 17:00:00', 35, '/assets/images/stories/fce213146d5fcaae1b32e2728e051f6f.jpeg', NULL, NULL, NULL, NULL, NULL),
(5, 1, 5, 'Winnaars van verhalenvertel wedstrijd', 'winnaars-verhalenvertel-wedstrijd', 'The storytelling contest will be organized in June. The winners present their best original stories live on stage at the Verhalenhuis. A celebration of fresh local talent and the art of oral storytelling. Come discover Haarlem\'s next generation of storytellers.', NULL, NULL, 'NL', '12+', 'best of', 0, '2026-07-24 19:00:00', '2026-07-24 20:30:00', 30, '/assets/images/stories/cb2205716106bbb349b267af663971ab.jpeg', NULL, NULL, NULL, NULL, NULL),
(6, 3, 5, 'Het verhaal van de Oeserzwammerij', 'verhaal-oeserzwammerij', 'Discover the fascinating story of urban mushroom farming in Haarlem. This talk explores how a small circular business is transforming food waste into gourmet oyster mushrooms, contributing to a more sustainable local food system. Inspiring, quirky, and eye-opening.', NULL, NULL, 'NL', '16+', 'stories with impact', 1, '2026-07-24 19:00:00', '2026-07-24 20:15:00', 25, '/assets/images/stories/442407af2369c471bb792666df2503e7.jpeg', NULL, NULL, NULL, NULL, NULL),
(7, 2, 5, 'Flip Thinking Podcast', 'flip-thinking-podcast', 'A live English-language podcast recording exploring \"flip thinking\" — the concept of turning obstacles into opportunities. Join host and author Berthold Gunster as he challenges guests and audience members to reframe their biggest problems. Recorded live for international distribution.', NULL, NULL, 'EN', '16+', 'recording podcast with audience', 0, '2026-07-24 20:30:00', '2026-07-24 21:45:00', 40, '/assets/images/stories/997004ba39a660e70d04636a8ed0f922.jpeg', NULL, NULL, NULL, NULL, NULL),
(8, 5, 5, 'Meneer Anansi', 'meneer-anansi-sat-morning', 'Step into a world of magic and mischief with the legendary Meneer Anansi. In this interactive Dutch-language session, children help Anansi the Spider solve riddles, outsmart tigers, and collect all the stories of the world to bring back to Haarlem. A perfect blend of humor, wisdom, and participation that keeps young minds engaged.', 'Winston \"The Weaver\"', 'Our storyteller, Winston \"The Weaver,\" has been performing traditional folklore across Europe for over 15 years. He was voted \"Best Family Act\" at the Edinburgh Fringe (2023) and has featured on BBC Radio\'s \"Stories for Schools.\" His unique ability to switch seamlessly between Dutch and English makes this event accessible for international and local families alike.', 'NL', '2-102', 'stories for the whole family', 0, '2026-07-25 10:00:00', '2026-07-25 11:00:00', 50, '/assets/images/stories/aae44f3940f6bb32db98ace7cc3e0754.jpeg', NULL, NULL, '/assets/audio/anansi-tiger-preview.mp3', '\"Anansi meets the Tiger\" - Live Recording', 'Winston:Come closer, children. Do you see the web in the corner? That is where Anansi lives...\n[Sound Effect]:*Rustling leaves*\nWinston:One day, Anansi decided he wanted all the wisdom in the world for himself. So he put it in a clay pot. But do you know what happened when he tried to climb the tree?'),
(9, 5, 5, 'Mister Anansi', 'mister-anansi-sat-afternoon', 'Step into a world of magic and mischief with the legendary Mister Anansi. In this interactive English-language session, children help Anansi the Spider solve riddles, outsmart tigers, and collect all the stories of the world to bring back to Haarlem. A perfect blend of humor, wisdom, and participation that keeps young minds engaged.', 'Winston \"The Weaver\"', 'Our storyteller, Winston \"The Weaver,\" has been performing traditional folklore across Europe for over 15 years. He was voted \"Best Family Act\" at the Edinburgh Fringe (2023) and has featured on BBC Radio\'s \"Stories for Schools.\" His unique ability to switch seamlessly between Dutch and English makes this event accessible for international and local families alike.', 'EN', '2-102', 'stories for the whole family', 0, '2026-07-25 15:00:00', '2026-07-25 16:00:00', 50, '/assets/images/stories/2143ce48ae8cca82613f3e0c80d71d92.jpeg', '/assets/images/stories/c49ab911d8adf050fef3d480581f359e.jpeg', '/assets/images/stories/2d348ee9a103a8f7248c53a7e1829db4.jpeg', '', '\"Anansi meets the Tiger\" - Live Recording', 'Winston:Come closer, children. Do you see the web in the corner? That is where Anansi lives...\r\n[Sound Effect]:*Rustling leaves*\r\nWinston:One day, Anansi decided he wanted all the wisdom in the world for himself. So he put it in a clay pot. But do you know what happened when he tried to climb the tree?'),
(10, 2, 5, 'Podcastlast Haarlem Special', 'podcastlast-haarlem-special', 'Podcastlast records a special Haarlem-themed episode live in front of an audience at De Schuur. Featuring local guests, audience questions, and a unique behind-the-scenes look at what makes this city tick. A must for podcast enthusiasts and Haarlem locals alike.', NULL, NULL, 'NL', '12+', 'recording podcast with audience', 0, '2026-07-25 14:00:00', '2026-07-25 15:15:00', 40, '/assets/images/stories/f2eb35fdf14d684e02c6ee5a94f0f0ba.jpeg', NULL, NULL, NULL, NULL, NULL),
(11, 4, 5, 'De geschiedenis van familie ten Boom', 'geschiedenis-familie-ten-boom', 'A deeply moving account of the Ten Boom family\'s extraordinary courage during the Second World War. Told at the very house where Jewish people were hidden, this story of faith, sacrifice, and humanity is presented in Dutch for a mature audience. A reservation is required to guarantee entry.', NULL, NULL, 'NL', '12+', 'stories with impact', 1, '2026-07-25 13:00:00', '2026-07-25 14:30:00', 35, '/assets/images/stories/fb563928c1dc5042675fd095c28b6aa1.jpeg', NULL, NULL, NULL, NULL, NULL),
(12, 5, 5, 'Mister Anansi', 'mister-anansi-sun-morning', 'Step into a world of magic and mischief with the legendary Mister Anansi. In this interactive English-language session, children help Anansi the Spider solve riddles, outsmart tigers, and collect all the stories of the world to bring back to Haarlem. A perfect blend of humor, wisdom, and participation that keeps young minds engaged.', 'Winston \"The Weaver\"', 'Our storyteller, Winston \"The Weaver,\" has been performing traditional folklore across Europe for over 15 years. He was voted \"Best Family Act\" at the Edinburgh Fringe (2023) and has featured on BBC Radio\'s \"Stories for Schools.\" His unique ability to switch seamlessly between Dutch and English makes this event accessible for international and local families alike.', 'EN', '2-102', 'stories for the whole family', 0, '2026-07-26 10:00:00', '2026-07-26 11:00:00', 50, '/assets/images/stories/997b979719d6f659c6680a3a4b0375db.jpeg', '/assets/images/stories/db96e84149734b8bb38867e87a78a1f1.jpeg', '/assets/images/stories/9d8c2d227ddd0370b95ff096c788018b.jpeg', '', '\"Anansi meets the Tiger\" - Live Recording', 'Winston:Come closer, children. Do you see the web in the corner? That is where Anansi lives...\r\n[Sound Effect]:*Rustling leaves*\r\nWinston:One day, Anansi decided he wanted all the wisdom in the world for himself. So he put it in a clay pot. But do you know what happened when he tried to climb the tree?'),
(13, 5, 5, 'Meneer Anansi', 'meneer-anansi-sun-afternoon', 'Step into a world of magic and mischief with the legendary Meneer Anansi. In this interactive Dutch-language session, children help Anansi the Spider solve riddles, outsmart tigers, and collect all the stories of the world to bring back to Haarlem. A perfect blend of humor, wisdom, and participation that keeps young minds engaged.', 'Winston \"The Weaver\"', 'Our storyteller, Winston \"The Weaver,\" has been performing traditional folklore across Europe for over 15 years. He was voted \"Best Family Act\" at the Edinburgh Fringe (2023) and has featured on BBC Radio\'s \"Stories for Schools.\" His unique ability to switch seamlessly between Dutch and English makes this event accessible for international and local families alike.', 'NL', '2-102', 'stories for the whole family', 0, '2026-07-26 15:00:00', '2026-07-26 16:00:00', 50, '/assets/images/stories/ab76b6f7b8e4570906764fd7b0712072.jpeg', NULL, NULL, '/assets/audio/anansi-tiger-preview.mp3', '\"Anansi meets the Tiger\" - Live Recording', 'Winston:Come closer, children. Do you see the web in the corner? That is where Anansi lives...\r\n[Sound Effect]:*Rustling leaves*\r\nWinston:One day, Anansi decided he wanted all the wisdom in the world for himself. So he put it in a clay pot. But do you know what happened when he tried to climb the tree?'),
(14, 4, 5, 'The History of the Ten Boom Family', 'history-ten-boom-family', 'A deeply moving English-language account of the Ten Boom family\'s extraordinary courage during the Second World War. Told at the very house where Jewish people were hidden, this story of faith, sacrifice, and humanity is presented for a mature international audience. A reservation is required to guarantee entry.', NULL, NULL, 'EN', '12+', 'stories with impact', 1, '2026-07-26 13:00:00', '2026-07-26 14:30:00', 35, '/assets/images/stories/8b1cede5daae032ab8b70d8c2ceb7287.jpeg', NULL, NULL, NULL, NULL, NULL),
(15, 1, 5, 'Winners of Storytelling Competition', 'winners-storytelling-competition', 'The storytelling contest will be organized in June. The winners present their best original stories live on stage at the Verhalenhuis. A celebration of fresh local talent and the English-language art of oral storytelling. Come discover Haarlem\'s next generation of storytellers.', NULL, NULL, 'EN', '12+', 'best of', 0, '2026-07-26 16:00:00', '2026-07-26 17:30:00', 30, '/assets/images/stories/62eb5096ef38305169f70c4ff3fa0be0.jpeg', NULL, NULL, NULL, NULL, NULL),
(101, 6, 1, 'Gumbo Kings', 'jazz-gumbo-kings-20260723-1800', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 18:00:00', '2026-07-23 19:00:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(102, 6, 1, 'Evolve', 'jazz-evolve-20260723-1930', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 19:30:00', '2026-07-23 20:30:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(103, 6, 1, 'Ntjam Rosie', 'jazz-ntjam-rosie-20260723-2100', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 21:00:00', '2026-07-23 22:00:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(104, 6, 1, 'Wicked Jazz Sounds', 'jazz-wicked-jazz-sounds-20260723-1800', 'Second Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 18:00:00', '2026-07-23 19:00:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(105, 6, 1, 'Wouter Hamel', 'jazz-wouter-hamel-20260723-1930', 'Second Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 19:30:00', '2026-07-23 20:30:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(106, 6, 1, 'Jonna Frazer', 'jazz-jonna-frazer-20260723-2100', 'Second Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 21:00:00', '2026-07-23 22:00:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(107, 6, 1, 'Karsu', 'jazz-karsu-20260724-1800', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 18:00:00', '2026-07-24 19:00:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(108, 6, 1, 'Uncle Sue', 'jazz-uncle-sue-20260724-1930', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 19:30:00', '2026-07-24 20:30:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(109, 6, 1, 'Chris Allen', 'jazz-chris-allen-20260724-2100', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 21:00:00', '2026-07-24 22:00:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(110, 6, 1, 'Myles Sanko', 'jazz-myles-sanko-20260724-1800', 'Second Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 18:00:00', '2026-07-24 19:00:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(111, 6, 1, 'Ilse Huizinga', 'jazz-ilse-huizinga-20260724-1930', 'Second Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 19:30:00', '2026-07-24 20:30:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(112, 6, 1, 'Eric Vloeimans and Hotspot!', 'jazz-eric-vloeimans-and-hotspot-20260724-2100', 'Second Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 21:00:00', '2026-07-24 22:00:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(113, 6, 1, 'Gare du Nord', 'jazz-gare-du-nord-20260725-1800', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 18:00:00', '2026-07-25 19:00:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(114, 6, 1, 'Rilan & The Bombadiers', 'jazz-rilan-the-bombadiers-20260725-1930', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 19:30:00', '2026-07-25 20:30:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 6, 1, 'Soul Six', 'jazz-soul-six-20260725-2100', 'Main Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 21:00:00', '2026-07-25 22:00:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 6, 1, 'Han Bennink', 'jazz-han-bennink-20260725-1800', 'Third Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 18:00:00', '2026-07-25 19:00:00', 150, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 6, 1, 'The Nordanians', 'jazz-the-nordanians-20260725-1930', 'Third Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 19:30:00', '2026-07-25 20:30:00', 150, NULL, NULL, NULL, NULL, NULL, NULL),
(118, 6, 1, 'Lilith Merlot', 'jazz-lilith-merlot-20260725-2100', 'Third Hall', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 21:00:00', '2026-07-25 22:00:00', 150, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 7, 1, 'Ruis Soundsystem', 'jazz-ruis-soundsystem-20260726-1500', 'Free show', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 15:00:00', '2026-07-26 16:00:00', 1000, NULL, NULL, NULL, NULL, NULL, NULL),
(120, 7, 1, 'Wicked Jazz Sounds', 'jazz-wicked-jazz-sounds-20260726-1600', 'Free show', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 16:00:00', '2026-07-26 17:00:00', 1000, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 7, 1, 'Evolve', 'jazz-evolve-20260726-1700', 'Free show', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 17:00:00', '2026-07-26 18:00:00', 1000, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 7, 1, 'The Nordanians', 'jazz-the-nordanians-20260726-1800', 'Free show', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 18:00:00', '2026-07-26 19:00:00', 1000, NULL, NULL, NULL, NULL, NULL, NULL),
(123, 7, 1, 'Gumbo Kings', 'jazz-gumbo-kings-20260726-1900', 'Free show', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 19:00:00', '2026-07-26 20:00:00', 1000, NULL, NULL, NULL, NULL, NULL, NULL),
(124, 7, 1, 'Gare du Nord', 'jazz-gare-du-nord-20260726-2000', 'Free show', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 20:00:00', '2026-07-26 21:00:00', 1000, NULL, NULL, NULL, NULL, NULL, NULL),
(125, 8, 2, 'Nicky Romero / Afrojack', 'dance-nicky-romero-afrojack-20260724-2000', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 20:00:00', '2026-07-24 21:30:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(126, 9, 2, 'Ti?sto', 'dance-ti-sto-20260724-2200', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 22:00:00', '2026-07-24 23:30:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(127, 10, 2, 'Hardwell', 'dance-hardwell-20260724-2300', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 23:00:00', '2026-07-24 23:59:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(128, 11, 2, 'Armin van Buuren', 'dance-armin-van-buuren-20260724-2200', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 22:00:00', '2026-07-24 23:30:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(129, 12, 2, 'Martin Garrix', 'dance-martin-garrix-20260724-2200', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 22:00:00', '2026-07-24 23:30:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(130, 13, 2, 'Hardwell / Martin Garrix / Armin van Buuren', 'dance-hardwell-martin-garrix-armin-van-buuren-20260725-1400', 'Back2Back session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 14:00:00', '2026-07-25 20:00:00', 2000, NULL, NULL, NULL, NULL, NULL, NULL),
(131, 10, 2, 'Afrojack', 'dance-afrojack-20260725-2200', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 22:00:00', '2026-07-25 23:30:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(132, 8, 2, 'Ti?sto', 'dance-ti-sto-20260725-2100', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 21:00:00', '2026-07-25 22:30:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(133, 9, 2, 'Nicky Romero', 'dance-nicky-romero-20260725-2300', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 23:00:00', '2026-07-25 23:59:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(134, 13, 2, 'Afrojack / Ti?sto / Nicky Romero', 'dance-afrojack-ti-sto-nicky-romero-20260726-1400', 'Back2Back session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 14:00:00', '2026-07-26 20:00:00', 2000, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 10, 2, 'Armin van Buuren', 'dance-armin-van-buuren-20260726-1900', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 19:00:00', '2026-07-26 20:30:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(136, 11, 2, 'Hardwell', 'dance-hardwell-20260726-2100', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 21:00:00', '2026-07-26 22:30:00', 200, NULL, NULL, NULL, NULL, NULL, NULL),
(137, 9, 2, 'Martin Garrix', 'dance-martin-garrix-20260726-1800', 'Club session (price_tbd)', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 18:00:00', '2026-07-26 19:30:00', 300, NULL, NULL, NULL, NULL, NULL, NULL),
(138, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260723-1000', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 10:00:00', '2026-07-23 12:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(139, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260723-1300', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 13:00:00', '2026-07-23 15:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(140, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260723-1600', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 16:00:00', '2026-07-23 18:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(141, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260724-1000', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 10:00:00', '2026-07-24 12:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(142, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260724-1300', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 13:00:00', '2026-07-24 15:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(143, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260724-1600', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 16:00:00', '2026-07-24 18:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(144, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260725-1000', 'Guided walking tou', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 10:00:00', '2026-07-25 12:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(145, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260725-1300', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 13:00:00', '2026-07-25 15:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260725-1600', 'Guided walking tou', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 16:00:00', '2026-07-25 18:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260726-1000', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 10:00:00', '2026-07-26 12:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(148, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260726-1300', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 13:00:00', '2026-07-26 15:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(149, 14, 3, 'A Stroll Through History Guided Tour', 'history-a-stroll-through-history-guided-tour-20260726-1600', 'Guided walking tour', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 16:00:00', '2026-07-26 18:30:00', 12, NULL, NULL, NULL, NULL, NULL, NULL),
(150, 15, 4, 'Cafe de Roemer Festival Menu', 'yummy-cafe-de-roemer-festival-menu-20260723-1800', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 18:00:00', '2026-07-23 19:30:00', 35, NULL, NULL, NULL, NULL, NULL, NULL),
(151, 16, 4, 'Ratatouille Festival Menu', 'yummy-ratatouille-festival-menu-20260723-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 17:00:00', '2026-07-23 19:00:00', 52, NULL, NULL, NULL, NULL, NULL, NULL),
(152, 17, 4, 'Restaurant ML Festival Menu', 'yummy-restaurant-ml-festival-menu-20260723-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 17:00:00', '2026-07-23 19:00:00', 60, NULL, NULL, NULL, NULL, NULL, NULL),
(153, 18, 4, 'Restaurant Fris Festival Menu', 'yummy-restaurant-fris-festival-menu-20260723-1730', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 17:30:00', '2026-07-23 19:00:00', 45, NULL, NULL, NULL, NULL, NULL, NULL),
(154, 19, 4, 'New Vegas Festival Menu', 'yummy-new-vegas-festival-menu-20260723-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 17:00:00', '2026-07-23 18:30:00', 36, NULL, NULL, NULL, NULL, NULL, NULL),
(155, 20, 4, 'Grand Cafe Brinkmann Festival Menu', 'yummy-grand-cafe-brinkmann-festival-menu-20260723-1630', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 16:30:00', '2026-07-23 18:00:00', 100, NULL, NULL, NULL, NULL, NULL, NULL),
(156, 21, 4, 'Urban Frenchy Bistro Toujours Festival Menu', 'yummy-urban-frenchy-bistro-toujours-festival-menu-20260723-1730', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-23 17:30:00', '2026-07-23 19:00:00', 48, NULL, NULL, NULL, NULL, NULL, NULL),
(157, 15, 4, 'Cafe de Roemer Festival Menu', 'yummy-cafe-de-roemer-festival-menu-20260724-1800', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 18:00:00', '2026-07-24 19:30:00', 35, NULL, NULL, NULL, NULL, NULL, NULL),
(158, 16, 4, 'Ratatouille Festival Menu', 'yummy-ratatouille-festival-menu-20260724-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 17:00:00', '2026-07-24 19:00:00', 52, NULL, NULL, NULL, NULL, NULL, NULL),
(159, 17, 4, 'Restaurant ML Festival Menu', 'yummy-restaurant-ml-festival-menu-20260724-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 17:00:00', '2026-07-24 19:00:00', 60, NULL, NULL, NULL, NULL, NULL, NULL),
(160, 18, 4, 'Restaurant Fris Festival Menu', 'yummy-restaurant-fris-festival-menu-20260724-1730', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 17:30:00', '2026-07-24 19:00:00', 45, NULL, NULL, NULL, NULL, NULL, NULL),
(161, 19, 4, 'New Vegas Festival Menu', 'yummy-new-vegas-festival-menu-20260724-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 17:00:00', '2026-07-24 18:30:00', 36, NULL, NULL, NULL, NULL, NULL, NULL),
(162, 20, 4, 'Grand Cafe Brinkmann Festival Menu', 'yummy-grand-cafe-brinkmann-festival-menu-20260724-1630', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 16:30:00', '2026-07-24 18:00:00', 100, NULL, NULL, NULL, NULL, NULL, NULL),
(163, 21, 4, 'Urban Frenchy Bistro Toujours Festival Menu', 'yummy-urban-frenchy-bistro-toujours-festival-menu-20260724-1730', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-24 17:30:00', '2026-07-24 19:00:00', 48, NULL, NULL, NULL, NULL, NULL, NULL),
(164, 15, 4, 'Cafe de Roemer Festival Menu', 'yummy-cafe-de-roemer-festival-menu-20260725-1800', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 18:00:00', '2026-07-25 19:30:00', 35, NULL, NULL, NULL, NULL, NULL, NULL),
(165, 16, 4, 'Ratatouille Festival Menu', 'yummy-ratatouille-festival-menu-20260725-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 17:00:00', '2026-07-25 19:00:00', 52, NULL, NULL, NULL, NULL, NULL, NULL),
(166, 17, 4, 'Restaurant ML Festival Menu', 'yummy-restaurant-ml-festival-menu-20260725-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 17:00:00', '2026-07-25 19:00:00', 60, NULL, NULL, NULL, NULL, NULL, NULL),
(167, 18, 4, 'Restaurant Fris Festival Menu', 'yummy-restaurant-fris-festival-menu-20260725-1730', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 17:30:00', '2026-07-25 19:00:00', 45, NULL, NULL, NULL, NULL, NULL, NULL),
(168, 19, 4, 'New Vegas Festival Menu', 'yummy-new-vegas-festival-menu-20260725-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 17:00:00', '2026-07-25 18:30:00', 36, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 20, 4, 'Grand Cafe Brinkmann Festival Menu', 'yummy-grand-cafe-brinkmann-festival-menu-20260725-1630', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 16:30:00', '2026-07-25 18:00:00', 100, NULL, NULL, NULL, NULL, NULL, NULL),
(170, 21, 4, 'Urban Frenchy Bistro Toujours Festival Menu', 'yummy-urban-frenchy-bistro-toujours-festival-menu-20260725-1730', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-25 17:30:00', '2026-07-25 19:00:00', 48, NULL, NULL, NULL, NULL, NULL, NULL),
(171, 15, 4, 'Cafe de Roemer Festival Menu', 'yummy-cafe-de-roemer-festival-menu-20260726-1800', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 18:00:00', '2026-07-26 19:30:00', 35, NULL, NULL, NULL, NULL, NULL, NULL),
(172, 16, 4, 'Ratatouille Festival Menu', 'yummy-ratatouille-festival-menu-20260726-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 17:00:00', '2026-07-26 19:00:00', 52, NULL, NULL, NULL, NULL, NULL, NULL),
(173, 17, 4, 'Restaurant ML Festival Menu', 'yummy-restaurant-ml-festival-menu-20260726-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 17:00:00', '2026-07-26 19:00:00', 60, NULL, NULL, NULL, NULL, NULL, NULL),
(174, 18, 4, 'Restaurant Fris Festival Menu', 'yummy-restaurant-fris-festival-menu-20260726-1730', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 17:30:00', '2026-07-26 19:00:00', 45, NULL, NULL, NULL, NULL, NULL, NULL),
(175, 19, 4, 'New Vegas Festival Menu', 'yummy-new-vegas-festival-menu-20260726-1700', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 17:00:00', '2026-07-26 18:30:00', 36, NULL, NULL, NULL, NULL, NULL, NULL),
(176, 20, 4, 'Grand Cafe Brinkmann Festival Menu', 'yummy-grand-cafe-brinkmann-festival-menu-20260726-1630', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 16:30:00', '2026-07-26 18:00:00', 100, NULL, NULL, NULL, NULL, NULL, NULL),
(177, 21, 4, 'Urban Frenchy Bistro Toujours Festival Menu', 'yummy-urban-frenchy-bistro-toujours-festival-menu-20260726-1730', 'Festival menu reservation', NULL, NULL, 'EN', 'All ages', NULL, 0, '2026-07-26 17:30:00', '2026-07-26 19:00:00', 48, NULL, NULL, NULL, NULL, NULL, NULL),
(181, 24, 4, 'Ratatouille booking', '', 'adults: 2 children: 2', NULL, NULL, 'NL', 'All ages', NULL, 0, '2026-04-11 21:00:00', '2026-04-11 23:00:00', 20, '/assets/uploads/yummy/restaurants/b3d3e9891fc40495c3eadd6cace50138.png', NULL, NULL, NULL, NULL, NULL),
(183, 26, 4, 'Ratatouille booking', '0d03e1c3e770f01c6e4ab705bf3e06ba5213da5fb503795342582db06a66d0f0', 'adults: 2 children: 2', NULL, NULL, 'NL', 'All ages', NULL, 0, '2026-04-15 21:00:00', '2026-04-15 23:00:00', 20, '/assets/uploads/yummy/restaurants/b3d3e9891fc40495c3eadd6cace50138.png', NULL, NULL, NULL, NULL, NULL),
(184, 27, 4, 'Ratatouille booking', '614f4b53769ec2c138228d08eb8d03d710dd1048ed8d9b3b9fbfee24626c5e79', 'adults: 2 children: 2', NULL, NULL, 'NL', 'All ages', NULL, 0, '2026-04-06 21:00:00', '2026-04-06 23:00:00', 20, '/assets/uploads/yummy/restaurants/b3d3e9891fc40495c3eadd6cace50138.png', NULL, NULL, NULL, NULL, NULL),
(185, 28, 4, 'Ratatouille booking', 'e6d897077cc396ab91620df2dbf425f7dea5f60a19e4c913ce3cd6370e61b7fd', 'adults: 2 children: 2', NULL, NULL, 'NL', 'All ages', NULL, 0, '2026-04-11 17:00:00', '2026-04-11 19:00:00', 20, '/assets/uploads/yummy/restaurants/b3d3e9891fc40495c3eadd6cace50138.png', NULL, NULL, NULL, NULL, NULL),
(186, 29, 4, 'Ratatouille booking', 'a6592ced8663fa07ad0f46e70354b000bf8383f7507ec8b47a410315a6d68066', 'adults: 2 children: 3', NULL, NULL, 'NL', 'All ages', NULL, 0, '2026-04-07 17:00:00', '2026-04-07 19:00:00', 20, '/assets/uploads/yummy/restaurants/b3d3e9891fc40495c3eadd6cace50138.png', NULL, NULL, NULL, NULL, NULL),
(187, 30, 4, 'Ratatouille booking', '2af3bbe9518ddc48b2091d20f961de657969d681e0f95923fd123d2cf9cbf7d6', 'adults: 4 children: 2', NULL, NULL, 'NL', 'All ages', NULL, 0, '2026-04-06 19:00:00', '2026-04-06 21:00:00', 20, '/assets/uploads/yummy/restaurants/b3d3e9891fc40495c3eadd6cace50138.png', NULL, NULL, NULL, NULL, NULL),
(188, 31, 4, 'Name booking', 'd31636bf44dec28eb09199b462db8d751e98d18b33eab37431622ccd030e6f5d', 'adults: 6 children: 8', NULL, NULL, 'NL', 'All ages', NULL, 0, '2026-04-19 16:30:00', '2026-04-19 18:00:00', 20, '/assets/uploads/yummy/restaurants/7f64939f72d9d68a9673f35544833a1e.png', NULL, NULL, NULL, NULL, NULL),
(189, 32, 4, 'Ratatouille booking', '3cb83ac6644ff2cf9a9647c4bb4289cabd70a68ade8107dd921a5f39e6616d4b', 'adults: 2 children: 3', NULL, NULL, 'NL', 'All ages', NULL, 0, '2026-04-20 21:00:00', '2026-04-20 23:00:00', 20, '/assets/uploads/yummy/restaurants/b3d3e9891fc40495c3eadd6cace50138.png', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `festival_events`
--

CREATE TABLE `festival_events` (
  `festival_event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `event_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `extra_info` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `festival_events`
--

INSERT INTO `festival_events` (`festival_event_id`, `title`, `category`, `event_date`, `start_time`, `end_time`, `location`, `extra_info`, `price`, `capacity`, `created_at`) VALUES
(1, 'Gumbo Kings', 'jazz', '2026-07-30', '18:00:00', '19:00:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(2, 'Evolve', 'jazz', '2026-07-30', '19:30:00', '20:30:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(3, 'Ntjam Rosie', 'jazz', '2026-07-30', '21:00:00', '22:00:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(4, 'Wicked Jazz Sounds', 'jazz', '2026-07-30', '18:00:00', '19:00:00', 'Patronaat', 'Second Hall', 10.00, 200, '2026-03-26 14:09:27'),
(5, 'Wouter Hamel', 'jazz', '2026-07-30', '19:30:00', '20:30:00', 'Patronaat', 'Second Hall', 10.00, 200, '2026-03-26 14:09:27'),
(6, 'Jonna Frazer', 'jazz', '2026-07-30', '21:00:00', '22:00:00', 'Patronaat', 'Second Hall', 10.00, 200, '2026-03-26 14:09:27'),
(7, 'Karsu', 'jazz', '2026-07-31', '18:00:00', '19:00:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(8, 'Uncle Sue', 'jazz', '2026-07-31', '19:30:00', '20:30:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(9, 'Chris Allen', 'jazz', '2026-07-31', '21:00:00', '22:00:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(10, 'Myles Sanko', 'jazz', '2026-07-31', '18:00:00', '19:00:00', 'Patronaat', 'Second Hall', 10.00, 200, '2026-03-26 14:09:27'),
(11, 'Ilse Huizinga', 'jazz', '2026-07-31', '19:30:00', '20:30:00', 'Patronaat', 'Second Hall', 10.00, 200, '2026-03-26 14:09:27'),
(12, 'Eric Vloeimans and Hotspot!', 'jazz', '2026-07-31', '21:00:00', '22:00:00', 'Patronaat', 'Second Hall', 10.00, 200, '2026-03-26 14:09:27'),
(13, 'Gare du Nord', 'jazz', '2026-08-01', '18:00:00', '19:00:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(14, 'Rilan & The Bombadiers', 'jazz', '2026-08-01', '19:30:00', '20:30:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(15, 'Soul Six', 'jazz', '2026-08-01', '21:00:00', '22:00:00', 'Patronaat', 'Main Hall', 15.00, 300, '2026-03-26 14:09:27'),
(16, 'Han Bennink', 'jazz', '2026-08-01', '18:00:00', '19:00:00', 'Patronaat', 'Third Hall', 10.00, 150, '2026-03-26 14:09:27'),
(17, 'The Nordanians', 'jazz', '2026-08-01', '19:30:00', '20:30:00', 'Patronaat', 'Third Hall', 10.00, 150, '2026-03-26 14:09:27'),
(18, 'Lilith Merlot', 'jazz', '2026-08-01', '21:00:00', '22:00:00', 'Patronaat', 'Third Hall', 10.00, 150, '2026-03-26 14:09:27'),
(19, 'Ruis Soundsystem', 'jazz', '2026-08-02', '15:00:00', '16:00:00', 'Grote Markt', 'Free show', 0.00, NULL, '2026-03-26 14:09:27'),
(20, 'Wicked Jazz Sounds', 'jazz', '2026-08-02', '16:00:00', '17:00:00', 'Grote Markt', 'Free show', 0.00, NULL, '2026-03-26 14:09:27'),
(21, 'Evolve', 'jazz', '2026-08-02', '17:00:00', '18:00:00', 'Grote Markt', 'Free show', 0.00, NULL, '2026-03-26 14:09:27'),
(22, 'The Nordanians', 'jazz', '2026-08-02', '18:00:00', '19:00:00', 'Grote Markt', 'Free show', 0.00, NULL, '2026-03-26 14:09:27'),
(23, 'Gumbo Kings', 'jazz', '2026-08-02', '19:00:00', '20:00:00', 'Grote Markt', 'Free show', 0.00, NULL, '2026-03-26 14:09:27'),
(24, 'Gare du Nord', 'jazz', '2026-08-02', '20:00:00', '21:00:00', 'Grote Markt', 'Free show', 0.00, NULL, '2026-03-26 14:09:27');

-- --------------------------------------------------------

--
-- Table structure for table `festival_event_tickets`
--

CREATE TABLE `festival_event_tickets` (
  `festival_event_ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `festival_event_ticket_type_id` int(11) NOT NULL,
  `qr_token` varchar(128) NOT NULL,
  `is_scanned` tinyint(1) NOT NULL DEFAULT 0,
  `scanned_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `ticket_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `festival_event_tickets`
--

INSERT INTO `festival_event_tickets` (`festival_event_ticket_id`, `user_id`, `festival_event_ticket_type_id`, `qr_token`, `is_scanned`, `scanned_at`, `created_at`, `ticket_code`) VALUES
(10, 1, 1, 'a1b2c3d4e5f60718293a4b5c6d7e8f90', 1, '2026-04-05 20:46:24', '2026-04-05 20:44:41', 'HF-NEW1'),
(11, 1, 1, 'b7c4d9e2f1a6835c9d0e4f7a1b2c3d4e', 1, '2026-04-05 21:24:22', '2026-04-05 21:22:36', 'HF-NEW2'),
(12, 1, 1, 'b7c4d9e2f1a6835c9d0e4f7a1b2c3e5t', 1, '2026-04-05 21:25:45', '2026-04-05 21:25:15', 'HF-NEW3'),
(13, 1, 1, 'b7c4d9e2f1a6835c9d0e4f7a1b2c3d4t', 1, '2026-04-05 21:42:30', '2026-04-05 21:41:41', 'HF-NEW6'),
(36, 15, 139, '0c5008a761f8499ba7f5dbcfa2ed423adf310327e1a8964e', 1, '2026-04-06 16:36:21', '2026-04-06 16:35:57', 'HF-D172DA'),
(37, 15, 104, '848489e60dcf2f389330f11d822bac093b8145fcc8b1ad00', 0, NULL, '2026-04-06 16:35:57', 'HF-EB9AC6'),
(38, 15, 104, 'a9aa17d563d1ad0b4011d2daf547a7e804a4e60df1887970', 0, NULL, '2026-04-06 16:35:57', 'HF-71F4D6'),
(39, 15, 101, 'a0f7276493002e4a8144e8e1595fca589b9c87195ae19706', 0, NULL, '2026-04-06 16:35:57', 'HF-8A5E23'),
(40, 15, 101, '1ab3cc5bdbc336dc7bd6a6e6d792fcd527068e4f946b790c', 0, NULL, '2026-04-06 16:35:57', 'HF-815AB8');

-- --------------------------------------------------------

--
-- Table structure for table `festival_event_ticket_types`
--

CREATE TABLE `festival_event_ticket_types` (
  `festival_event_ticket_type_id` int(11) NOT NULL,
  `festival_event_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `is_pay_as_you_like` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `festival_event_ticket_types`
--

INSERT INTO `festival_event_ticket_types` (`festival_event_ticket_type_id`, `festival_event_id`, `name`, `price`, `is_pay_as_you_like`) VALUES
(1, 1, 'Regular', 20.00, 0);

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
(1, '10:00 AM', 5.00, 15),
(2, '01:00 PM', 12.50, 10),
(3, '04:00 PM', 60.00, 8);

-- --------------------------------------------------------

--
-- Table structure for table `history_ticket_prices`
--

CREATE TABLE `history_ticket_prices` (
  `id` int(11) NOT NULL,
  `ticket_type` enum('individual','family') NOT NULL,
  `price` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `history_ticket_prices`
--

INSERT INTO `history_ticket_prices` (`id`, `ticket_type`, `price`) VALUES
(1, 'individual', 12.50),
(2, 'family', 60.00);

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
-- Table structure for table `Invoice`
--

CREATE TABLE `Invoice` (
  `invoice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `vat_percentage` decimal(5,2) NOT NULL DEFAULT 9.00 COMMENT 'Culture events = 9% VAT',
  `client_name` varchar(255) DEFAULT NULL,
  `client_address` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Invoice`
--

INSERT INTO `Invoice` (`invoice_id`, `order_id`, `invoice_number`, `invoice_date`, `total_amount`, `vat_percentage`, `client_name`, `client_address`) VALUES
(1, 2, 'INV-2026-000002', '2026-04-04 13:07:44', 15.00, 9.00, 'Enes Veli Yigit', 'ENESVELIYIGIT@GMAIL.COM'),
(2, 3, 'INV-2026-000003', '2026-04-04 14:43:24', 92.00, 9.00, 'Enes Veli Yigit', 'enesveliyigit0@gmail.com'),
(3, 4, 'INV-2026-000004', '2026-04-05 13:05:43', 80.38, 9.00, 'Enes Veli Yigit', 'enesveliyigit0@gmail.com'),
(4, 10, 'INV-2026-000010', '2026-04-06 23:57:52', 50.00, 9.00, 'achraf derouich', 'achraf@admin.com'),
(5, 11, 'INV-2026-000011', '2026-04-06 23:59:26', 60.00, 9.00, 'Timofii Sadko', 'tim.sadko@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `jazz_experiences`
--

CREATE TABLE `jazz_experiences` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_experiences`
--

INSERT INTO `jazz_experiences` (`id`, `title`, `description`, `image_path`, `sort_order`, `is_active`) VALUES
(1, 'Jazz & Drinks', 'Soft instrumental sets paired with cocktails and lounge seating. Feels like a classy evening in a downtown bar.', '/assets/uploads/jazz/experiences/1773540446_JazzExperience2.png', 2, 1),
(2, 'Vinyl Sessions', 'Rediscover rare jazz records curated by local vinyl experts. Feels like stepping into a vintage record store.', '/assets/uploads/jazz/experiences/1773540485_JazzExperience1.png', 3, 1),
(3, 'Sunset Stage', 'Outdoor performances with golden-hour vibes. Feels like a perfect summer evening soundtrack.', '/assets/uploads/jazz/experiences/1773540473_JazzExperience5.png', 4, 1),
(5, 'Late Night Chill Jam', 'Improvised jam sessions guided by top musicians in the festival. Feels like a smoky underground room.', '/assets/uploads/jazz/experiences/1773652237_gareDuNordHero.png', 1, 1),
(8, 'Rythm And Coffe', 'Start your morning with mellow live jazz performed in cozy café corners across Haarlem. Feels like: smooth jazz floats through the air.', '/assets/uploads/jazz/experiences/1773540454_JazzExperience3.png', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_hero`
--

CREATE TABLE `jazz_hero` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_hero`
--

INSERT INTO `jazz_hero` (`id`, `title`, `subtitle`, `image_path`, `is_active`) VALUES
(1, 'Haarlem Jazz', 'Experience the rhythm of Haarlem’s vibrant jazz scene.', '/assets/uploads/jazz/hero/1774369165_wallpaperflare.com_wallpaper.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_intro_content`
--

CREATE TABLE `jazz_intro_content` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_intro_content`
--

INSERT INTO `jazz_intro_content` (`id`, `title`, `description`) VALUES
(1, 'Welcome to Haarlem Jazz', 'Haarlem Jazz celebrates soulful melodies, late-night sessions, and vibrant creativity of local and international artists.');

-- --------------------------------------------------------

--
-- Table structure for table `jazz_locations`
--

CREATE TABLE `jazz_locations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `google_maps_embed_url` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_locations`
--

INSERT INTO `jazz_locations` (`id`, `name`, `address`, `google_maps_embed_url`, `is_active`) VALUES
(1, 'Patronaat', 'Zijlsingel 2, Haarlem', 'https://www.google.com/maps?q=Patronaat,+Haarlem&output=embed', 1),
(2, 'Grote Markt', 'Grote Markt, 2011 RD Haarlem', 'https://www.google.com/maps?q=Grote+Markt,+2011+RD+Haarlem&output=embed', 1);

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
  `image_path` varchar(255) DEFAULT NULL,
  `performance_style` varchar(150) DEFAULT NULL,
  `event_date_text` varchar(100) DEFAULT NULL,
  `event_time_text` varchar(100) DEFAULT NULL,
  `venue_name` varchar(150) DEFAULT NULL,
  `venue_address` varchar(255) DEFAULT NULL,
  `price_text` varchar(50) DEFAULT NULL,
  `note_text` varchar(255) DEFAULT NULL,
  `audio_url` varchar(255) DEFAULT NULL,
  `hero_image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_performers`
--

INSERT INTO `jazz_performers` (`id`, `name`, `bio`, `sort_order`, `is_active`, `image_path`, `performance_style`, `event_date_text`, `event_time_text`, `venue_name`, `venue_address`, `price_text`, `note_text`, `audio_url`, `hero_image_path`) VALUES
(1, 'Evolve', 'wneqifhskjhdfwe', 1, 1, '/assets/uploads/jazz/performers/1773533302_GareDuNord.png', 'Chill', 'Thursday', '20.00', 'Patronaat - Main Hall', 'Zijlsingel 2, 2013 DN Haarlem', '€15,90', 'baducjkdsca', '', '/assets/uploads/jazz/performers/1775428465_Screenshot 2026-04-06 at 00.34.18.png'),
(2, 'Fox & The Mayors', '', 2, 1, '/assets/uploads/jazz/performers/1775427024_Screenshot 2026-04-06 at 00.10.13.png', 'Smooth, expressive', '', '', '', '', '', '', '', NULL),
(3, 'Gare du Nord', 'Gare du Nord emerged as a Dutch-Belgian lounge-jazz collective known for mixing smoky soul elements with cinematic jazz grooves. Over the years, the group released several successful albums that shaped their recognizable late-night sound. Their collaborations with guest vocalists and instrumentalists helped refine the warm, intimate energy they bring to the stage.', 3, 1, '/assets/uploads/jazz/performers/1773534810_1773533302_GareDuNord.png', 'Smooth, expressive, intimate', 'Thursday', '18:00 - 19:00', 'Patronaat - Main Hall', 'Zijlsingel 2, 2013 DN Haarlem', '€15,90', 'Also available for FREE on Sunday at Grote Markt.', '', '/assets/uploads/jazz/performers/1773537511_gareDuNordHero.png'),
(4, 'Gumbo Kings', '', 4, 1, '/assets/uploads/jazz/performers/1773540222_GumboKings.png', '', '', '', '', '', '', '', '', '/assets/uploads/jazz/performers/1773540285_2ade9cbd4cd817824d3d1ed94771912c.jpg'),
(5, 'Han Bennink', '', 5, 1, '/assets/uploads/jazz/performers/1773540676_HanBenink.png', '', '', '', '', '', '', '', '', NULL),
(6, 'Jonna Frazer', '', 6, 1, '/assets/uploads/jazz/performers/1773540698_JonnaFrazer.png', '', '', '', '', '', '', '', '', NULL),
(7, 'Chris Allen', '', 7, 1, '/assets/uploads/jazz/performers/1773540716_ChrisAllen.png', '', '', '', '', '', '', '', '', NULL),
(8, 'Lilith Merlot', '', 8, 1, '/assets/uploads/jazz/performers/1773540729_LilithMerlot.png', '', '', '', '', '', '', '', '', NULL),
(9, 'Myles Sanko', '', 9, 1, '/assets/uploads/jazz/performers/1773540744_MylesSanko.png', '', '', '', '', '', '', '', '', NULL),
(13, 'Soul Six', '', 13, 1, '/assets/uploads/jazz/performers/1773540813_SoulSix.png', '', '', '', '', '', '', '', '', NULL),
(14, 'The Family XL', '', 14, 1, '/assets/uploads/jazz/performers/1773540829_TheFamilyXl.png', '', '', '', '', '', '', '', '', NULL),
(15, 'The Nordanians', '', 15, 1, '/assets/uploads/jazz/performers/1773540846_TheNordanians.png', '', '', '', '', '', '', '', '', NULL),
(17, 'Uncle Sue', '', 17, 1, '/assets/uploads/jazz/performers/1773540894_UncleSue.png', '', '', '', '', '', '', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_performer_appearances`
--

CREATE TABLE `jazz_performer_appearances` (
  `id` int(11) NOT NULL,
  `performer_id` int(11) NOT NULL,
  `day_text` varchar(50) NOT NULL,
  `time_text` varchar(50) NOT NULL,
  `location_text` varchar(255) NOT NULL,
  `note_text` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_performer_appearances`
--

INSERT INTO `jazz_performer_appearances` (`id`, `performer_id`, `day_text`, `time_text`, `location_text`, `note_text`, `sort_order`) VALUES
(3, 3, 'Thursday', '18:00 - 19:00', 'Patronaat - Main Hall', '', 1),
(4, 3, 'Sunday', '20:00 - 21:00', 'Grote Markt (Free Show)', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_performer_highlights`
--

CREATE TABLE `jazz_performer_highlights` (
  `id` int(11) NOT NULL,
  `performer_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_performer_highlights`
--

INSERT INTO `jazz_performer_highlights` (`id`, `performer_id`, `title`, `description`, `sort_order`) VALUES
(5, 3, 'Formation and Early Success', 'Since their formation, Gare du Nord built a strong reputation through atmospheric live performances and a distinctive blend of smooth jazz, soul, and lounge influences. Their stylish sound quickly attracted attention from audiences looking for intimate and elegant festival experiences.', 1),
(6, 3, 'Debut Release', 'Their early releases introduced listeners to a warm, cinematic sound built on expressive vocals, mellow grooves, and refined instrumentation. This helped establish Gare du Nord as a recognizable name within the Dutch lounge-jazz scene.', 2),
(7, 3, 'Growing Recognition', 'As their popularity increased, Gare du Nord appeared at a wide range of venues and cultural events, gaining recognition for performances that balance emotion, groove, and sophistication. Their music became closely associated with stylish late-evening festival settings.', 3),
(8, 3, 'International Appeal', 'Gare du Nord reached audiences beyond the Netherlands through recordings, collaborations, and international performances. Their elegant mix of jazz, soul, and lounge textures gave them a broad appeal and a lasting presence in the European music scene.', 4),
(10, 4, 'rhythm & blues', 'Gumbo Kings are a five-piece Dutch band with a modern take on soul, rhythm & blues, and roots music. Their live sound mixes the groove of New Orleans funk, the grit of blues, and a stylish, energetic stage presence. Official band material describes them as a sharply dressed group with their own modern view on soul and rhythm & blues, while other profiles describe their sound as blending New Orleans funk, Delta blues, and Memphis-style melodies.\r\n', -2);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_performer_locations`
--

CREATE TABLE `jazz_performer_locations` (
  `id` int(11) NOT NULL,
  `performer_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_performer_locations`
--

INSERT INTO `jazz_performer_locations` (`id`, `performer_id`, `location_id`, `sort_order`) VALUES
(3, 3, 1, 1),
(4, 3, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_performer_tracks`
--

CREATE TABLE `jazz_performer_tracks` (
  `id` int(11) NOT NULL,
  `performer_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `release_date_text` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `listen_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_performer_tracks`
--

INSERT INTO `jazz_performer_tracks` (`id`, `performer_id`, `title`, `release_date_text`, `description`, `image_path`, `listen_url`, `sort_order`) VALUES
(1, 3, 'Sex \'n\' jazz', '4 May 2007', 'Seductive groove-jazz classic', NULL, '', 1),
(2, 3, 'Lilywhite Soul', '16 September 2011', 'Velvet lounge-soul shimmer', NULL, '', 2),
(5, 4, 'In The Dark', '2018', 'ne fjbjfb', '', 'jqwfrbkwf', 1),
(6, 4, 'Hotel Belvédère', '2018', 'erfhiebfnw', '', 'jwbjjwbjw', 5);

-- --------------------------------------------------------

--
-- Table structure for table `jazz_recommendations`
--

CREATE TABLE `jazz_recommendations` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jazz_recommendations`
--

INSERT INTO `jazz_recommendations` (`id`, `title`, `description`, `url`, `image_path`, `sort_order`, `is_active`) VALUES
(1, 'A Stroll Through History', 'Walked along your through historic Haarlem with local storytellers sharing tales of the city\'s rich past.', '/history', '/assets/uploads/jazz/recommendations/1773540983_stroll.png', 1, 1),
(5, 'Stories', 'Immerse yourself in Haarlem\'s spoken-word acts and storytelling.', '/stories', '/assets/uploads/jazz/recommendations/1773541019_StoriesRecommendation.png', 2, 1),
(6, 'Yummy!', 'Explore local food and culinary experiences in Haarlem.', '/yummy', '/assets/uploads/jazz/recommendations/1773541051_Yummy!.png', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

CREATE TABLE `Order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Order`
--

INSERT INTO `Order` (`order_id`, `user_id`, `order_date`, `status`, `payment_method`) VALUES
(1, 8, '2026-04-04 12:30:19', 'paid', 'ideal'),
(2, 8, '2026-04-04 13:07:44', 'paid', 'credit_card'),
(3, 8, '2026-04-04 14:43:24', 'paid', 'paypal'),
(4, 8, '2026-04-05 13:05:43', 'paid', 'credit_card'),
(5, 2, '2026-04-06 11:49:14', 'pending', 'credit_card'),
(6, 2, '2026-04-06 11:51:49', 'pending', 'credit_card'),
(7, 2, '2026-04-06 11:56:24', 'pending', 'credit_card'),
(8, 2, '2026-04-06 11:57:51', 'pending', 'credit_card'),
(9, 2, '2026-04-06 23:43:02', 'pending', 'credit_card'),
(10, 2, '2026-04-06 23:57:52', 'paid', 'credit_card'),
(11, 5, '2026-04-06 23:59:26', 'paid', 'credit_card');

-- --------------------------------------------------------

--
-- Table structure for table `OrderItem`
--

CREATE TABLE `OrderItem` (
  `item_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `OrderItem`
--

INSERT INTO `OrderItem` (`item_id`, `type_id`, `order_id`, `quantity`, `unit_price`) VALUES
(1, 5, 2, 1, 15.00),
(2, 5, 3, 1, 77.00),
(3, 19, 3, 2, 7.50),
(4, 5, 4, 4, 5.00),
(5, 6, 4, 1, 10.00),
(6, 1, 4, 1, 6.00),
(7, 7, 4, 1, 9.38),
(8, 143, 4, 1, 17.50),
(9, 138, 4, 1, 17.50),
(10, 101, 5, 1, 15.00),
(11, 101, 6, 1, 15.00),
(12, 101, 7, 1, 15.00),
(13, 101, 8, 1, 15.00),
(15, 178, 10, 1, 50.00),
(16, 179, 11, 1, 60.00);

-- --------------------------------------------------------

--
-- Table structure for table `PasswordResetToken`
--

CREATE TABLE `PasswordResetToken` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `activated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Ticket`
--

CREATE TABLE `Ticket` (
  `ticket_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL COMMENT 'Secured hash - not a plain ID',
  `is_scanned` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Ticket`
--

INSERT INTO `Ticket` (`ticket_id`, `order_id`, `type_id`, `barcode`, `is_scanned`) VALUES
(1, 2, 5, '73e5fcc1f892d424d88fee18df23869ba2ab0f40b323795069f7a49d11e2a6c2', 0),
(2, 3, 5, 'b1422480999148cba3bfbd7507c81584f3d13fc127b4d048f55f06745a28140c', 0),
(3, 3, 19, '12918c9bc5668131db58b5b5b554995e6110cb4eb525f3c8f02834a8cfd3aaa9', 0),
(4, 3, 19, '2ecdacf07b04021d978dec65664d54d592429a9db7422e84fdce96e7b1492f23', 0),
(5, 4, 5, 'ffa3448cccd71381bc224d093efdd8adaf55185b3254f05bd0bb63c943dbb628', 0),
(6, 4, 5, '715ed56ee9bd426502011f1ba6f6de81004b9aa29711d665605721cf41d7fea1', 0),
(7, 4, 5, '61fac25d288968973f6966a528f54a6236fbdf2e3810e5509e8c16e5ae18cc0d', 0),
(8, 4, 5, 'dda05f32e23c0c45f43fe6f6de0bb7ac774d1cc019275a36b861cafa53d339bb', 0),
(9, 4, 6, 'f78ca9f1e30adcec3df97b466ed689552a12a53938d8260c05a43cd38f29418f', 0),
(10, 4, 1, '0d97f860c9cf4d36309674b7fdbbf34d01030b464f526e70253bb8227393df74', 0),
(11, 4, 7, '4429640785122f5e3517520ed3bd635a5fe6e5a0660df87250ea7ee6b9ddfbd4', 0),
(12, 4, 143, 'f0db781509608d82b86f8c021b4c6018a4b8c85465a3211518cc0289d4e6c11a', 0),
(13, 4, 138, '34f372ef8864bd482006292673e835a2c02466cc7646120877b68f07f29e5c20', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Ticket_Type`
--

CREATE TABLE `Ticket_Type` (
  `type_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'e.g. Regular Ticket, HaarlemPas',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '0.00 = pay as you like',
  `is_pay_as_you_like` tinyint(1) DEFAULT 0,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Ticket_Type`
--

INSERT INTO `Ticket_Type` (`type_id`, `event_id`, `name`, `price`, `is_pay_as_you_like`, `start_time`, `end_time`) VALUES
(1, 1, 'Regular Ticket', 6.00, 0, '2026-07-23 16:00:00', '2026-07-23 17:00:00'),
(2, 1, 'HaarlemPas (25% off)', 4.50, 0, '2026-07-23 16:00:00', '2026-07-23 17:00:00'),
(3, 2, 'Regular Ticket', 12.50, 0, '2026-07-23 19:00:00', '2026-07-23 20:15:00'),
(4, 2, 'HaarlemPas (25% off)', 9.38, 0, '2026-07-23 19:00:00', '2026-07-23 20:15:00'),
(5, 3, 'Pay as you like', 0.00, 1, '2026-07-23 20:30:00', '2026-07-23 21:45:00'),
(6, 4, 'Pay as you like', 0.00, 1, '2026-07-24 16:00:00', '2026-07-24 17:00:00'),
(7, 5, 'Regular Ticket', 12.50, 0, '2026-07-24 19:00:00', '2026-07-24 20:30:00'),
(8, 5, 'HaarlemPas (25% off)', 9.38, 0, '2026-07-24 19:00:00', '2026-07-24 20:30:00'),
(9, 6, 'Pay as you like', 0.00, 1, '2026-07-24 19:00:00', '2026-07-24 20:15:00'),
(10, 7, 'Regular Ticket', 12.50, 0, '2026-07-24 20:30:00', '2026-07-24 21:45:00'),
(11, 7, 'HaarlemPas (25% off)', 9.38, 0, '2026-07-24 20:30:00', '2026-07-24 21:45:00'),
(12, 8, 'Regular Ticket', 10.00, 0, '2026-07-25 10:00:00', '2026-07-25 11:00:00'),
(13, 8, 'HaarlemPas (25% off)', 7.50, 0, '2026-07-25 10:00:00', '2026-07-25 11:00:00'),
(14, 9, 'Regular Ticket', 10.00, 0, '2026-07-25 15:00:00', '2026-07-25 16:00:00'),
(15, 9, 'HaarlemPas (25% off)', 7.50, 0, '2026-07-25 15:00:00', '2026-07-25 16:00:00'),
(16, 10, 'Regular Ticket', 12.50, 0, '2026-07-25 14:00:00', '2026-07-25 15:15:00'),
(17, 10, 'HaarlemPas (25% off)', 9.38, 0, '2026-07-25 14:00:00', '2026-07-25 15:15:00'),
(18, 11, 'Pay as you like', 0.00, 1, '2026-07-25 13:00:00', '2026-07-25 14:30:00'),
(19, 12, 'Regular Ticket', 10.00, 0, '2026-07-26 10:00:00', '2026-07-26 11:00:00'),
(20, 12, 'HaarlemPas (25% off)', 7.50, 0, '2026-07-26 10:00:00', '2026-07-26 11:00:00'),
(21, 13, 'Regular Ticket', 10.00, 0, '2026-07-26 15:00:00', '2026-07-26 16:00:00'),
(22, 13, 'HaarlemPas (25% off)', 7.50, 0, '2026-07-26 15:00:00', '2026-07-26 16:00:00'),
(23, 14, 'Pay as you like', 0.00, 1, '2026-07-26 13:00:00', '2026-07-26 14:30:00'),
(24, 15, 'Regular Ticket', 12.50, 0, '2026-07-26 16:00:00', '2026-07-26 17:30:00'),
(25, 15, 'HaarlemPas (25% off)', 9.38, 0, '2026-07-26 16:00:00', '2026-07-26 17:30:00'),
(101, 101, 'Regular Ticket', 15.00, 0, '2026-07-23 18:00:00', '2026-07-23 19:00:00'),
(102, 102, 'Regular Ticket', 15.00, 0, '2026-07-23 19:30:00', '2026-07-23 20:30:00'),
(103, 103, 'Regular Ticket', 15.00, 0, '2026-07-23 21:00:00', '2026-07-23 22:00:00'),
(104, 104, 'Regular Ticket', 10.00, 0, '2026-07-23 18:00:00', '2026-07-23 19:00:00'),
(105, 105, 'Regular Ticket', 10.00, 0, '2026-07-23 19:30:00', '2026-07-23 20:30:00'),
(106, 106, 'Regular Ticket', 10.00, 0, '2026-07-23 21:00:00', '2026-07-23 22:00:00'),
(107, 107, 'Regular Ticket', 15.00, 0, '2026-07-24 18:00:00', '2026-07-24 19:00:00'),
(108, 108, 'Regular Ticket', 15.00, 0, '2026-07-24 19:30:00', '2026-07-24 20:30:00'),
(109, 109, 'Regular Ticket', 15.00, 0, '2026-07-24 21:00:00', '2026-07-24 22:00:00'),
(110, 110, 'Regular Ticket', 10.00, 0, '2026-07-24 18:00:00', '2026-07-24 19:00:00'),
(111, 111, 'Regular Ticket', 10.00, 0, '2026-07-24 19:30:00', '2026-07-24 20:30:00'),
(112, 112, 'Regular Ticket', 10.00, 0, '2026-07-24 21:00:00', '2026-07-24 22:00:00'),
(113, 113, 'Regular Ticket', 15.00, 0, '2026-07-25 18:00:00', '2026-07-25 19:00:00'),
(114, 114, 'Regular Ticket', 15.00, 0, '2026-07-25 19:30:00', '2026-07-25 20:30:00'),
(115, 115, 'Regular Ticket', 15.00, 0, '2026-07-25 21:00:00', '2026-07-25 22:00:00'),
(116, 116, 'Regular Ticket', 10.00, 0, '2026-07-25 18:00:00', '2026-07-25 19:00:00'),
(117, 117, 'Regular Ticket', 10.00, 0, '2026-07-25 19:30:00', '2026-07-25 20:30:00'),
(118, 118, 'Regular Ticket', 10.00, 0, '2026-07-25 21:00:00', '2026-07-25 22:00:00'),
(119, 119, 'Regular Ticket', 0.00, 0, '2026-07-26 15:00:00', '2026-07-26 16:00:00'),
(120, 120, 'Regular Ticket', 0.00, 0, '2026-07-26 16:00:00', '2026-07-26 17:00:00'),
(121, 121, 'Regular Ticket', 0.00, 0, '2026-07-26 17:00:00', '2026-07-26 18:00:00'),
(122, 122, 'Regular Ticket', 0.00, 0, '2026-07-26 18:00:00', '2026-07-26 19:00:00'),
(123, 123, 'Regular Ticket', 0.00, 0, '2026-07-26 19:00:00', '2026-07-26 20:00:00'),
(124, 124, 'Regular Ticket', 0.00, 0, '2026-07-26 20:00:00', '2026-07-26 21:00:00'),
(125, 125, 'Regular Ticket', 0.00, 0, '2026-07-24 20:00:00', '2026-07-24 21:30:00'),
(126, 126, 'Regular Ticket', 0.00, 0, '2026-07-24 22:00:00', '2026-07-24 23:30:00'),
(127, 127, 'Regular Ticket', 0.00, 0, '2026-07-24 23:00:00', '2026-07-24 23:59:00'),
(128, 128, 'Regular Ticket', 0.00, 0, '2026-07-24 22:00:00', '2026-07-24 23:30:00'),
(129, 129, 'Regular Ticket', 0.00, 0, '2026-07-24 22:00:00', '2026-07-24 23:30:00'),
(130, 130, 'Regular Ticket', 0.00, 0, '2026-07-25 14:00:00', '2026-07-25 20:00:00'),
(131, 131, 'Regular Ticket', 0.00, 0, '2026-07-25 22:00:00', '2026-07-25 23:30:00'),
(132, 132, 'Regular Ticket', 0.00, 0, '2026-07-25 21:00:00', '2026-07-25 22:30:00'),
(133, 133, 'Regular Ticket', 0.00, 0, '2026-07-25 23:00:00', '2026-07-25 23:59:00'),
(134, 134, 'Regular Ticket', 0.00, 0, '2026-07-26 14:00:00', '2026-07-26 20:00:00'),
(135, 135, 'Regular Ticket', 0.00, 0, '2026-07-26 19:00:00', '2026-07-26 20:30:00'),
(136, 136, 'Regular Ticket', 0.00, 0, '2026-07-26 21:00:00', '2026-07-26 22:30:00'),
(137, 137, 'Regular Ticket', 0.00, 0, '2026-07-26 18:00:00', '2026-07-26 19:30:00'),
(138, 138, 'Regular Ticket', 17.50, 0, '2026-07-23 10:00:00', '2026-07-23 12:30:00'),
(139, 139, 'Regular Ticket', 17.50, 0, '2026-07-23 13:00:00', '2026-07-23 15:30:00'),
(140, 140, 'Regular Ticket', 17.50, 0, '2026-07-23 16:00:00', '2026-07-23 18:30:00'),
(141, 141, 'Regular Ticket', 17.50, 0, '2026-07-24 10:00:00', '2026-07-24 12:30:00'),
(142, 142, 'Regular Ticket', 17.50, 0, '2026-07-24 13:00:00', '2026-07-24 15:30:00'),
(143, 143, 'Regular Ticket', 17.50, 0, '2026-07-24 16:00:00', '2026-07-24 18:30:00'),
(144, 144, 'Regular Ticket', 17.50, 0, '2026-07-25 10:00:00', '2026-07-25 12:30:00'),
(145, 145, 'Regular Ticket', 17.50, 0, '2026-07-25 13:00:00', '2026-07-25 15:30:00'),
(146, 146, 'Regular Ticket', 17.50, 0, '2026-07-25 16:00:00', '2026-07-25 18:30:00'),
(147, 147, 'Regular Ticket', 17.50, 0, '2026-07-26 10:00:00', '2026-07-26 12:30:00'),
(148, 148, 'Regular Ticket', 17.50, 0, '2026-07-26 13:00:00', '2026-07-26 15:30:00'),
(149, 149, 'Regular Ticket', 17.50, 0, '2026-07-26 16:00:00', '2026-07-26 18:30:00'),
(150, 150, 'Regular Ticket', 35.00, 0, '2026-07-23 18:00:00', '2026-07-23 19:30:00'),
(151, 151, 'Regular Ticket', 45.00, 0, '2026-07-23 17:00:00', '2026-07-23 19:00:00'),
(152, 152, 'Regular Ticket', 45.00, 0, '2026-07-23 17:00:00', '2026-07-23 19:00:00'),
(153, 153, 'Regular Ticket', 45.00, 0, '2026-07-23 17:30:00', '2026-07-23 19:00:00'),
(154, 154, 'Regular Ticket', 35.00, 0, '2026-07-23 17:00:00', '2026-07-23 18:30:00'),
(155, 155, 'Regular Ticket', 35.00, 0, '2026-07-23 16:30:00', '2026-07-23 18:00:00'),
(156, 156, 'Regular Ticket', 35.00, 0, '2026-07-23 17:30:00', '2026-07-23 19:00:00'),
(157, 157, 'Regular Ticket', 35.00, 0, '2026-07-24 18:00:00', '2026-07-24 19:30:00'),
(158, 158, 'Regular Ticket', 45.00, 0, '2026-07-24 17:00:00', '2026-07-24 19:00:00'),
(159, 159, 'Regular Ticket', 45.00, 0, '2026-07-24 17:00:00', '2026-07-24 19:00:00'),
(160, 160, 'Regular Ticket', 45.00, 0, '2026-07-24 17:30:00', '2026-07-24 19:00:00'),
(161, 161, 'Regular Ticket', 35.00, 0, '2026-07-24 17:00:00', '2026-07-24 18:30:00'),
(162, 162, 'Regular Ticket', 35.00, 0, '2026-07-24 16:30:00', '2026-07-24 18:00:00'),
(163, 163, 'Regular Ticket', 35.00, 0, '2026-07-24 17:30:00', '2026-07-24 19:00:00'),
(164, 164, 'Regular Ticket', 35.00, 0, '2026-07-25 18:00:00', '2026-07-25 19:30:00'),
(165, 165, 'Regular Ticket', 45.00, 0, '2026-07-25 17:00:00', '2026-07-25 19:00:00'),
(166, 166, 'Regular Ticket', 45.00, 0, '2026-07-25 17:00:00', '2026-07-25 19:00:00'),
(167, 167, 'Regular Ticket', 45.00, 0, '2026-07-25 17:30:00', '2026-07-25 19:00:00'),
(168, 168, 'Regular Ticket', 35.00, 0, '2026-07-25 17:00:00', '2026-07-25 18:30:00'),
(169, 169, 'Regular Ticket', 35.00, 0, '2026-07-25 16:30:00', '2026-07-25 18:00:00'),
(170, 170, 'Regular Ticket', 35.00, 0, '2026-07-25 17:30:00', '2026-07-25 19:00:00'),
(171, 171, 'Regular Ticket', 35.00, 0, '2026-07-26 18:00:00', '2026-07-26 19:30:00'),
(172, 172, 'Regular Ticket', 45.00, 0, '2026-07-26 17:00:00', '2026-07-26 19:00:00'),
(173, 173, 'Regular Ticket', 45.00, 0, '2026-07-26 17:00:00', '2026-07-26 19:00:00'),
(174, 174, 'Regular Ticket', 45.00, 0, '2026-07-26 17:30:00', '2026-07-26 19:00:00'),
(175, 175, 'Regular Ticket', 35.00, 0, '2026-07-26 17:00:00', '2026-07-26 18:30:00'),
(176, 176, 'Regular Ticket', 35.00, 0, '2026-07-26 16:30:00', '2026-07-26 18:00:00'),
(177, 177, 'Regular Ticket', 35.00, 0, '2026-07-26 17:30:00', '2026-07-26 19:00:00'),
(178, 186, 'Ratatouille booking', 50.00, 0, '2026-04-07 17:00:00', '2026-04-07 19:00:00'),
(179, 187, 'Ratatouille booking', 60.00, 0, '2026-04-06 19:00:00', '2026-04-06 21:00:00'),
(180, 188, 'Name booking', 140.00, 0, '2026-04-19 16:30:00', '2026-04-19 18:00:00'),
(181, 189, 'Ratatouille booking', 50.00, 0, '2026-04-20 21:00:00', '2026-04-20 23:00:00');

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
(2, 'achraf@admin.com', '$2y$12$b8feJtwJ9Vg02pXHbV44gOvCuQKGwSeNwA0l9ug32ovMr3PEqR/Am', 'achraf derouich', 'admin', '/assets/uploads/user_2_1773057263.jpeg', '2026-02-07 04:07:01'),
(3, 'achraf@custumer.com', '$2y$12$xNRPBJ1/XOl6sG6z4rNkFeOG3TlzWpbqAdieirQsXVXFjXlpRSmX.', 'achraf derouich', 'customer', NULL, '2026-02-08 02:52:34'),
(4, 'hasan@costumer.com', '$2y$12$zP1tpSnNx/OP95eNm921t.VJb9sVhAEvJfdCYLXZmHo0kbGL25Zma', 'Hasan zaz', 'customer', NULL, '2026-02-09 09:44:09'),
(5, 'tim.sadko@gmail.com', '$2y$12$hn3z4x0E55UTyIQSkbnNu.1ouAnMxByNFdog/lgNhi0iDE0D9S1ga', 'Timofii Sadko', 'customer', NULL, '2026-02-27 12:38:00'),
(7, 'fff.fff@gmail.com', '$2y$12$FgVzZeZQ9wBDZsRRTdclReF782iykFNhv11yDmOLHK/hVYCPo91k.', 'ffff', 'customer', NULL, '2026-02-27 12:44:31'),
(8, 'enesveliyigit0@gmail.com', '$2y$12$ZN.EsKd.ZksHkuMFzeRxqe1pnPLOU9G87z7NFm1ql.JZ2EnjS2FwK', 'Enes Veli Yigit', 'admin', '/assets/uploads/user_8_1774543422.jpg', '2026-03-09 14:38:15'),
(9, 'earnest@gmail.com', '$2y$12$J6rfVP2MlTYmHpwS/nZBzOxETojIYb8bAdEtj4vj23EUE.ZdDQvNu', 'Earnest', 'customer', NULL, '2026-03-26 16:26:39'),
(10, 'ILOVEACHRAF@GMAIL.COM', '$2y$12$9kf46vzoS67gld1ioCvDGeb69rqvMpOnHalG2cBkVB/gaaqo7TfTG', 'Earnest', 'employee', NULL, '2026-03-26 16:32:54'),
(11, 'hotman@gmail.com', '$2y$12$mPNOa3kGlHmmJbRgVcT6KOjoBhsufbETgjDaq8gcDaFLXf83.216y', 'hotman@gmail.com', 'customer', NULL, '2026-03-31 14:40:05'),
(12, 'enesvelia8@gmail.com', '$2y$12$P1wcqIl5deGdRXZuCtbM8umhDBxf7dc2MdOH7HhlkA22Sm6URiYQe', 'Enes Veli Yigit', 'customer', NULL, '2026-04-04 15:21:18'),
(13, 'hello1@gmail.com', '$2y$12$BL6ERlVokQXgNf/3KgQ0JexSEsL2t8ow2F96kuzHz/cf77ZuT202C', 'hello', 'customer', NULL, '2026-04-05 13:06:09'),
(14, 'employee@employee.com', '$2y$12$0dv.PkporKCvCDhcNPZnDu64yyr8Ar34Gbixnhsq8zq3yWFtWQFKu', 'Employee', 'employee', NULL, '2026-04-06 13:17:11'),
(15, 'saidraghoua@gmail.com', '$2y$12$FcpFaTHQ5P9XKHdjW1UU..X3o.HPI6S7NbBWOdiflwlDPWHcxBCjW', 'Said', 'customer', NULL, '2026-04-06 15:49:47');

-- --------------------------------------------------------

--
-- Table structure for table `Venue`
--

CREATE TABLE `Venue` (
  `venue_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Venue`
--

INSERT INTO `Venue` (`venue_id`, `name`, `address`) VALUES
(1, 'Verhalenhuis Haarlem', 'Van Egmondstraat 7, Haarlem-Noord'),
(2, 'De Schuur', 'Lange Begijnestraat 9, 2011 HH Haarlem'),
(3, 'Kweekcafé', 'Kleverlaan 9, 2023 JC Haarlem'),
(4, 'Corrie ten Boom huis', 'Barteljorisstraat 19, Haarlem'),
(5, 'Theater Elswout', 'Elswoutslaan 24-a, 2051 AE Overveen'),
(6, 'Patronaat', 'Zijlvest 21, 2011 VB Haarlem'),
(7, 'Grote Markt', 'Grote Markt, Haarlem'),
(8, 'Lichtfabriek', 'Minckelersweg 2, 2031 EM Haarlem'),
(9, 'Slachthuis', 'Rockplein 6, 2033 KK Haarlem'),
(10, 'Jopenkerk', 'Gedempte Voldersgracht 2, 2011 WD Haarlem'),
(11, 'XO the Club', 'Grote Markt 8, 2011 RD Haarlem'),
(12, 'Puncher Comedy Club', 'Grote Markt 10, 2011 RD Haarlem'),
(13, 'Caprera Openluchttheater', 'Hoge Duin en Daalseweg 2, 2061 AG Bloemendaal'),
(14, 'Bavokerk', 'Grote Markt 22, 2011 RD Haarlem'),
(15, 'Cafe de Roemer', 'Botermarkt 17, 2011 XL Haarlem'),
(16, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem'),
(17, 'Restaurant ML', 'Kleine Houtstraat 70, 2011 DR Haarlem'),
(18, 'Restaurant Fris', 'Twijnderslaan 7, 2012 BG Haarlem'),
(19, 'New Vegas', 'Koningstraat 5, 2011 TB Haarlem'),
(20, 'Grand Cafe Brinkmann', 'Grote Markt 13, 2011 RC Haarlem'),
(21, 'Urban Frenchy Bistro Toujours', 'Oude Groenmarkt 10-12, 2011 HL Haarlem'),
(24, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem'),
(25, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem'),
(26, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem'),
(27, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem'),
(28, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem'),
(29, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem'),
(30, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem'),
(31, 'Name', 'Spaarne 96, 2011 CL Haarlem'),
(32, 'Ratatouille', 'Spaarne 96, 2011 CL Haarlem');

-- --------------------------------------------------------

--
-- Table structure for table `YummyBookings`
--

CREATE TABLE `YummyBookings` (
  `booking_id` int(16) NOT NULL,
  `reservation_id` int(16) NOT NULL,
  `user_id` int(16) NOT NULL,
  `date` date NOT NULL,
  `adult_number` tinyint(3) UNSIGNED NOT NULL,
  `child_number` tinyint(3) UNSIGNED NOT NULL,
  `comment` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `YummyCMS`
--

CREATE TABLE `YummyCMS` (
  `cms_id` int(16) NOT NULL,
  `home_title` varchar(128) NOT NULL,
  `home_subtitle` varchar(1024) NOT NULL,
  `home_image` varchar(128) NOT NULL,
  `list_title` varchar(128) NOT NULL,
  `list_subtitle` varchar(1024) NOT NULL,
  `list_image` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyCMS`
--

INSERT INTO `YummyCMS` (`cms_id`, `home_title`, `home_subtitle`, `home_image`, `list_title`, `list_subtitle`, `list_image`) VALUES
(1, 'Food and Drinks', 'Discover Haarlem’s vibrant food and drink scene, from elegant fine dining restaurants and cosy cafes to lively bars and quick bite spots. Whether you’re looking for a relaxed coffee break, a casual lunch, craft cocktails, or an unforgettable dinner experience, Haarlem offers something for every taste, mood, and moment right in the heart of the city.\r\n', '0b6f1e1d15de5247f57367596f4fe6b2.jpg', 'Restaurants, Cafes and Bars', 'Haarlem has built button strong reputation as button destination for high-quality dining, perfectly reflect the city’s diverse and refined food scene. Each offers button distinct experience, catering to different moods while maintaining button consistently high standard. ', 'abdbf9e23e9e2ba94b6b22efaffa0a44.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `YummyDishes`
--

CREATE TABLE `YummyDishes` (
  `dish_id` int(16) NOT NULL,
  `restaurant_id` int(16) NOT NULL,
  `name` varchar(64) NOT NULL,
  `text` varchar(256) NOT NULL,
  `image_path` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyDishes`
--

INSERT INTO `YummyDishes` (`dish_id`, `restaurant_id`, `name`, `text`, `image_path`) VALUES
(1, 1, 'Ratatouille', 'Rich, creamy Parmesan ratatouille with baba ganoush and gnocchi hearty and flavorful comfort refined.', '55t.png'),
(2, 1, 'Kingfish', 'Delicately seasoned kingfish with yuzu, dashi, and caviar bright, silky, and elegantly balanced. ', '22t.png'),
(3, 1, 'Tarbot', 'Perfectly cooked turbot with vin jaune, creamy parsnip, and caviar accents sophisticated seafood delight.', '11t.png'),
(4, 1, 'Kingfish', 'Delicately seasoned kingfish with yuzu, dashi, and caviar bright, silky, and elegantly balanced. ', '22t.png'),
(5, 1, 'Tarbot', 'Perfectly cooked turbot with vin jaune, creamy parsnip, and caviar accents sophisticated seafood delight.', '11t.png');

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
-- Table structure for table `YummyOpeningHours`
--

CREATE TABLE `YummyOpeningHours` (
  `id` int(16) NOT NULL,
  `restaurant_id` int(16) NOT NULL,
  `monday` varchar(64) NOT NULL,
  `tuesday` varchar(64) NOT NULL,
  `wednesday` varchar(64) NOT NULL,
  `thursday` varchar(64) NOT NULL,
  `friday` varchar(64) NOT NULL,
  `saturday` varchar(64) NOT NULL,
  `sunday` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyOpeningHours`
--

INSERT INTO `YummyOpeningHours` (`id`, `restaurant_id`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`) VALUES
(1, 1, 'Closed', 'Closed', '18:00-21:30', '18:00-21:30', '18:30-21:30', '18:30-21:30', '18:30-21:30'),
(7, 2, '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00'),
(8, 4, '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00'),
(9, 5, '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00'),
(10, 6, '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00'),
(11, 7, '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00'),
(12, 8, '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00'),
(13, 9, '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00', '17:30-23:00');

-- --------------------------------------------------------

--
-- Table structure for table `YummyReservationSlots`
--

CREATE TABLE `YummyReservationSlots` (
  `reservation_id` int(16) NOT NULL,
  `slot_id` int(16) NOT NULL,
  `date` date NOT NULL,
  `booked` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
-- Table structure for table `YummyRestaurantImages`
--

CREATE TABLE `YummyRestaurantImages` (
  `image_id` int(16) NOT NULL,
  `restaurant_id` int(16) NOT NULL,
  `path` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyRestaurantImages`
--

INSERT INTO `YummyRestaurantImages` (`image_id`, `restaurant_id`, `path`) VALUES
(10, 1, 'd5df10fcc034f9c8de2d8339057be489.png'),
(11, 1, '6e2fbde707772677afbbf2491c05c6dc.png');

-- --------------------------------------------------------

--
-- Table structure for table `YummyRestaurants`
--

CREATE TABLE `YummyRestaurants` (
  `restaurant_id` int(16) NOT NULL,
  `main_img_path` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `mini_text` varchar(256) NOT NULL,
  `rating` float NOT NULL,
  `cost_rating` bit(4) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'0',
  `text` varchar(2048) NOT NULL DEFAULT '',
  `address_text` varchar(128) NOT NULL DEFAULT '',
  `address_uri` varchar(256) NOT NULL DEFAULT '',
  `website_link` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyRestaurants`
--

INSERT INTO `YummyRestaurants` (`restaurant_id`, `main_img_path`, `name`, `mini_text`, `rating`, `cost_rating`, `active`, `text`, `address_text`, `address_uri`, `website_link`) VALUES
(1, 'b3d3e9891fc40495c3eadd6cace50138.png', 'Ratatouille', 'Elegant fine-dining restaurant with a refined French cuisine in a historic riverside building. Perfect for special occasions and memorable dinner experiences. ', 4.6, b'0011', b'1', 'Ratatouille is one of Haarlem’s standouts fine dining destinations, blending modern French cuisine with creative contemporary flair. Set in a beautifully restored historic building along the Spaarne river, this Michelin-starred restaurant offers an elegant yet welcoming atmosphere perfect for special occasions, intimate dinners, or memorable culinary experiences. \r\n\r\nUnder the guidance of chef Jozua Jaring, the menu showcases meticulously crafted dishes that balance bold flavors, refined techniques, and seasonal ingredients. Guests can enjoy a range of tasting menus from four to six courses that highlight inventive interpretations of classic French foundations, luxurious seafood, and artfully prepared vegetarian options. \r\n\r\nAttention to detail extends beyond the plate; the restaurant’s wine program is curated to enhance every course, with expert pairings designed to elevate the full dining journey. Whether you’re seated inside amid stylish interiors or on the charming waterside terrace during warmer months, Ratatouille delivers an exceptional gourmet experience that celebrates both tradition and innovation in every bite. \r\n', 'Spaarne 96, 2011 CL Haarlem', 'Ratatouille+Food+%26+Wine', 'http://www.ratatouillefoodandwine.nl/'),
(2, 'trft.png', 'Restaurant Fris', 'Contemporary restaurant known for modern, creative dishes in a welcoming setting, combining innovative flavors with relaxed dining.', 4, b'0011', b'1', 'Ratatouille is one of Haarlem’s standouts fine dining destinations, blending modern French cuisine with creative contemporary flair. Set in a beautifully restored historic building along the Spaarne river, this Michelin-starred restaurant offers an elegant yet welcoming atmosphere perfect for special occasions, intimate dinners, or memorable culinary experiences. \r\n\r\nUnder the guidance of chef Jozua Jaring, the menu showcases meticulously crafted dishes that balance bold flavors, refined techniques, and seasonal ingredients. Guests can enjoy a range of tasting menus from four to six courses that highlight inventive interpretations of classic French foundations, luxurious seafood, and artfully prepared vegetarian options. \r\n\r\nAttention to detail extends beyond the plate; the restaurant’s wine program is curated to enhance every course, with expert pairings designed to elevate the full dining journey. Whether you’re seated inside amid stylish interiors or on the charming waterside terrace during warmer months, Ratatouille delivers an exceptional gourmet experience that celebrates both tradition and innovation in every bite. \r\n', '', '', NULL),
(4, '4ecb8fc1f9639bc4fd8c85c461a90507d25987c6.png', 'New Vegas', 'A lively restaurant and bar offering a relaxed atmosphere, comfort food, and drinks — great for casual meetups, meals with friends, or an easy night out. ', 3.4, b'0010', b'1', 'Ratatouille is one of Haarlem’s standouts fine dining destinations, blending modern French cuisine with creative contemporary flair. Set in a beautifully restored historic building along the Spaarne river, this Michelin-starred restaurant offers an elegant yet welcoming atmosphere perfect for special occasions, intimate dinners, or memorable culinary experiences. \r\n\r\nUnder the guidance of chef Jozua Jaring, the menu showcases meticulously crafted dishes that balance bold flavors, refined techniques, and seasonal ingredients. Guests can enjoy a range of tasting menus from four to six courses that highlight inventive interpretations of classic French foundations, luxurious seafood, and artfully prepared vegetarian options. \r\n\r\nAttention to detail extends beyond the plate; the restaurant’s wine program is curated to enhance every course, with expert pairings designed to elevate the full dining journey. Whether you’re seated inside amid stylish interiors or on the charming waterside terrace during warmer months, Ratatouille delivers an exceptional gourmet experience that celebrates both tradition and innovation in every bite. \r\n', '', '', NULL),
(5, '84ebc9c296006b843e884811ba26ba5c0f48e87a.png', 'Grand Cafe Brinkman', 'Classic Haarlem café-restaurant perfect for lunch, dinner, drinks, or people-watching in the city centre with a warm, inviting vibe. ', 3.8, b'0010', b'1', 'Ratatouille is one of Haarlem’s standouts fine dining destinations, blending modern French cuisine with creative contemporary flair. Set in a beautifully restored historic building along the Spaarne river, this Michelin-starred restaurant offers an elegant yet welcoming atmosphere perfect for special occasions, intimate dinners, or memorable culinary experiences. \r\n\r\nUnder the guidance of chef Jozua Jaring, the menu showcases meticulously crafted dishes that balance bold flavors, refined techniques, and seasonal ingredients. Guests can enjoy a range of tasting menus from four to six courses that highlight inventive interpretations of classic French foundations, luxurious seafood, and artfully prepared vegetarian options. \r\n\r\nAttention to detail extends beyond the plate; the restaurant’s wine program is curated to enhance every course, with expert pairings designed to elevate the full dining journey. Whether you’re seated inside amid stylish interiors or on the charming waterside terrace during warmer months, Ratatouille delivers an exceptional gourmet experience that celebrates both tradition and innovation in every bite. \r\n', '', '', NULL),
(6, 'c1f770e71ad26341a02236bdbfaa8764d78382e7.png', 'Koper', 'Elegant dining with refined dishes rooted in classic European cuisine, ideal for a memorable dinner night out or special occasion in beautifully styled surroundings. ', 5, b'0011', b'1', 'Ratatouille is one of Haarlem’s standouts fine dining destinations, blending modern French cuisine with creative contemporary flair. Set in a beautifully restored historic building along the Spaarne river, this Michelin-starred restaurant offers an elegant yet welcoming atmosphere perfect for special occasions, intimate dinners, or memorable culinary experiences. \r\n\r\nUnder the guidance of chef Jozua Jaring, the menu showcases meticulously crafted dishes that balance bold flavors, refined techniques, and seasonal ingredients. Guests can enjoy a range of tasting menus from four to six courses that highlight inventive interpretations of classic French foundations, luxurious seafood, and artfully prepared vegetarian options. \r\n\r\nAttention to detail extends beyond the plate; the restaurant’s wine program is curated to enhance every course, with expert pairings designed to elevate the full dining journey. Whether you’re seated inside amid stylish interiors or on the charming waterside terrace during warmer months, Ratatouille delivers an exceptional gourmet experience that celebrates both tradition and innovation in every bite. \r\n', '', '', NULL),
(7, 'a96586c89bdcf8bd35ca11c1fa519a7f35b3451b.png', 'Café de Roemer', 'Cozy cafe serving light bites, drinks and casual fare in a historic Haarlem spot ideal for coffee breaks or relaxed socializing. ', 4.1, b'0011', b'1', 'Ratatouille is one of Haarlem’s standouts fine dining destinations, blending modern French cuisine with creative contemporary flair. Set in a beautifully restored historic building along the Spaarne river, this Michelin-starred restaurant offers an elegant yet welcoming atmosphere perfect for special occasions, intimate dinners, or memorable culinary experiences. \r\n\r\nUnder the guidance of chef Jozua Jaring, the menu showcases meticulously crafted dishes that balance bold flavors, refined techniques, and seasonal ingredients. Guests can enjoy a range of tasting menus from four to six courses that highlight inventive interpretations of classic French foundations, luxurious seafood, and artfully prepared vegetarian options. \r\n\r\nAttention to detail extends beyond the plate; the restaurant’s wine program is curated to enhance every course, with expert pairings designed to elevate the full dining journey. Whether you’re seated inside amid stylish interiors or on the charming waterside terrace during warmer months, Ratatouille delivers an exceptional gourmet experience that celebrates both tradition and innovation in every bite. \r\n', '', '', NULL),
(8, 'eccbb8f0cb382e19ddd12930d34f2c1bb32a6fd0.png', 'Restaurant ML', 'A charming café/restaurant blending relaxed dining with a casual menu and friendly service great for informal meals or coffee.', 4.5, b'0011', b'1', 'Ratatouille is one of Haarlem’s standouts fine dining destinations, blending modern French cuisine with creative contemporary flair. Set in a beautifully restored historic building along the Spaarne river, this Michelin-starred restaurant offers an elegant yet welcoming atmosphere perfect for special occasions, intimate dinners, or memorable culinary experiences. \r\n\r\nUnder the guidance of chef Jozua Jaring, the menu showcases meticulously crafted dishes that balance bold flavors, refined techniques, and seasonal ingredients. Guests can enjoy a range of tasting menus from four to six courses that highlight inventive interpretations of classic French foundations, luxurious seafood, and artfully prepared vegetarian options. \r\n\r\nAttention to detail extends beyond the plate; the restaurant’s wine program is curated to enhance every course, with expert pairings designed to elevate the full dining journey. Whether you’re seated inside amid stylish interiors or on the charming waterside terrace during warmer months, Ratatouille delivers an exceptional gourmet experience that celebrates both tradition and innovation in every bite. \r\n', '', '', NULL),
(9, '84703904c0b0b04ff368246f347530bbcb94c1bf.png', 'Urban Frenchy Bistro Toujours', 'A lively Mediterranean-inspired spot on Haarlem’s Grote Markt, perfect for sharing flavourful cocktails, and relaxed meals with friends or family.', 3.2, b'0001', b'1', 'Ratatouille is one of Haarlem’s standouts fine dining destinations, blending modern French cuisine with creative contemporary flair. Set in a beautifully restored historic building along the Spaarne river, this Michelin-starred restaurant offers an elegant yet welcoming atmosphere perfect for special occasions, intimate dinners, or memorable culinary experiences. \r\n\r\nUnder the guidance of chef Jozua Jaring, the menu showcases meticulously crafted dishes that balance bold flavors, refined techniques, and seasonal ingredients. Guests can enjoy a range of tasting menus from four to six courses that highlight inventive interpretations of classic French foundations, luxurious seafood, and artfully prepared vegetarian options. \r\n\r\nAttention to detail extends beyond the plate; the restaurant’s wine program is curated to enhance every course, with expert pairings designed to elevate the full dining journey. Whether you’re seated inside amid stylish interiors or on the charming waterside terrace during warmer months, Ratatouille delivers an exceptional gourmet experience that celebrates both tradition and innovation in every bite. \r\n', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `YummyRestaurantTimeSlots`
--

CREATE TABLE `YummyRestaurantTimeSlots` (
  `slot_id` int(16) NOT NULL,
  `restaurant_id` int(16) NOT NULL,
  `time` time NOT NULL,
  `capacity` smallint(6) NOT NULL,
  `duration` int(16) NOT NULL DEFAULT 120
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `YummyRestaurantTimeSlots`
--

INSERT INTO `YummyRestaurantTimeSlots` (`slot_id`, `restaurant_id`, `time`, `capacity`, `duration`) VALUES
(1, 1, '17:00:00', 30, 120),
(2, 1, '19:00:00', 30, 120),
(3, 1, '21:00:00', 30, 120);

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
-- Indexes for table `CMS_Content`
--
ALTER TABLE `CMS_Content`
  ADD PRIMARY KEY (`content_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `Event`
--
ALTER TABLE `Event`
  ADD PRIMARY KEY (`event_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_start_time` (`start_time`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `festival_events`
--
ALTER TABLE `festival_events`
  ADD PRIMARY KEY (`festival_event_id`);

--
-- Indexes for table `festival_event_tickets`
--
ALTER TABLE `festival_event_tickets`
  ADD PRIMARY KEY (`festival_event_ticket_id`),
  ADD UNIQUE KEY `qr_token` (`qr_token`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `festival_event_ticket_type_id` (`festival_event_ticket_type_id`);

--
-- Indexes for table `festival_event_ticket_types`
--
ALTER TABLE `festival_event_ticket_types`
  ADD PRIMARY KEY (`festival_event_ticket_type_id`),
  ADD KEY `festival_event_id` (`festival_event_id`);

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
-- Indexes for table `history_ticket_prices`
--
ALTER TABLE `history_ticket_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_type` (`ticket_type`);

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
-- Indexes for table `Invoice`
--
ALTER TABLE `Invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_invoice_number` (`invoice_number`);

--
-- Indexes for table `jazz_experiences`
--
ALTER TABLE `jazz_experiences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jazz_hero`
--
ALTER TABLE `jazz_hero`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jazz_intro_content`
--
ALTER TABLE `jazz_intro_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jazz_locations`
--
ALTER TABLE `jazz_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jazz_performers`
--
ALTER TABLE `jazz_performers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jazz_performer_appearances`
--
ALTER TABLE `jazz_performer_appearances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `performer_id` (`performer_id`);

--
-- Indexes for table `jazz_performer_highlights`
--
ALTER TABLE `jazz_performer_highlights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jazz_performer_highlights_performer` (`performer_id`);

--
-- Indexes for table `jazz_performer_locations`
--
ALTER TABLE `jazz_performer_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `performer_id` (`performer_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `jazz_performer_tracks`
--
ALTER TABLE `jazz_performer_tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jazz_performer_tracks_performer` (`performer_id`);

--
-- Indexes for table `jazz_recommendations`
--
ALTER TABLE `jazz_recommendations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Order`
--
ALTER TABLE `Order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `OrderItem`
--
ALTER TABLE `OrderItem`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_type_id` (`type_id`);

--
-- Indexes for table `PasswordResetToken`
--
ALTER TABLE `PasswordResetToken`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `Ticket`
--
ALTER TABLE `Ticket`
  ADD PRIMARY KEY (`ticket_id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `idx_barcode` (`barcode`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indexes for table `Ticket_Type`
--
ALTER TABLE `Ticket_Type`
  ADD PRIMARY KEY (`type_id`),
  ADD KEY `idx_event_id` (`event_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Venue`
--
ALTER TABLE `Venue`
  ADD PRIMARY KEY (`venue_id`),
  ADD KEY `idx_name` (`name`);

--
-- Indexes for table `YummyBookings`
--
ALTER TABLE `YummyBookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `YummyCMS`
--
ALTER TABLE `YummyCMS`
  ADD PRIMARY KEY (`cms_id`);

--
-- Indexes for table `YummyDishes`
--
ALTER TABLE `YummyDishes`
  ADD PRIMARY KEY (`dish_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

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
-- Indexes for table `YummyOpeningHours`
--
ALTER TABLE `YummyOpeningHours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `YummyReservationSlots`
--
ALTER TABLE `YummyReservationSlots`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Indexes for table `YummyRestaurantFoodTypes`
--
ALTER TABLE `YummyRestaurantFoodTypes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `YummyRestaurantImages`
--
ALTER TABLE `YummyRestaurantImages`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `YummyRestaurants`
--
ALTER TABLE `YummyRestaurants`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `YummyRestaurantTimeSlots`
--
ALTER TABLE `YummyRestaurantTimeSlots`
  ADD PRIMARY KEY (`slot_id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `CartItem`
--
ALTER TABLE `CartItem`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `cms_content`
--
ALTER TABLE `cms_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `CMS_Content`
--
ALTER TABLE `CMS_Content`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Event`
--
ALTER TABLE `Event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `festival_events`
--
ALTER TABLE `festival_events`
  MODIFY `festival_event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `festival_event_tickets`
--
ALTER TABLE `festival_event_tickets`
  MODIFY `festival_event_ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `festival_event_ticket_types`
--
ALTER TABLE `festival_event_ticket_types`
  MODIFY `festival_event_ticket_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `history_ticket_prices`
--
ALTER TABLE `history_ticket_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `Invoice`
--
ALTER TABLE `Invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jazz_experiences`
--
ALTER TABLE `jazz_experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jazz_hero`
--
ALTER TABLE `jazz_hero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jazz_intro_content`
--
ALTER TABLE `jazz_intro_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jazz_locations`
--
ALTER TABLE `jazz_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jazz_performers`
--
ALTER TABLE `jazz_performers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `jazz_performer_appearances`
--
ALTER TABLE `jazz_performer_appearances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jazz_performer_highlights`
--
ALTER TABLE `jazz_performer_highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jazz_performer_locations`
--
ALTER TABLE `jazz_performer_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jazz_performer_tracks`
--
ALTER TABLE `jazz_performer_tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jazz_recommendations`
--
ALTER TABLE `jazz_recommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Order`
--
ALTER TABLE `Order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `OrderItem`
--
ALTER TABLE `OrderItem`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `PasswordResetToken`
--
ALTER TABLE `PasswordResetToken`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Ticket`
--
ALTER TABLE `Ticket`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `Ticket_Type`
--
ALTER TABLE `Ticket_Type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `Venue`
--
ALTER TABLE `Venue`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `YummyBookings`
--
ALTER TABLE `YummyBookings`
  MODIFY `booking_id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT for table `YummyOpeningHours`
--
ALTER TABLE `YummyOpeningHours`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `YummyReservationSlots`
--
ALTER TABLE `YummyReservationSlots`
  MODIFY `reservation_id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `YummyRestaurantFoodTypes`
--
ALTER TABLE `YummyRestaurantFoodTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `YummyRestaurantImages`
--
ALTER TABLE `YummyRestaurantImages`
  MODIFY `image_id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `YummyRestaurants`
--
ALTER TABLE `YummyRestaurants`
  MODIFY `restaurant_id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `YummyRestaurantTimeSlots`
--
ALTER TABLE `YummyRestaurantTimeSlots`
  MODIFY `slot_id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CartItem`
--
ALTER TABLE `CartItem`
  ADD CONSTRAINT `cartitem_user_fk` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Event`
--
ALTER TABLE `Event`
  ADD CONSTRAINT `Event_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `Venue` (`venue_id`) ON DELETE CASCADE;

--
-- Constraints for table `festival_event_tickets`
--
ALTER TABLE `festival_event_tickets`
  ADD CONSTRAINT `festival_event_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `festival_event_ticket_types`
--
ALTER TABLE `festival_event_ticket_types`
  ADD CONSTRAINT `festival_event_ticket_types_ibfk_1` FOREIGN KEY (`festival_event_id`) REFERENCES `festival_events` (`festival_event_id`) ON DELETE CASCADE;

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
-- Constraints for table `Invoice`
--
ALTER TABLE `Invoice`
  ADD CONSTRAINT `Invoice_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `jazz_performer_appearances`
--
ALTER TABLE `jazz_performer_appearances`
  ADD CONSTRAINT `jazz_performer_appearances_ibfk_1` FOREIGN KEY (`performer_id`) REFERENCES `jazz_performers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jazz_performer_highlights`
--
ALTER TABLE `jazz_performer_highlights`
  ADD CONSTRAINT `fk_jazz_performer_highlights_performer` FOREIGN KEY (`performer_id`) REFERENCES `jazz_performers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jazz_performer_locations`
--
ALTER TABLE `jazz_performer_locations`
  ADD CONSTRAINT `jazz_performer_locations_ibfk_1` FOREIGN KEY (`performer_id`) REFERENCES `jazz_performers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jazz_performer_locations_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `jazz_locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jazz_performer_tracks`
--
ALTER TABLE `jazz_performer_tracks`
  ADD CONSTRAINT `fk_jazz_performer_tracks_performer` FOREIGN KEY (`performer_id`) REFERENCES `jazz_performers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Order`
--
ALTER TABLE `Order`
  ADD CONSTRAINT `Order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `OrderItem`
--
ALTER TABLE `OrderItem`
  ADD CONSTRAINT `OrderItem_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `Ticket_Type` (`type_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `OrderItem_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `PasswordResetToken`
--
ALTER TABLE `PasswordResetToken`
  ADD CONSTRAINT `PasswordResetToken_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Ticket`
--
ALTER TABLE `Ticket`
  ADD CONSTRAINT `Ticket_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Order` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Ticket_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `Ticket_Type` (`type_id`) ON DELETE CASCADE;

--
-- Constraints for table `Ticket_Type`
--
ALTER TABLE `Ticket_Type`
  ADD CONSTRAINT `Ticket_Type_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `Event` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `YummyBookings`
--
ALTER TABLE `YummyBookings`
  ADD CONSTRAINT `1` FOREIGN KEY (`reservation_id`) REFERENCES `YummyReservationSlots` (`reservation_id`),
  ADD CONSTRAINT `2` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`);

--
-- Constraints for table `YummyDishes`
--
ALTER TABLE `YummyDishes`
  ADD CONSTRAINT `YummyDishes_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `YummyRestaurants` (`restaurant_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
