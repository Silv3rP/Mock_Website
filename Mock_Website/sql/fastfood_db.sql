-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 14, 2025 at 10:28 AM
-- Server version: 5.7.39
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fastfood_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `fast_food`
--


CREATE TABLE `fast_food` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) DEFAULT NULL,
  `description` text,
  `price` decimal(5,2) DEFAULT NULL,
  `customer_rating` decimal(2,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fast_food`
--

INSERT INTO `fast_food` (`item_id`, `item_name`, `description`, `price`, `customer_rating`) VALUES
(1, 'Los Pollos Locos Wings', 'Crispy wings with a wild twist.', 8.99, 4.8),
(2, 'Heisenburger', 'Grilled chicken burger with blue cheese sauce.', 10.99, 4.7),
(3, 'The Jesse Tender', 'Juicy chicken tenders with BBQ sauce.', 7.99, 4.5),
(4, 'Saul\'s Spicy Strips', 'Hot and spicy chicken strips.', 9.49, 4.6),
(5, 'The Blue Meth Buffalo Bites', 'Buffalo chicken bites with blue ranch dip.', 8.49, 4.8),
(6, 'Walter White Hot Wings', 'Extra hot wings with a secret sauce.', 9.99, 4.9),
(7, 'Hank\'s Honey BBQ Drumsticks', 'Sweet and tangy BBQ drumsticks.', 8.99, 4.7),
(8, 'Fring\'s Fiery Nuggets', 'Crispy nuggets with a fiery dip.', 6.99, 4.5),
(9, 'Mike\'s Mild Chicken Wrap', 'Mildly spiced chicken wrap.', 7.49, 4.3),
(10, 'Skinny Pete\'s Popcorn Chicken', 'Bite-sized crispy chicken.', 6.49, 4.2),
(11, 'Badger\'s Big Chicken Sandwich', 'Hefty fried chicken sandwich.', 11.49, 4.6),
(12, 'Tuco\'s Tandoori Thighs', 'Spicy tandoori chicken thighs.', 9.99, 4.8),
(13, 'The Gale Grilled Chicken', 'Classic grilled chicken breast.', 8.99, 4.5),
(14, 'Lydia\'s Lemon Pepper Wings', 'Zesty lemon pepper wings.', 9.49, 4.7),
(15, 'Jane\'s Jalapeño Strips', 'Crispy chicken strips with jalapeño dip.', 8.49, 4.4),
(16, 'Todd\'s Twisted Tenders', 'Twisted and breaded chicken tenders.', 7.99, 4.5),
(17, 'Combo\'s Crispy Chicken Burger', 'Crunchy chicken fillet burger.', 10.49, 4.6),
(18, 'The Cartel Classic', 'Signature cartel-style fried chicken.', 11.99, 4.8),
(19, 'Salamanca\'s Sizzling Strips', 'Sizzling spicy chicken strips.', 9.49, 4.7),
(20, 'The \"Yeah Science!\" Sriracha Wings', 'Hot sriracha wings with a science-like tang.', 9.99, 4.8),
(21, 'Pinkman\'s Parmesan Popcorn Chicken', 'Parmesan-dusted popcorn chicken.', 7.49, 4.5),
(22, 'The Tortuga Tandoori Wrap', 'Bold tandoori chicken in a wrap.', 8.99, 4.3),
(23, 'Los Pollos Parmesan Bites', 'Crispy chicken bites with parmesan.', 8.49, 4.6),
(24, 'Uncle Jack\'s Jacked-Up Chicken Strips', 'Super-sized spicy chicken strips.', 9.49, 4.7),
(25, 'Brock\'s BBQ Boneless Wings', 'Boneless wings with smoky BBQ sauce.', 8.99, 4.5),
(26, 'The Madrigal Honey Mustard Wings', 'Honey mustard glazed wings.', 9.49, 4.6),
(27, 'Flynn\'s Fiery Fries (with Chicken Dip)', 'Spicy fries with chicken dip.', 6.99, 4.3),
(28, 'Skyler\'s Smoky Chicken Bowl', 'Smoked chicken with veggies.', 10.99, 4.6),
(29, 'Hector\'s Hot Chicken', 'Blazing hot chicken pieces.', 9.99, 4.8),
(30, 'The Chemist\'s Charbroiled Chicken', 'Perfectly grilled chicken.', 9.49, 4.5),
(31, 'Better Call Slaw (with Chicken Tenders)', 'Crispy chicken tenders with slaw.', 8.99, 4.4),
(32, 'Breaking Bites Boneless Chicken', 'Tender boneless bites.', 7.99, 4.5),
(33, 'Fring’s Secret Sauce Wings', 'Wings with a top-secret sauce.', 9.99, 4.9),
(34, 'The Blue Sky Chicken Sandwich', 'Chicken sandwich with blue cheese.', 10.99, 4.6),
(35, 'Cluckin\' Car Wash Crispy Chicken', 'Crispy chicken served with dipping sauce.', 8.99, 4.5),
(36, 'Tuco’s Taco Chicken Wrap', 'Spicy chicken taco wrap.', 8.49, 4.4),
(37, 'Krazy-8’s Korean BBQ Wings', 'Korean-style BBQ wings.', 9.99, 4.7),
(38, 'Walt Jr.’s Wing Platter', 'Mixed platter of wings.', 11.99, 4.8),
(39, 'The RV Roast Chicken', 'Slow-roasted chicken.', 10.99, 4.6),
(40, 'El Camino Extra Crispy Strips', 'Extra crispy chicken strips.', 8.99, 4.5),
(41, 'Los Pollos Poppers', 'Spicy chicken poppers.', 7.99, 4.4),
(42, '“I Am The Sauce” Honey Mustard Wings', 'Honey mustard glazed wings.', 9.49, 4.7),
(43, 'The Lab-Coated Lemon Pepper Wings', 'Lemon pepper wings with a scientific twist.', 9.99, 4.8),
(44, 'The Gustavo Grilled Chicken Deluxe', 'Deluxe grilled chicken dish.', 12.49, 4.9),
(45, 'Cookhouse Crispy Combo', 'Crispy chicken combo meal.', 10.49, 4.6),
(46, 'The Cartel Cutlet Sandwich', 'Breaded chicken cutlet sandwich.', 10.99, 4.7),
(47, 'Fring’s Fuego Fried Chicken', 'Fiery fried chicken.', 11.49, 4.8),
(48, 'Salamanca Street-Style Wings', 'Street-style spicy wings.', 9.49, 4.7),
(49, '\"Say My Name\" Sweet Chili Strips', 'Sweet chili chicken strips.', 9.99, 4.8),
(50, 'The Empire Fried Chicken Bucket', 'Large bucket of fried chicken.', 14.99, 5.0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fast_food`
--
ALTER TABLE `fast_food`
  ADD PRIMARY KEY (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fast_food`
--
ALTER TABLE `fast_food` MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
