-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 05, 2019 at 08:49 PM
-- Server version: 10.0.38-MariaDB-cll-lve
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ctjoppmqhosting_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `ban_list`
--

CREATE TABLE `ban_list` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ban_reason` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `banned_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat_room`
--

CREATE TABLE `chat_room` (
  `room_id` int(11) NOT NULL,
  `room_name` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `room_description` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `owner` int(11) NOT NULL,
  `created_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_room`
--

INSERT INTO `chat_room` (`room_id`, `room_name`, `room_description`, `owner`, `created_time`) VALUES
(1, 'Minh Long', 'Hello', 2, '2019-07-05 20:29:22');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `sent_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `room_id`, `message`, `sent_time`) VALUES
(96, 2, 1, 'net user minh long abc123! /add', '2019-04-23 10:08:39'),
(97, 2, 1, 'nghèo khổ', '2019-04-23 10:10:49'),
(98, 1, 1, 'nghèo khổ', '2019-04-23 10:12:11'),
(99, 2, 1, 'thứ nghèo khổ cơ cực cơ hàn nhọc nhằn bần tiện tiện nhân tiện tì nô tì ti tiện', '2019-04-23 10:14:12'),
(100, 1, 1, 'cc', '2019-04-23 10:15:20'),
(101, 1, 1, 'Hi!', '2019-05-07 21:57:48'),
(102, 1, 1, 'Hihi', '2019-05-07 21:57:58'),
(103, 1, 1, 'hi', '2019-06-30 01:39:13'),
(104, 1, 5, 'chào', '2019-06-30 01:39:23'),
(105, 1, 1, 'Alo test :)', '2019-07-04 06:55:52'),
(106, 1, 1, 'Haha', '2019-07-04 06:55:56'),
(107, 5, 1, 'hi', '2019-07-04 15:52:56'),
(108, 1, 9, 'hi', '2019-07-05 19:33:40'),
(109, 2, 9, 'hi', '2019-07-05 19:34:05'),
(110, 1, 18, 'Con đĩ nghèo khổ', '2019-07-05 19:56:30');

-- --------------------------------------------------------

--
-- Table structure for table `request_join`
--

CREATE TABLE `request_join` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `room_member`
--

CREATE TABLE `room_member` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `join_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstName` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `lastName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `profilePicture` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `session` text NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `joinned_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstName`, `lastName`, `profilePicture`, `username`, `password`, `session`, `admin`, `verified`, `joinned_time`) VALUES
(1, 'Vy', 'Nghĩa', 'https://nghia.org/project/chat/assets/img/default.jpg', 'vynghia', '@vynghia123', '14776210a1c0fb7e9da355a8332f2e4c', 1, 0, '0000-00-00 00:00:00'),
(2, 'Minh', 'Long', 'https://nghia.org/project/chat/assets/img/default.jpg', 'minhlong', '123456', '871924a52f3b4d52de38f9dea0164fef', 0, 0, '0000-00-00 00:00:00'),
(3, 'Thành', 'Tâm', 'https://nghia.org/project/chat/assets/img/default.jpg', 'thanhtam', '123456', 'a3cebfaad129a9b3e6710735548dcb60', 0, 0, '0000-00-00 00:00:00'),
(4, 'Trà', 'Sữa', 'https://nghia.org/project/chat/assets/img/default.jpg', 'trasua', '123456', '12c2c8a4f8123eccf14cc66340ddfaa9', 0, 0, '0000-00-00 00:00:00'),
(16, 'Vy', 'Nghia ', 'https://nghia.org/project/chat/assets/img/default.jpg', 'vynghia2gm', '1151985611', '8ef8f0fa6646062be1724d68c3d243ca', 0, 0, '2019-07-04 21:08:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ban_list`
--
ALTER TABLE `ban_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_join`
--
ALTER TABLE `request_join`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_member`
--
ALTER TABLE `room_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ban_list`
--
ALTER TABLE `ban_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `request_join`
--
ALTER TABLE `request_join`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `room_member`
--
ALTER TABLE `room_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
