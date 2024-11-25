-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 04:26 AM
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
-- Database: `dj_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `dj_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `client_id`, `dj_id`, `event_date`, `status`, `created_at`) VALUES
(1, 2, 1, '2024-11-24', '', '2024-11-22 00:44:52'),
(2, 2, 1, '2024-11-23', '', '2024-11-22 03:24:10'),
(3, 2, 3, '2024-11-29', 'pending', '2024-11-22 03:28:00'),
(5, 2, 3, '2024-11-20', 'pending', '2024-11-22 05:25:19'),
(7, 2, 1, '2024-11-25', '', '2024-11-22 06:07:51'),
(8, 2, 1, '2024-11-27', '', '2024-11-22 06:12:10'),
(11, 2, 1, '2024-11-25', '', '2024-11-22 06:42:57'),
(12, 6, 1, '2024-11-25', '', '2024-11-22 06:45:16'),
(13, 6, 1, '2024-11-21', '', '2024-11-22 06:45:25'),
(14, 6, 1, '2024-11-29', 'pending', '2024-11-22 06:58:13'),
(15, 2, 3, '2024-11-23', 'pending', '2024-11-22 19:35:15'),
(16, 6, 4, '2024-11-22', 'pending', '2024-11-22 20:51:32'),
(17, 2, 4, '2024-11-20', 'pending', '2024-11-23 22:56:39'),
(18, 10, 1, '2024-12-07', 'pending', '2024-11-23 23:38:09'),
(19, 6, 1, '2024-12-04', 'pending', '2024-11-24 02:24:46'),
(20, 6, 1, '2024-11-20', 'pending', '2024-11-24 02:48:59');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dj_categories`
--

CREATE TABLE `dj_categories` (
  `dj_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `created_at`, `updated_at`, `author_id`) VALUES
(1, 'the big bash', 'bash bash bash', '2024-11-24 03:04:01', '2024-11-24 03:04:01', 9),
(2, 'Welcome to the page', 'Still under construction bare with us! ', '2024-11-24 03:05:38', '2024-11-24 03:05:38', 9),
(3, 'Working working...', 'Updating functionality... ', '2024-11-24 03:05:58', '2024-11-24 03:05:58', 9),
(4, 'For the fans.', 'Checkout EEC this week to see LKF preform his new techno album! ', '2024-11-24 03:06:23', '2024-11-24 03:06:23', 9),
(5, 'keep it rocking', 'Party on the way stay tuned...', '2024-11-24 03:06:42', '2024-11-24 03:06:42', 9),
(6, 'What is a party? ', 'A party is a gathering of people coming together to celebrate, socialize, or enjoy a shared activity. It can range from casual meetups to formal events, depending on the occasion or purpose. Parties often include food, drinks, music, and entertainment, creating an atmosphere for relaxation and connection. The vibe can be lively and energetic, like a rave or club night, or intimate and laid-back, such as a dinner party or game night. People typically attend to have fun, meet new friends, or mark special occasions like birthdays, weddings, or holidays. While some parties emphasize dancing and loud music, others focus on conversations and shared activities. Ultimately, a party is about creating memorable moments and bringing people together for a good time.', '2024-11-24 03:08:47', '2024-11-24 03:08:47', 9),
(7, 'Keep things safe and nice.', 'treat all well.\r\nHave respect\r\nEnjoy the moment.', '2024-11-24 03:09:24', '2024-11-24 03:09:24', 9),
(8, 'Best beer', 'The best beer might be a pilsner, classy, light, easy to drink and enjoyable anywhere, our partners at WPG Brew brew are producing a new beer for our up coming show! ', '2024-11-24 03:10:23', '2024-11-24 03:10:23', 9),
(9, 'Safe travels ', 'Remember party goers and goodtime havers...\r\nFree bus shuttles to the party tonight as well as uber and lyft\r\nlets be smart and make wise choices! ', '2024-11-24 03:11:20', '2024-11-24 03:11:20', 9),
(10, 'November ', 'We are hoping to have alot more functionality for you guys on the site soon, including pictures of events, and some other cool things! keep an EYE! ', '2024-11-24 03:12:33', '2024-11-24 03:12:33', 9);

-- --------------------------------------------------------

--
-- Table structure for table `ratings_reviews`
--

CREATE TABLE `ratings_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dj_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings_reviews`
--

INSERT INTO `ratings_reviews` (`id`, `user_id`, `dj_id`, `rating`, `comment`, `created_at`) VALUES
(2, 2, 3, 4, 'Was a blast! Could use a bit of work on reading the crowd at times, worth the money! ', '2024-11-22 19:26:44'),
(4, 2, 1, 5, 'Loved this his performance! ', '2024-11-22 21:33:11'),
(5, 2, 4, 4, 'loved the kanye west mashup!!', '2024-11-23 22:57:40'),
(6, 6, 1, 4, 'Great variety in his music! so fun! ', '2024-11-23 23:27:03'),
(7, 10, 1, 5, 'Loved the Calvin harris music you played! ', '2024-11-23 23:38:01'),
(8, 10, 4, 3, 'Was ok ', '2024-11-23 23:57:32'),
(9, 2, 1, 3, 'hi', '2024-11-24 02:55:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dj','client') DEFAULT 'client',
  `bio` text DEFAULT NULL,
  `genres` varchar(255) DEFAULT NULL,
  `availability` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`availability`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `bio`, `genres`, `availability`, `created_at`) VALUES
(1, 'LKFLeeroy', '$2y$10$7KOT2KbGHFNOrFUrgpO47uOPuxF52sO.0LBt1TKqWMIOon8nm72KW', 'dj', '&lt;p&gt;Liam Friesen, known by his stage name LKFLeeroy, is a Canadian electronic music producer and DJ specializing in genres like dubstep, house, and riddim. He has released tracks such as Revenge Comeback , and Work. Active since at least 2017, LKFLeeroy has also shared his music on platforms like SoundCloud, YouTube, and Facebook. He has performed at local events, including the Together Again Frost Festival in March 2023. Additionally, he served as a supporting act for Borgore at the Exchange Event Centre (EEC) in January 2024, and continues preforming weddings, socials, raves and anything that needs a dancefloor moving!&lt;/p&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;Easy to work with&lt;/li&gt;\r\n&lt;li&gt;Reliable&nbsp;&lt;/li&gt;\r\n&lt;li&gt;Experienced&lt;/li&gt;\r\n&lt;/ul&gt;', '&lt;p&gt;Open Format&lt;/p&gt;', NULL, '2024-11-21 23:20:40'),
(2, 'user1', '$2y$10$EoWo72Nr5gfEOZLdTtHwmOx82CGXd98Fhfg6qJlFxHPtOrp53oiMa', 'client', '', '', NULL, '2024-11-22 00:09:08'),
(3, 'DJ Maxiloft', '', 'dj', 'Expert in techno and house music 4 on the floor!', 'techno, house', NULL, '2024-11-22 00:15:57'),
(4, 'DJ SparknPlug', '', 'dj', 'Lover of hip-hop beats and mashups.', 'hip-hop, mashup', NULL, '2024-11-22 00:15:57'),
(5, 'John Donut', '$2y$10$CfB51QIZ08/BeR/pOhu8QeJ0io2jiDUlWvjUqLIPxwNSzwqWEOTMi', 'client', '', '', NULL, '2024-11-22 06:43:53'),
(6, 'Fred1', '$2y$10$VhXCuafNz4rmaT84j6V6del34jrH8758BvSUC/epoX43077wm7V8S', 'client', '', '', NULL, '2024-11-22 06:44:47'),
(7, 'Tiesto', '$2y$10$bM0W0bcL7PSulpoOlrAvFOb/EWKPutiVpX97LBBaVRsydNjjuFbl6', 'dj', 'Tiësto, born Tijs Michiel Verwest, is a world-renowned Dutch DJ and music producer considered one of the pioneers of modern electronic dance music. Rising to fame in the early 2000s, he gained global recognition with tracks like &#34;Adagio for Strings&#34; and became the first DJ to perform at the Olympics during the 2004 Athens Games. Known for his versatility, Tiësto has explored various EDM genres, including trance, house, and pop, collaborating with artists like Post Malone, Calvin Harris, and Dzeko. He continues to headline major festivals and is celebrated as one of the most influential figures in the dance music scene.', 'House ', NULL, '2024-11-22 18:26:37'),
(8, 'bossman', '$2y$10$WyX09sIHFl7onvybPLCfvOSHvpVI6G08qbBE.uiTVC8HAz74gFPHu', 'client', '', '', NULL, '2024-11-22 19:56:01'),
(9, 'adminboss', '$2y$10$OCETYkCVI7oZQbl2ew3r.eVfjKbEQkDS/FSXg.YAawI04PLQldpLi', 'admin', '', '', NULL, '2024-11-22 19:56:48'),
(10, 'John1', '$2y$10$.ADSwpvAHHFIBWpHvC4G6u1wRFc8iaMeb9bNgbvNSxbaKWQ66tb3G', 'client', '', '', NULL, '2024-11-23 23:37:06'),
(11, 'SirDontMixalot', '$2y$10$EM.7Ceik8i.Pm44arBllLeyszJv8aWt6g0HnKQBYouLZSOJahwGkm', 'dj', 'Im a Dj who hates DJING! But I still do it anyways! ', 'Hiphop', NULL, '2024-11-23 23:39:53'),
(12, 'tuser', '$2y$10$yvr7oAnLUz/Af1vRvd0oBeAiAp3idT.S/Dmu9iCM3fbmBsZRN5dAa', 'client', '', '', NULL, '2024-11-24 03:24:08'),
(13, 'tadmin', '$2y$10$JH/kAdNsxomQOewl4WvmZe7t6lwCzv.FhUJaTfGSzcFkTBcWhEPUG', 'admin', '', '', NULL, '2024-11-24 03:24:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `dj_id` (`dj_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `dj_categories`
--
ALTER TABLE `dj_categories`
  ADD PRIMARY KEY (`dj_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_dj` (`dj_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`dj_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `dj_categories`
--
ALTER TABLE `dj_categories`
  ADD CONSTRAINT `dj_categories_ibfk_1` FOREIGN KEY (`dj_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `dj_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `ratings_reviews`
--
ALTER TABLE `ratings_reviews`
  ADD CONSTRAINT `fk_dj` FOREIGN KEY (`dj_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ratings_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `ratings_reviews_ibfk_2` FOREIGN KEY (`dj_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
