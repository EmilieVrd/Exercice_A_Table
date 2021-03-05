-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 28 fév. 2021 à 17:56
-- Version du serveur :  8.0.21
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `resto`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `client_id` int NOT NULL,
  `client_lang` varchar(30) NOT NULL,
  `phone_brand` varchar(30) NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`client_id`, `client_lang`, `phone_brand`) VALUES
(1, 'SPA', 'Samsung'),
(2, 'FIN', 'Xiaomi'),
(3, 'FRA', 'Apple'),
(4, 'ENG', 'Huawei'),
(5, 'ENG', 'HTC'),
(6, 'ENG', 'Apple'),
(7, 'ITA', 'Samsung'),
(8, 'CHI', 'Xiaomi'),
(9, 'FRA', 'Wiko'),
(10, 'ITA', 'OnePlus');

-- --------------------------------------------------------

--
-- Structure de la table `qr_codes`
--

DROP TABLE IF EXISTS `qr_codes`;
CREATE TABLE IF NOT EXISTS `qr_codes` (
  `qr_id` int NOT NULL AUTO_INCREMENT,
  `qr_nom` varchar(30) NOT NULL,
  `qr_url` text NOT NULL,
  PRIMARY KEY (`qr_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `qr_codes`
--

INSERT INTO `qr_codes` (`qr_id`, `qr_nom`, `qr_url`) VALUES
(1, 'Alice', 'http://localhost/resto/àlaide.php'),
(2, 'Corinne', ''),
(3, 'Manon', ''),
(4, 'Norah', ''),
(5, 'Audrey', ''),
(6, 'Jaina', '');

-- --------------------------------------------------------

--
-- Structure de la table `tables`
--

DROP TABLE IF EXISTS `tables`;
CREATE TABLE IF NOT EXISTS `tables` (
  `nb_chairs` int DEFAULT NULL,
  `emplacement` varchar(30) NOT NULL,
  `table_qr` int DEFAULT NULL,
  KEY `table_qr` (`table_qr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tables`
--

INSERT INTO `tables` (`nb_chairs`, `emplacement`, `table_qr`) VALUES
(4, 'outside', 1),
(2, 'inside', 2),
(6, 'outside', 3),
(4, 'inside', 4),
(2, 'outside', 5),
(6, 'inside', 6);

-- --------------------------------------------------------

--
-- Structure de la table `visits`
--

DROP TABLE IF EXISTS `visits`;
CREATE TABLE IF NOT EXISTS `visits` (
  `date` timestamp NOT NULL,
  `table_qr` int NOT NULL,
  `client_id` int NOT NULL,
  PRIMARY KEY (`client_id`,`table_qr`) USING BTREE,
  KEY `visits_ibfk_2` (`table_qr`),
  KEY `client_id` (`client_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `visits`
--

INSERT INTO `visits` (`date`, `table_qr`, `client_id`) VALUES
('2021-02-16 17:01:06', 2, 1),
('2021-02-10 17:18:26', 4, 1),
('2021-02-22 11:34:18', 1, 2),
('2020-12-16 11:46:26', 3, 3),
('2021-01-17 19:27:26', 2, 4),
('2021-01-08 17:51:26', 1, 6),
('2021-01-07 11:18:09', 5, 6),
('2020-12-16 17:46:09', 6, 7),
('2021-02-02 11:03:18', 3, 9),
('2021-02-07 18:03:26', 2, 10);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `visits_ibfk_2` FOREIGN KEY (`table_qr`) REFERENCES `qr_codes` (`qr_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
