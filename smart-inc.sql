-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2021 at 06:39 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart-inc`
--

CREATE DATABASE IF NOT EXISTS `Smart-Inc`;
USE `Smart-Inc`;

CREATE USER 'smartUser'@'localhost' IDENTIFIED BY 'mysmartpassword';
GRANT ALL PRIVILEGES ON `smart-inc`.* TO 'smartUser'@'localhost' WITH GRANT OPTION;
-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `associatedUser` varchar(32) NOT NULL,
  `cartID` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cart` (`associatedUser`, `cartID`, `date`) VALUES ('customer', '1', current_timestamp()), 
('customer2', '2', current_timestamp());

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `cartID` int(11) NOT NULL,
  `productName` varchar(32) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `cartID` int(11) NOT NULL,
  `user` varchar(32) NOT NULL,
  `total` int(11) NOT NULL,
  `datePlaced` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `name` varchar(32) NOT NULL,
  `category` varchar(14) NOT NULL,
  `description` varchar(140) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`name`, `category`, `description`, `price`, `quantity`) VALUES
('55\\\" ULED TV', 'Technology', '(Wall Mount Not Included)', 1000, 99),
('Bethanie & Co. 14K Gold Necklace', 'Accessories', '26\\\"', 1250, 99),
('Bethanie & Co. Diamond Necklace', 'Accessories', '30\\\"  With Accents Sterling Silver', 2000, 0),
('Black Hoodie', 'Apparel', '100% Cotton', 25, 3),
('Black T-Shirt', 'Apparel', '100% Cotton', 12, 98),
('Blue Hoodie', 'Apparel', '100% Cotton', 25, 99),
('Blue Rain Jacket', 'Apparel', 'water-resistant', 120, 99),
('Brown T-Shirt', 'Apparel', '100% Cotton', 12, 99),
('Carlos Di Cartier Watch', 'Accessories', 'Small Model, Quartz Movement', 1500, 99),
('Faux Shrub Plant', 'Household', 'Faux Foilage', 45, 99),
('Gray T-Shirt', 'Apparel', '100% Cotton', 12, 99),
('Green Beanie', 'Apparel', '100% Acrylic', 28, 99),
('Green Rain Jacket', 'Apparel', 'water-resistant', 120, 99),
('Green T-Shirt', 'Apparel', '100% Cotton', 12, 99),
('IMEA Couch', 'Household', 'Fabric', 550, 99),
('IMEA Lamp', 'Household', '(Lamp Shade Not-Included)', 30, 99),
('IMEA Loveseat', 'Household', 'Fabric', 330, 99),
('Intil i10700K Gaming PC', 'Technology', '(Includes Free Keyboard+Mouse)', 2200, 98),
('iPear 12', 'Technology', 'Cell Phone', 999, 99),
('iPear 12 Plus', 'Technology', 'Cell Phone', 1299, 99),
('iPear Pad Pro', 'Technology', '12\\\" Screen', 1399, 99),
('Kneebok Athletic Shoes', 'Footwear', 'Genuine Leather', 250, 99),
('Kneebok Crossfit Training Shoes', 'Footwear', 'Lightweight and Machine Washable', 80, 99),
('Leather Belt', 'Apparel', '100% Leather', 200, 99),
('Luasha De Cartier Watch', 'Accessories', '41MM, Automatic Movement', 1350, 99),
('Purple Beenie', 'Apparel', '100% Acrylic', 28, 99),
('Red Beanie', 'Apparel', '100% Acrylic', 28, 99),
('Red Hoodie', 'Apparel', '100% Cotton', 25, 99),
('Red Rain Jacket', 'Apparel', 'water-resistant', 120, 99),
('Red T-Shirt', 'Apparel', '100% Cotton', 12, 99),
('Stainless Steel GoleX', 'Accessories', 'Swiss Luxury', 300, 99),
('Tan T-Shirt', 'Apparel', '100% Cotton', 12, 99),
('The South Face Beenie', 'Apparel', '100% Acrylic', 28, 99),
('White T-Shirt', 'Apparel', '100% Cotton', 12, 99),
('Wooden Coffee Table', 'Household', 'Oak Wood', 70, 99),
('Yellow T-Shirt', 'Apparel', '100% Cotton', 12, 99),
('Zuma Athletic Shoes', 'Footwear', 'Born for speed', 100, 99),
('Zuma Enzi V.1 Athletic Shoes', 'Footwear', 'Limited Edition', 225, 99);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `Username` varchar(32) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(32) NOT NULL,
  `Address` varchar(128) NOT NULL,
  `Admin` int(11) DEFAULT NULL,
  `Manager` int(11) DEFAULT NULL,
  `Employee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`Username`, `Password`, `Email`, `Address`, `Admin`, `Manager`, `Employee`) VALUES
('admin', '$2y$10$SY3D/4d2e7GsNnQJNUxZ1u5ZcwPIk7AKAf2zo9wBX8GpSQ9rC5pjq', 'smartadmin@smart.com', '1 Street, Montclair, NJ 07043', 1, NULL, NULL),
('customer', '$2y$10$2NHXO6Yot/VFFx6LeNfDjOcgicvwm0cNZiJEkd7SU2BF3BoiYK6Yy', 'smartcustomer@smart.com', '1 Street, Montclair, NJ 07043', NULL, NULL, NULL),
('customer2', '$2y$10$/MdfNDg1ZYuzPtb5FPfeoOtn53IdtAXWLEQK2ZzOfDAcoiX3n0GKm', 'customer@mail.com', '1 Street, Montclair, NJ 07043', NULL, NULL, NULL),
('nelsayed', '$2y$10$HAyUIxpKOd8y0.vpTQihXe0YDJbjqMHzNsRqQuwWJM/hihb1WJKMm', 'colloraelsn1@montclair.edu', '191 Seminole Road, Lafayette, NJ 07848', NULL, NULL, 1),
('pdolfini', '$2y$10$C.bkmOQhkTDpRpaMk2bVF.Mh7jAVBEItJcSh25uIxYn69IGJp4Xbm', 'thedolfinip@gmail.com', '13th Street, New York City, NY 10030', NULL, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `associatedUser` (`associatedUser`);

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD KEY `cartID` (`cartID`),
  ADD KEY `productName` (`productName`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD KEY `cartID` (`cartID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`associatedUser`) REFERENCES `user` (`Username`);

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `cartitems_ibfk_1` FOREIGN KEY (`cartID`) REFERENCES `cart` (`cartID`),
  ADD CONSTRAINT `cartitems_ibfk_2` FOREIGN KEY (`productName`) REFERENCES `products` (`name`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cartID`) REFERENCES `cart` (`cartID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
