-- --------------------------------------------------------
-- Sunucu:                       127.0.0.1
-- Sunucu sürümü:                10.4.32-MariaDB - mariadb.org binary distribution
-- Sunucu İşletim Sistemi:       Win64
-- HeidiSQL Sürüm:               12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- puantoryeni için veritabanı yapısı dökülüyor
CREATE DATABASE IF NOT EXISTS `puantoryeni` /*!40100 DEFAULT CHARACTER SET latin5 COLLATE latin5_turkish_ci */;
USE `puantoryeni`;

-- tablo yapısı dökülüyor puantoryeni.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` int(11) DEFAULT 1,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `firm_id` int(11) NOT NULL DEFAULT 0,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `user_roles` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `is_main_user` int(1) DEFAULT NULL,
  `sicil_no` varchar(50) DEFAULT NULL,
  `yetkinlik_no` varchar(50) DEFAULT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `remember_token` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`firm_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- puantoryeni.users: ~13 rows (yaklaşık) tablosu için veriler indiriliyor
DELETE FROM `users`;
INSERT INTO `users` (`id`, `user_type`, `parent_id`, `firm_id`, `full_name`, `email`, `password`, `phone`, `job`, `title`, `user_roles`, `status`, `is_main_user`, `sicil_no`, `yetkinlik_no`, `session_token`, `remember_token`, `created_at`) VALUES
	(3, 1, 0, 0, 'Bilge Kazaz', 'bilgekazaz@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '507', 'Mühendis', '', 1, 1, NULL, NULL, NULL, NULL, NULL, '2024-08-30 16:00:31'),
	(4, 1, 0, 0, 'Mehmet Ali Gökmen', 'beyzade83@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '539', '', '', 9, 0, 1, NULL, NULL, NULL, NULL, '2024-09-04 15:56:13'),
	(60, 2, 0, 8, 'Gökmen', 'beyzade83@hotmail.com', '$2y$10$vS1eJAEjs/0idfv34AgJA.eWD.m9utaK4THvOUoa8/oiSkG7Wf6aK', '05079432723', 'Yönetici', NULL, 22, 1, 1, NULL, NULL, NULL, NULL, '2024-09-30 19:18:25'),
	(61, 1, 0, 0, 'Avni Akgöz ', 'avniiakgoz@gmail.com', '$2y$10$81U50qIwKRyuudnpah4IL.zu95ZExgD0lO23PNYglLrKbpA2MlaO6', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, '2024-09-26 19:29:00'),
	(63, 1, 0, 11, 'Ümüt Ünal', 'umutunal.91@gmail.com', '$2y$10$yip0iyY8brgrIKShtU5omeVcdOCm1cqSLtOTdicQX2.QiisOvyu7a', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, '2024-09-27 08:57:57'),
	(64, 1, 0, 12, 'Ümüt Ünal', 'yngsolarenerji@gmail.com', '$2y$10$kBeDnGUrJyuXJ.o3NFdVhOOQZbB36ikTf7EqcLtWvNbwnjVQTCNiq', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, '2024-09-27 08:59:24'),
	(65, 1, 0, 0, 'gokmen', 'admin@puantor.com.tr', '$2y$10$/LY27auC2qYCf0LN8zBjUe6dUXeMJw3vvxAUVyB.xVBRAaDE9P2Iy', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, '2024-09-29 19:50:08'),
	(66, 1, 0, 14, 'Avni Akgöz', 'avniakgoz01@gmail.com', '$2y$10$A1BmQH024cciv6DGrkMcaewilM06P93Xk4LGJ1LBW2h4ZdCphXLq.', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, '2024-10-01 10:01:56'),
	(67, 1, 0, 8, 'Bilge Kazaz', 'bilgekazaz@hotmail.com', '$2y$10$n62AYwumq05/D5C0BVG1F.Yp/ayJ9rcOj4nXcNKoy4TP2BUP9LWeS', '5079432723', '', NULL, 22, 1, NULL, NULL, NULL, NULL, NULL, '2024-10-10 12:43:34'),
	(70, 1, 0, 8, 'Bilge Kazaz', 'bilgekazaz33@gmail.com', '$2y$10$v6ktysmC4wzfMtgTcSDF5elDW6uH67BdD1Yd8zeuIJoJ/t5rPufa.', '5079432723', '', NULL, 22, 1, NULL, NULL, NULL, NULL, NULL, '2024-10-10 12:44:24'),
	(71, 1, 0, 8, 'Gökmen', 'beyzade5433@hotmail.com', '$2y$10$sVYL8AJGWUP1HBtpjQ0IkuciPdVLt0fph3CQdmNuaKvL9R.rgYMBm', '05079432723', 'Merkez', NULL, 22, 1, NULL, NULL, NULL, NULL, NULL, '2024-10-10 12:46:16'),
	(72, 1, 0, 8, 'Ertan Güneş', 'ertanguness@gmail.com', '$2y$10$tdX6cxjHGRFm9rIbelnqfe/uxD/yuPqZSVJG5ZvYc2zGceBYBjpYm', '', 'Yönetici', NULL, 22, 1, NULL, NULL, NULL, NULL, NULL, '2024-10-10 15:40:47'),
	(73, 1, 0, 15, 'Gökmen', 'beyzade83@hotmail.com', '$2y$10$LCkTYPi9caQi3z93hCyOY.G9Beb7z20JdWMDueqKuxJyVPiWowvvS', '05079432723', '', NULL, 23, 0, NULL, NULL, NULL, NULL, NULL, '2024-10-10 15:59:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
