-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 20, 2019 at 01:53 PM
-- Server version: 10.3.13-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id11078589_kdu_newsletter`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` varchar(11) NOT NULL,
  `pass` varchar(64) NOT NULL,
  `type` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `pass`, `type`) VALUES
('000000', '75a0abb19a6690376ed901f9e2e2be3f', 0),
('0191234', '62c8ad0a15d9d1ca38d5dee762a16e01', 2),
('0191331', '03a3655fff3e9bdea48de9f49e938e32', 2),
('0191673', '6dff2291fe2e822de2e8068a182c4759', 2),
('0191975', '271f17707d8bfd2cd45f7e5182298703', 2);

-- --------------------------------------------------------

--
-- Table structure for table `annoucement`
--

CREATE TABLE `annoucement` (
  `id` int(11) NOT NULL,
  `content` varchar(100) NOT NULL,
  `date_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `post_by` varchar(11) NOT NULL,
  `date_posted` datetime NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `title`, `content`, `post_by`, `date_posted`, `type`, `status`) VALUES
(3, 'UOW Malaysia KDU University College 2019 Engineering Day', 'This event, dedicated to raising interest and awareness on matters relating to energy efficiency and renewable energy; also gained support from National Water Services Commission (SPAN) as well as Energy Commission Malaysia (EC) as these agencies are to play pivotal roles in supporting and deploying new clean energy projects; in line with the Government’s master plan to increase energy efficiency by 15% in 2030. In this connection, the School of Engineering being one of the energy grant recipient of RM55, 000 from SEDA under its RMK -11 framework forms an alliance as a long-term academia driven partner on this accelerating journey.', '000000', '2019-11-20 10:59:11', 'General', 1);

-- --------------------------------------------------------

--
-- Table structure for table `article_likes`
--

CREATE TABLE `article_likes` (
  `article_id` int(11) NOT NULL,
  `user_id` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `article_likes`
--

INSERT INTO `article_likes` (`article_id`, `user_id`) VALUES
(3, '000000');

-- --------------------------------------------------------

--
-- Table structure for table `article_rejected`
--

CREATE TABLE `article_rejected` (
  `article_id` int(11) NOT NULL,
  `reason` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(2) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `type`) VALUES
(1, 'General'),
(2, 'Sports'),
(3, 'News'),
(4, 'Foreign Country'),
(5, 'Video Games'),
(6, 'Memes');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date_posted` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_id`, `comment`, `date_posted`) VALUES
(1, 3, '000000', 'please subsribe', '2019-11-20 10:59:50');

-- --------------------------------------------------------

--
-- Table structure for table `event_desc`
--

CREATE TABLE `event_desc` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(4000) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `date_posted` datetime NOT NULL,
  `post_by` varchar(11) NOT NULL,
  `status` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_participant`
--

CREATE TABLE `event_participant` (
  `event_id` int(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `item_id` varchar(11) NOT NULL,
  `reason` varchar(200) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE `share` (
  `id` int(11) NOT NULL,
  `user_id` varchar(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `reply` varchar(200) DEFAULT NULL,
  `date_shared` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `share`
--

INSERT INTO `share` (`id`, `user_id`, `article_id`, `reply`, `date_shared`) VALUES
(3, '000000', 3, NULL, '2019-11-20 10:59:11');

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `user_id` varchar(11) NOT NULL,
  `user_id2` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `date_joined` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `contact`, `description`, `date_joined`) VALUES
('000000', 'ADMIN', 'bee@bee.com', '000-0000000', '', '2019-11-20'),
('0191234', 'Lee Zhen Xiang', 'zhenxiang_123@gmail.com', '018-1234567', NULL, '2019-11-20'),
('0191331', 'Lau Zihao', 'lauzh1997@gmail.com', '111-1212344', NULL, '2019-11-20'),
('0191673', 'Lam Jun Wei', 'hahacrazyman123@gmail.com', '012-3456789', 'Hi I am Lam Jun Wei Nice to meet you.', '2019-11-20'),
('0191975', 'Cheong Zhen Xuan', 'cheongzhenxuan1998@gmail.com', '012-3456789', NULL, '2019-11-20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `annoucement`
--
ALTER TABLE `annoucement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_by` (`post_by`);

--
-- Indexes for table `article_likes`
--
ALTER TABLE `article_likes`
  ADD UNIQUE KEY `article_id` (`article_id`,`user_id`);

--
-- Indexes for table `article_rejected`
--
ALTER TABLE `article_rejected`
  ADD UNIQUE KEY `article_id` (`article_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`) USING BTREE,
  ADD KEY `user_id_const` (`user_id`);

--
-- Indexes for table `event_desc`
--
ALTER TABLE `event_desc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_by_const` (`post_by`);

--
-- Indexes for table `event_participant`
--
ALTER TABLE `event_participant`
  ADD UNIQUE KEY `event_id` (`event_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id_cons` (`article_id`),
  ADD KEY `share_user_id` (`user_id`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD UNIQUE KEY `user_id` (`user_id`,`user_id2`),
  ADD KEY `sub_user2` (`user_id2`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `annoucement`
--
ALTER TABLE `annoucement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event_desc`
--
ALTER TABLE `event_desc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `share`
--
ALTER TABLE `share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `article_likes`
--
ALTER TABLE `article_likes`
  ADD CONSTRAINT `articleid_likes` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Constraints for table `article_rejected`
--
ALTER TABLE `article_rejected`
  ADD CONSTRAINT `article_idconst` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `article_id_constraint` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);

--
-- Constraints for table `event_participant`
--
ALTER TABLE `event_participant`
  ADD CONSTRAINT `event_id_contraints` FOREIGN KEY (`event_id`) REFERENCES `event_desc` (`id`);

--
-- Constraints for table `share`
--
ALTER TABLE `share`
  ADD CONSTRAINT `article_id_cons` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
