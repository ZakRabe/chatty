-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 14, 2014 at 08:37 AM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chatty`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `name` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `text`, `name`, `city`, `email`) VALUES
(1, 'Hey there', 'John ', 'Norfolk', 'Iloveyou@heythere.org'),
(2, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam elit arcu, pharetra tristique lectus a, luctus laoreet turpis. Donec ac mattis sapien. Nullam sodales, purus nec fringilla tempor, mi dolor ultricies libero, vel feugiat risus dui a tortor. Mauris facilisis lectus vitae felis fringilla, id consequat tortor dictum. Mauris ut tellus scelerisque, porttitor tortor in, imperdiet eros. Maecenas commodo felis sit amet placerat malesuada. Proin aliquam mauris nisi, ac varius felis laoreet non. Donec eu commodo tellus, non iaculis lectus. Curabitur pellentesque iaculis magna, at imperdiet dolor feugiat id.', 'Long', 'Storybro', 'blahblah@hey.org'),
(3, 'Def gonna type the whole thing', 'DR. Mr. Dave McParverzunz', '42 Wallaby Way, Sydney, AU, EARTH', 'jeeze@typingishard.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
