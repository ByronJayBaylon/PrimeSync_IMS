-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2023 at 02:30 AM
-- Server version: 5.6.21
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `posimsci`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
`id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` varchar(50) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `account_type`, `date_created`, `created_by`) VALUES
(1, 'admin', '$2y$10$L6BKTF8v7Ok/meG.P6eCi.VoSR4bZsEt/M7PZqy37fsajHOP5m/0a', 'Admin', '2023-12-07 00:38:12', 'Admin'),
(2, 'clerk', '$2y$10$L6BKTF8v7Ok/meG.P6eCi.VoSR4bZsEt/M7PZqy37fsajHOP5m/0a', 'Clerk', '2023-12-06 21:45:50', 'admin'),
(3, 'cashier', '$2y$10$L6BKTF8v7Ok/meG.P6eCi.VoSR4bZsEt/M7PZqy37fsajHOP5m/0a', 'Cashier', '2023-12-06 21:46:00', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`id` int(11) NOT NULL,
  `date_time` datetime DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `date_time`, `category`, `creator`) VALUES
(1, '2024-11-14 08:44:20', 'Sinandomeng', 'admin'),
(2, '2024-11-14 08:44:20', 'Dinorado', 'admin'),
(3, '2024-11-14 08:44:20', 'Milagrosa', 'admin'),
(4, '2024-11-14 08:44:20', 'Brown Dinorado', 'admin'),
(5, '2024-11-14 08:44:20', 'Malagkit na Puti', 'admin'),
(6, '2024-11-14 08:44:20', 'Malagkit na Itim', 'admin'),
(7, '2024-11-14 08:44:20', 'Red Rice', 'admin');
(8, '2024-11-14 08:44:20', 'Black Rice', 'admin');
(9, '2024-11-14 08:44:20', 'Doña Maria Jasponica', 'admin');
(10, '2024-11-14 08:44:20', 'Doña Maria Miponica', 'admin');
(11, '2024-11-14 08:44:20', 'Tinawon', 'admin');
(12, '2024-11-14 08:44:20', 'Unoy', 'admin');
(13, '2024-11-14 08:44:20', 'Angelica', 'admin');
(14, '2024-11-14 08:44:20', 'Balatinaw', 'admin');
(15, '2024-11-14 08:44:20', 'Malagkit na Pula', 'admin');
(16, '2024-11-14 08:44:20', 'Japanese Rice (Sushi)', 'admin');
(17, '2024-11-14 08:44:20', 'Basmati Rice', 'admin');
(18, '2024-11-14 08:44:20', 'Jasmine Rice', 'admin');
(19, '2024-11-14 08:44:20', 'Calrose Rice', 'admin');
(20, '2024-11-14 08:44:20', 'Arborio', 'admin');
-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `ProcessingMethod` varchar(100) DEFAULT NULL,
  `description` text,
  `date_time` datetime DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `quantities` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
  `creator` varchar(100) DEFAULT NULL,
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `ProcessingMethod`, `description`, `date_time`,`supplier`, `quantities`, `price`, `creator`) VALUES
(1, 'Sinandomeng', 'Medium Grain', 'Milled (White)', '2023-12-07 08:44:45', 'Nueva Farmers Co.', 5000, 66.00, 'admin'),
(2, 'Dinorado', 'Long Grain', 'Milled (White)', '2023-12-07 10:11:16', 'Isabela Rice Traders', 3000, 82.50, 'admin'),
(3, 'Milagrosa', 'Long Grain', 'Milled (White)', '2023-12-07 10:13:48', 'Angelica Rice Supply', 2000, 99.00, 'admin'),
(4, 'Brown Dinorado', 'Long Grain', 'Brown (Unmilled)', '2023-12-07 10:52:53', 'Nueva Farmers Co.', 1500, 110.00, 'admin'),
(5, 'Malagkit na Puti', 'Short Grain', 'Glutinous (White)', '2023-12-07 10:53:21', 'Bulacan Sticky Rice Co.', 1000, 137.50, 'admin'),
(6, 'Malagkit na Itim', 'Short Grain', 'Glutinous (Black)', '2023-12-07 11:27:42', 'Bulacan Sticky Rice Co.', 800, 165.00, 'admin'),
(7, 'Red Rice', 'Long Grain', 'Red (Unmilled)', '2023-12-07 11:28:00', 'Ifugao Heirloom Traders', 1200, 121.00, 'admin'),
(8, 'Black Rice', 'Short Grain', 'Black (Unmilled)', '2023-12-07 11:28:38', 'Cordillera Organic Co.', 600, 192.50, 'admin'),
(9, 'Doña Maria Jasponica', 'Medium Grain', 'Milled (White)', '2023-12-07 11:29:03', 'Jasponica Rice Trading', 2500, 99.00, 'admin'),
(10, 'Doña Maria Miponica', 'Short Grain', 'Milled (White)', '2023-12-07 11:29:22', 'Miponica Rice Suppliers', 2200, 104.50, 'admin'),
(11, 'Tinawon', 'Long Grain', 'Brown (Unmilled)', '2023-12-07 11:29:40', 'Ifugao Heirloom Traders', 700, 165.00, 'admin'),
(12, 'Unoy', 'Long Grain', 'Red (Unmilled)', '2023-12-08 09:14:26', 'Cordillera Organic Co.', 500, 176.00, 'admin'),
(13, 'Angelica', 'Long Grain', 'Milled (White)', '2023-12-08 09:14:26', 'Angelica Rice Supply', 4000, 88.00, 'admin'),
(14, 'Balatinaw', 'Short Grain', 'Black (Unmilled)', '2023-12-08 09:14:26', 'Cordillera Heirloom Co.', 400, 203.50, 'admin'),
(15, 'Malagkit na Pula', 'Short Grain', 'Glutinous (Red)', '2023-12-08 09:14:26', 'Bulacan Sticky Rice Co.', 900, 154.00, 'admin'),
(16, 'Japanese Rice (Sushi)', 'Short Grain', 'Milled (White)', '2023-12-08 09:14:26', 'Tokyo Rice Exports', 1500, 220.00,'admin'),
(17, 'Basmati Rice', 'Long Grain', 'Milled (White)', '2023-12-08 09:14:26', 'Karachi Rice Traders', 1800, 275.00, 'admin'),
(18, 'Jasmine Rice', 'Long Grain', 'Milled (White)', '2023-12-08 09:14:26', 'Bangkok Rice Exporters', 2200, 137.50, 'admin'),
(19, 'Calrose Rice', 'Long Grain', 'Milled (White)', '2023-12-08 09:14:26', 'California Rice Co.', 1200, 165.00, 'admin'),
(20, 'Arborio Rice', 'Short Grain', 'Milled (White)', '2023-12-08 09:14:26', 'Italian Grain Co.', 800, 330.00, 'admin');
-- --------------------------------------------------------

--
-- Table structure for table `sales`
--
CREATE TABLE IF NOT EXISTS `sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `sub_total` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `month` int(11) GENERATED ALWAYS AS (MONTH(`date`)) STORED,
  `year` int(11) GENERATED ALWAYS AS (YEAR(`date`)) STORED,
  `week` int(11) GENERATED ALWAYS AS (WEEK(`date`)) STORED,
  PRIMARY KEY (`sale_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- Inserting new data for the `sales` table
INSERT INTO `sales` (`sale_id`, `item_id`, `item_name`, `date`, `quantity`, `sub_total`) VALUES
(1,1, 'Sinandomeng', '2024-01-05', 50, 3300),
(2,1, 'Sinandomeng', '2024-02-10', 100, 6600),
(3,1, 'Sinandomeng', '2024-03-15', 200, 13200),
(4,2, 'Dinorado', '2024-01-12', 30, 2475),
(5,2, 'Dinorado', '2024-02-18', 60, 4950),
(6,3, 'Milagrosa', '2024-03-25', 90, 8910),
(7,4, 'Brown Dinorado', '2024-04-05', 45, 4950),
(8,4, 'Brown Dinorado', '2024-05-15', 80, 8800),
(9,5, 'Malagkit na Puti', '2024-06-20', 70, 9625),
(10,6, 'Malagkit na Itim', '2024-07-18', 60, 9900),
(11,6, 'Malagkit na Itim', '2024-08-28', 30, 4950);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
 ADD PRIMARY KEY (`sale_id`), ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;