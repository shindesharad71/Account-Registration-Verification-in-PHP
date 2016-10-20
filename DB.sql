-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2016 at 01:56 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `intern`
--

-- --------------------------------------------------------

--
-- Table structure for table `intern`
--

CREATE TABLE `intern` (
  `id` int(5) NOT NULL,
  `username` varchar(70) NOT NULL,
  `password` varchar(70) NOT NULL,
  `email` varchar(250) NOT NULL,
  `active` varchar(5) NOT NULL,
  `code` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `intern`
--

INSERT INTO `intern` (`id`, `username`, `password`, `email`, `active`, `code`) VALUES
(1, 'admin', 'admin', 'admin@portal.com', '1', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `intern`
--
ALTER TABLE `intern`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `intern`
--
ALTER TABLE `intern`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
