-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Mar 04, 2026 at 12:44 PM
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
(2, 'achraf@admin.com', '$2y$12$b8feJtwJ9Vg02pXHbV44gOvCuQKGwSeNwA0l9ug32ovMr3PEqR/Am', 'achraf derouich', 'customer', NULL, '2026-02-07 04:07:01'),
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
(2, 1, 1);

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
-- AUTO_INCREMENT for table `PasswordResetToken`
--
ALTER TABLE `PasswordResetToken`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `YummyRestaurants`
--
ALTER TABLE `YummyRestaurants`
  MODIFY `restaurant_id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

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
