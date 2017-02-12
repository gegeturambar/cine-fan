-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Dim 12 Février 2017 à 16:17
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `cine-fan`
--
CREATE DATABASE IF NOT EXISTS `cine-fan` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cine-fan`;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `name`, `slug`) VALUES
(1, 'Action', 'action'),
(4, 'Comédie', 'comedie'),
(5, 'Far-west', 'farwest'),
(12, 'Drame', 'drame');

-- --------------------------------------------------------

--
-- Structure de la table `compteur`
--

CREATE TABLE `compteur` (
  `id` int(11) NOT NULL,
  `compteur` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `compteur`
--

INSERT INTO `compteur` (`id`, `compteur`) VALUES
(1, 11);

-- --------------------------------------------------------

--
-- Structure de la table `movie`
--

CREATE TABLE `movie` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `release_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `movie`
--

INSERT INTO `movie` (`id`, `category_id`, `title`, `slug`, `description`, `picture`, `release_date`) VALUES
(1, 1, 'Ben Hur', 'ben-hur', 'Film douze fois oscarisé', 'c59ef8e34c7a9d4d84e2c6069161e3a0.jpg', '1959-04-15'),
(2, 1, 'Saw', 'saw', 'genial', '39c7c061a320cdfb75cb5aeee1a1dc1a.jpg', '2004-01-01'),
(4, 1, 'Histoire vraie', 'histoire-vraie', 'L\'histoire d\'un vieux qui fait 400 kilomètres sur sa tondeuse à gazon pour retrouver son frère...', '81bd8c03b46a10364925fe7c1110c555.jpg', '1999-01-01'),
(5, 5, 'Mud', 'mud', 'un bateau retrouvé dans les arbres qui semble habité par un inconnu...', 'fde024cf2d77a8620c1ead96993a72e1.jpg', '2012-01-01'),
(7, 1, 'Lost in translation', 'lost-in-translation', 'Bill Murray for ever !', 'c242b5b61a81cef2c7069d07549aefbe.jpg', '2003-01-01'),
(8, 1, 'The pledge', 'the-pledge', 'Nickolson au top !', '6f7e5b921c48e1f61206af0f78d82405.jpg', '2001-01-01'),
(9, 1, 'Little miss sunshine', 'little-miss-sunshine', 'Film géant', 'def37c01fb1299d6e8f2ae8c55544700.jpg', '2006-01-01'),
(10, 4, 'Les lumières de la ville', 'les-lumieres-de-la-ville', 'Un des meilleurs Charlot', 'dd9e8f2e9a88ab99d98db3af7773be75.jpg', '1935-01-01'),
(11, 12, 'Le professionnel', 'le-professionnel', 'Belmondo \\o/', '4dd763a8b2877dfd1059cf079846b994.jpg', '1981-08-15'),
(12, 4, 'Le magnifique', 'le-magnifique', 'le meilleur des Belmondo, avec l\'infâme Karpoff !', '7dafb825641e9d8f63f0720f5bd28336.jpg', '1983-01-01'),
(14, 4, 'Retour vers le futur', 'retour-vers-le-futur', 'Un classique indémodable', '5bea451de7c046477b6f417b989f8a1b.jpg', '1985-10-14');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_64C19C1989D9B62` (`slug`),
  ADD KEY `cle_index_sur_titre_film` (`name`);

--
-- Index pour la table `compteur`
--
ALTER TABLE `compteur`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1D5EF26F989D9B62` (`slug`),
  ADD UNIQUE KEY `UNIQ_1D5EF26F2B36786BE769876D` (`title`,`release_date`),
  ADD KEY `IDX_1D5EF26F12469DE2` (`category_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `compteur`
--
ALTER TABLE `compteur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `movie`
--
ALTER TABLE `movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `movie`
--
ALTER TABLE `movie`
  ADD CONSTRAINT `FK_1D5EF26F12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
