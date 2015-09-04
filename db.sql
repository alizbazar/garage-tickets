-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 04, 2015 at 04:47 PM
-- Server version: 5.5.42-cll
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vntradeo_vntrade`
--

-- --------------------------------------------------------

--
-- Table structure for table `garage_invites`
--

CREATE TABLE IF NOT EXISTS `garage_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(7) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` text NOT NULL,
  `referredBy` varchar(7) NOT NULL,
  `freeInvites` int(11) DEFAULT NULL,
  `registered` timestamp NULL DEFAULT NULL,
  `visited` timestamp NULL DEFAULT NULL,
  `waitinglist` tinyint(1) DEFAULT NULL,
  `dev` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1017 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
