-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 10:22 AM
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
-- Database: `public_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `member_id`, `comment_text`, `created_at`) VALUES
(18, 9, 1, 'tes', '2025-10-20 05:24:15'),
(19, 9, 3, 'RIDHWAN GANTEG', '2025-10-20 05:29:11'),
(20, 10, 2, 'mantap bang', '2025-10-22 08:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `member_id`, `post_id`) VALUES
(47, 1, 9),
(50, 3, 9);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `username`, `password`, `created_at`) VALUES
(1, 'Ridhwan', 'Ridhwan123', '2025-10-14 07:30:37'),
(2, 'Raffi', 'Raffi123', '2025-10-14 07:30:37'),
(3, 'danen', 'danen123', '2025-10-20 03:29:57');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `member_id`, `title`, `content`, `image_path`, `created_at`, `updated_at`) VALUES
(3, 1, 'ace of spade', 'ace of spade is a highest card in poker hierarchy', 'uploads/68f48ab0813c4_01_of_spades_A.svg.png', '2025-10-14 09:56:21', '2025-10-19 09:32:00'),
(4, 2, '#1', 'hola', 'uploads/1760435875_istockphoto-505551939-612x612.jpg', '2025-10-14 09:57:55', '2025-10-14 09:58:44'),
(8, 1, 'AJAX (Asynchronus Javascript And XML)', 'AJAX, singkatan dari Asynchronous JavaScript and XML, merupakan salah satu teknologi penting dalam pengembangan web modern yang memungkinkan pertukaran data antara browser dan server tanpa harus memuat ulang seluruh halaman. Dengan menggunakan AJAX, sebuah halaman web dapat menampilkan atau memperbarui sebagian konten secara dinamis sesuai interaksi pengguna. Misalnya, ketika kita mengetik di kolom pencarian dan hasilnya muncul secara otomatis tanpa halaman berpindah, itulah contoh nyata penggunaan AJAX.\r\n\r\nSecara umum, AJAX memanfaatkan JavaScript di sisi klien untuk membuat permintaan (request) ke server, dan PHP di sisi server untuk memproses serta mengirimkan kembali data sebagai respon. Prosesnya bersifat asinkron, artinya browser tidak perlu menunggu respon server untuk melanjutkan aktivitas lain. Inilah yang membuat interaksi terasa lebih cepat dan responsif dibandingkan metode tradisional yang mengharuskan reload halaman setiap kali data dikirim', 'uploads/1760931437_images.png', '2025-10-20 03:37:17', '2025-10-20 03:37:17'),
(9, 2, 'Ace of Spades', 'Ace of Spades', 'uploads/1760934345_01_of_spades_A.svg.png', '2025-10-20 04:25:45', '2025-10-20 04:25:45'),
(10, 1, 'tes', 'test', 'uploads/1760937963_library.jpg', '2025-10-20 05:26:03', '2025-10-20 05:26:03'),
(11, 3, 'Leon FT', 'FATUR MANTA JAYA', 'uploads/1760938075_WhatsApp Image 2025-10-06 at 17.20.22.jpeg', '2025-10-20 05:27:55', '2025-10-20 05:27:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `member_id` (`member_id`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `member_id` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
