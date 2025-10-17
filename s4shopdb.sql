-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 17, 2025 at 03:31 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s4shopdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image`, `slug`) VALUES
(1, 'Febric', 'hello', 'ChatGPT Image Oct 17, 2025, 01_01_11 AM.png', 'febric');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `weight` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `referral_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `payment_status` enum('Pending','Paid','Failed','Refunded') DEFAULT 'Pending',
  `payment_screenshot` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `name`, `address`, `email`, `phone`, `size`, `product_id`, `weight`, `quantity`, `total`, `referral_code`, `created_at`, `status`, `payment_status`, `payment_screenshot`) VALUES
(1, 'Prakash Sharma', 'Udaipur', 'prakash@gmail.com', '9664100138', '', 1, '', 1, '620.00', 'NAM016', '2025-10-17 09:58:09', 'Pending', 'Refunded', 'uploads/payments/1760694921_ChatGPT Image Oct 17, 2025, 01_01_11 AM.png');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sizes` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `feature-img` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `sizes`, `slug`, `description`, `price`, `feature-img`, `image`) VALUES
(1, 1, 'S4 Smart Combo Pack 1 – Fashion Essentials', '', 's4-smart-combo-pack-1-fashion-essentials', '<p>Upgrade your style effortlessly with the <strong>S4 Smart Combo Pack</strong>! This all-in-one fashion kit includes premium <strong>fabric</strong>, a stylish <strong>watch</strong>, trendy <strong>shades</strong>, and a classic <strong>belt</strong>&mdash;everything you need to look sharp and confident. Perfect for daily wear or gifting, this combo is designed for those who love convenience without compromising on style.</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p><strong>Premium Fabric</strong> &ndash; High-quality material for comfortable and stylish wear</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Stylish Watch</strong> &ndash; Sleek design to complement any outfit</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Trendy Shades</strong> &ndash; Protect your eyes while staying fashionable</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Classic Belt</strong> &ndash; Durable and versatile to complete your look</p>\r\n	</li>\r\n</ul>\r\n', '620.00', 'ChatGPT-image-Oct.png', 'Screenshot 2025-10-16 202932.png'),
(2, 1, 'S4 Smart Combo Pack 2 – Stylish Essentials', NULL, 's4-smart-combo-pack-2-stylish-essentials', '<p>Upgrade your style effortlessly with the <strong>S4 Smart Combo Pack</strong>! This all-in-one fashion kit includes premium <strong>fabric</strong>, a stylish <strong>watch</strong>, trendy <strong>shades</strong>, and a classic <strong>belt</strong>&mdash;everything you need to look sharp and confident. Perfect for daily wear or gifting, this combo is designed for those who love convenience without compromising on style.</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p><strong>Premium Fabric</strong> &ndash; High-quality material for comfortable and stylish wear</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Stylish Watch</strong> &ndash; Sleek design to complement any outfit</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Trendy Shades</strong> &ndash; Protect your eyes while staying fashionable</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Classic Belt</strong> &ndash; Durable and versatile to complete your look</p>\r\n	</li>\r\n</ul>\r\n', '800.00', 'front-view-woman-with-shopping-bag-concept.jpg', 'front-view-woman-with-shopping-bag-concept.jpg'),
(3, 1, 'S4 Smart Combo Pack 3 – Economy Essentials', NULL, 's4-smart-combo-pack-3-economy-essentials', '<p><strong>What&rsquo;s Included:</strong></p>\r\n\r\n<ul>\r\n	<li>\r\n	<p><strong>Premium Fabric</strong> &ndash; High-quality material for comfortable and stylish wear</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Stylish Watch</strong> &ndash; Sleek design to complement any outfit</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Trendy Shades</strong> &ndash; Protect your eyes while staying fashionable</p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Classic Belt</strong> &ndash; Durable and versatile to complete your look</p>\r\n	</li>\r\n</ul>\r\n', '1000.00', 'Best-Network-Marketing-Tips-from-MLM-Leaders.jpg', 'Best-Network-Marketing-Tips-from-MLM-Leaders.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_code`, `name`, `email`, `phone`, `password`) VALUES
(1, 'NAM001', 'Kirti', 'demo@gmail.com', '6375699256', '$2y$10$pcA9ecEv1JPm6gu787ZSAuUTX7nlzYPPB6osJGgW7v38kxRh.NjWW'),
(2, 'NAM002', 'sunita', 'sunita@gmail.com', '9876543211', '$2y$10$p9XLMhZrrjrukQ6tHlAQZeaYispPM1cfnwfMYXqxiKb8iZ.yl52dG'),
(3, 'NAM003', 'rakhi', 'kirtijain9347@gmail.com', '6375699256', '$2y$10$OA//inl/ugxfjDMx.7YnFOJudGQpq2tV/aKaAbBK9tPRyW8mqOtju'),
(4, 'NAM004', 'New User', 'new@example.com', '', 'test123'),
(5, 'NAM005', 'New User', 'new@example.com', '9999999999', 'test123'),
(6, 'NAM006', 'vinita', 'vinita@gmail.com', '9999999999', '$2y$10$AoLwC4qgph.liTbqJlrk0uSxMBJuXVB6RCpSX3Cevz4j0CJA2qzCW'),
(7, 'NAM007', 'sarika', 'sarika@gmail.com', '6375699256', '$2y$10$HbRrsJZtft/E3Jmyy1DQHeWcTUAvB1a4uDn5N9wWaAUwU.3TUIIPO'),
(8, 'NAM008', 'Kirti', 'kirtijain63@gmail.com', '6375699256', '$2y$10$st9aMDyXnPcOouA/Z4CidupRuxgzuHE.yC6YUD/Ox7bYyCWNXbCF6'),
(9, 'NAM009', 'Kirti', 'bhavneshwari@gmail.com', '6375699256', '$2y$10$hgldgaPcRY4S36tF82Etgel4di7lZNMJnDEmox5FKhvMHi3kg1jvW'),
(10, 'NAM010', 'Kirti12', 'Kirti12@gmail.com', '9664289081', '$2y$10$qDn1fUjJIW4A2Uqiu95djuWoDBod1LdEMB1UxZUcTVJYAjGIQBjBK'),
(11, 'NAM011', 'Kirti', 'kirtijainn@gmail.com', '6375699256', '$2y$10$UzYsjV/DkhiqFpWb716/Muq30YxqUDJNHFKBwDY8dm/8pJb0esTKe'),
(12, 'NAM012', 'Kirti', 'kirtijain@gmail.com', '6375699256', '$2y$10$VXt/RaWcU0RAmuDa5/JJ.eS9TQGN2dQS6CW3HhmVLkD1tZSwldg02'),
(13, 'NAM013', 'nik', 'nik@gmail.com', '6375699256', '$2y$10$d55FcfJ8MATngwqvcSMD6ukJkcMm4G34SqEYkuAXeeoaDO6r.X3ca'),
(14, 'NAM014', 'vikas', 'vikas@gmail.com', '6378884528', '$2y$10$1sjpHmcutLKHndBOE83qHuV4rF9/hwOuhp2JFuoOXM91GMyK7n1T2'),
(15, 'NAM015', 'kv', 'kv@gmail.com', '9676565453', '$2y$10$2SvplotYaYeNUJOvpaobmuHXtALUuFqchidK5Jh0XFtMljgtJ7IOG'),
(16, 'NAM016', 'Prakash Sharma', 'prakash@muskowl.com', '9664100138', '$2y$10$Pe46EaNduu1R1kEWPyOvyu8zD3hgXjyLdr74TWPfR48CYZA8dpLqi');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `before_insert_users` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
  DECLARE next_id INT;

  -- Get next auto_increment ID
  SELECT AUTO_INCREMENT INTO next_id
  FROM information_schema.tables
  WHERE table_name = 'users' AND table_schema = DATABASE();

  -- Generate code like NAM001, NAM002, etc.
  SET NEW.user_code = CONCAT('NAM', LPAD(next_id, 3, '0'));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_slug` (`slug`),
  ADD UNIQUE KEY `description` (`description`) USING HASH;

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`,`name`,`email`,`phone`,`product_id`,`weight`,`quantity`,`total`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
