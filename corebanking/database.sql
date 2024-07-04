-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 04, 2024 at 02:16 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `corebanking`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `noac` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `pin` int(6) NOT NULL,
  `balance` double NOT NULL,
  `lasttxid` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`noac`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cabang`
--

DROP TABLE IF EXISTS `cabang`;
CREATE TABLE IF NOT EXISTS `cabang` (
  `cabang` int(10) NOT NULL,
  `keterangan` tinytext NOT NULL,
  PRIMARY KEY (`cabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `noac` varchar(20) NOT NULL,
  `txid` bigint(20) NOT NULL,
  `cabang` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `noac` (`noac`,`txid`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zoperator`
--

DROP TABLE IF EXISTS `zoperator`;
CREATE TABLE IF NOT EXISTS `zoperator` (
  `userid` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `creaby` varchar(30) NOT NULL,
  `creatime` datetime NOT NULL,
  `modiby` varchar(30) NOT NULL,
  `moditime` datetime NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zoperator`
--

INSERT INTO `zoperator` (`userid`, `password`, `creaby`, `creatime`, `modiby`, `moditime`) VALUES
('hendra', 'd8f68c1ab79ab971a3835f2c0315c34f8214a113', 'admin', '2024-06-12 09:04:19', 'hendra', '2024-06-13 10:14:48');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `FK_transaction_account` FOREIGN KEY (`noac`) REFERENCES `account` (`noac`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
