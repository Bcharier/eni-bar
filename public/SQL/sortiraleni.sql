-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 20 fév. 2024 à 14:41
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sortiraleni`
--

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `administrateur` tinyint(1) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_id` int DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D79F6B11F6BD1646` (`site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `participant`
--

INSERT INTO `participant` (`id`, `roles`, `password`, `nom`, `prenom`, `telephone`, `mail`, `administrateur`, `actif`, `pseudo`, `site_id`, `reset_token`) VALUES
(1, '[]', '$2y$13$Q65.MAq53lklb9F5w0k1celmTJXdKFUYsPs357GCthjOebUfncPrK', 'LaBanane', 'jojo', NULL, 'jojolabanane@banane.org', 0, 1, 'JoLaBa', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `site`
--

DROP TABLE IF EXISTS `site`;
CREATE TABLE IF NOT EXISTS `site` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `site`
--

INSERT INTO `site` (`id`, `nom`) VALUES
(1, 'Saint-Herblain'),
(2, 'Chartres de Bretagne'),
(3, 'La Roche Sur Yon');

-- --------------------------------------------------------

--
-- Structure de la table `sortie`
--

DROP TABLE IF EXISTS `sortie`;
CREATE TABLE IF NOT EXISTS `sortie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_heure_debut` datetime NOT NULL,
  `duree` int NOT NULL,
  `date_limite_inscription` datetime NOT NULL,
  `nb_inscriptions_max` int NOT NULL,
  `infos_sortie` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `etat` int DEFAULT NULL,
  `organisateur_id` int DEFAULT NULL,
  `site_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3C3FD3F2D936B2FA` (`organisateur_id`),
  KEY `IDX_3C3FD3F2F6BD1646` (`site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sortie`
--

INSERT INTO `sortie` (`id`, `nom`, `date_heure_debut`, `duree`, `date_limite_inscription`, `nb_inscriptions_max`, `infos_sortie`, `etat`, `organisateur_id`, `site_id`) VALUES
(1, 'Sortie test', '2024-02-19 15:29:00', 60, '2024-02-19 15:29:00', 10, NULL, NULL, 1, 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `FK_D79F6B11F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);

--
-- Contraintes pour la table `sortie`
--
ALTER TABLE `sortie`
  ADD CONSTRAINT `FK_3C3FD3F2D936B2FA` FOREIGN KEY (`organisateur_id`) REFERENCES `participant` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
