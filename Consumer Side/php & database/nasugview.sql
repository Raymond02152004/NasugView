-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2025 at 05:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nasugview`
--

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `rating` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`id`, `name`, `image_url`, `address`, `category`, `rating`) VALUES
(8, 'Cora RTW Store', 'business/store.jpg', 'Brgy. 10 Public Market, Nasugbu, Batangas', 'Clothes', 5.0),
(9, 'BernaBeach Resort', 'business/berna.png', 'Brgy. Bucana, Nasugbu, Batangas', 'Resorts', 4.9),
(10, 'Bulalohan sa Kanto', 'business/bulalo.jpg', 'Brgy. 10, Nasugbu, Batangas', 'Restaurants', 4.8),
(11, 'RRJ Boutique', 'business/rrj.jpg', 'J P Laurel St, Nasugbu, Batangas', 'Clothes', 4.7),
(12, 'Golden View Resort', 'business/gold.jpg', 'Brgy. Bucana, Nasugbu, Batangas', 'Resorts', 4.6),
(13, 'Len Wings', 'business/unli.jpg', 'Brgy. Wawa, Nasugbu, Batangas', 'Restaurants', 4.5),
(14, 'Pendong By Rance', 'business/pendong.jpg', 'Concepcion St, Nasugbu, Batangas', 'Restaurants', 4.6);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `image`, `caption`, `created_at`) VALUES
(3, 4, 'Len_1752920373.jpeg', 'Coffee and Cake', '2025-07-19 18:19:33'),
(4, 7, 'Sheila_1752921405.jpeg', 'Boodle Fight', '2025-07-19 18:36:45'),
(22, 7, 'Sheila_1753754851.jpg', '🩷🩷🩷', '2025-07-29 10:07:31'),
(23, 7, 'Sheila_1753754873.jpg', '', '2025-07-29 10:07:53'),
(24, 4, 'Len_1753755104.jpeg', 'Hello', '2025-07-29 10:11:44');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `excellent_rating` int(11) DEFAULT NULL,
  `service_rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `username`, `business_name`, `excellent_rating`, `service_rating`, `comment`, `created_at`, `image_path`) VALUES
(2, 'Sheila', 'Cora RTW Store', 4, 4, 'Nice\n', '2025-07-23 16:07:28', NULL),
(9, 'Len', 'Cora RTW Store', 5, 4, 'Very Nice and affordable', '2025-07-23 17:31:06', '6881003aa3db2.jpeg'),
(14, 'Sheila', 'BernaBeach Resort', 5, 5, 'Very Good', '2025-07-27 13:40:52', NULL),
(17, 'Len', 'BernaBeach Resort', 2, 3, 'Tagal namin naghintay', '2025-07-27 14:09:15', NULL),
(29, 'Len', 'Cora RTW Store', 5, 5, 'Nice', '2025-07-29 04:09:16', NULL),
(30, 'Len', 'Cora RTW Store', 5, 5, 'Nice', '2025-07-29 04:09:54', '68882d7232577.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `username` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `image` varchar(250) NOT NULL,
  `cover` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `image`, `cover`) VALUES
(4, 'lindsayangcao13@gmail.com\n', 'Len', '$2y$10$EnVWjspCdTAxT.bwfYLY/uEJKMHjz8wM17t/cbuFA9uptCpL/HJwe', 'profiles/Len_profile_1753755031.jpeg', 'covers/Len_cover_1752920887.jpeg'),
(7, 'sheilagomez@gmail.com', 'Sheila', '$2y$10$jUQX/r4NGWXehvolg.5yJev84DewrffjFFyQyXSb7Xf6lBUYG9dli', 'profiles/sheila.jpg', 'covers/post.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
