-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 25 Mars 2017 à 20:41
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `zone_entrepreneurs`
--
CREATE DATABASE IF NOT EXISTS `zone_entrepreneurs` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `zone_entrepreneurs`;

-- --------------------------------------------------------

--
-- Structure de la table `activation`
--

DROP TABLE IF EXISTS `activation`;
CREATE TABLE IF NOT EXISTS `activation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mode` varchar(150) NOT NULL,
  `activation` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `activation`
--

INSERT INTO `activation` (`id`, `mode`, `activation`) VALUES
(1, 'aucune', 1),
(2, 'mail', 0),
(3, 'manuel', 0);

-- --------------------------------------------------------

--
-- Structure de la table `activation_mail`
--

DROP TABLE IF EXISTS `activation_mail`;
CREATE TABLE IF NOT EXISTS `activation_mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre_pro` int(11) NOT NULL,
  `jeton` varchar(150) NOT NULL,
  `depot` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_membre_pro` (`id_membre_pro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `membres_pro`
--

DROP TABLE IF EXISTS `membres_pro`;
CREATE TABLE IF NOT EXISTS `membres_pro` (
  `id_membre_pro` int(11) NOT NULL AUTO_INCREMENT,
  `civilite` char(2) NOT NULL,
  `nom` text NOT NULL,
  `prenom` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `naissance` text NOT NULL,
  `societe` text NOT NULL,
  `localcommercial` int(11) NOT NULL,
  `adresse` text NOT NULL,
  `codepostal` int(6) NOT NULL,
  `ville` text NOT NULL,
  `pays` text NOT NULL,
  `telephone` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `activation` int(11) NOT NULL DEFAULT '0',
  `date_depot` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_membre_pro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `membres_pro`
--

INSERT INTO `membres_pro` (`id_membre_pro`, `civilite`, `nom`, `prenom`, `email`, `password`, `naissance`, `societe`, `localcommercial`, `adresse`, `codepostal`, `ville`, `pays`, `telephone`, `niveau`, `activation`, `date_depot`) VALUES
(1, 'M', 'test1', 'test1', 'test1@zone.fr', 'ØE%ë™ÕÏ<³ïËáÑLÏ%', '26/07/1986', 'zonee', 1, '98 avenue de la baraudiÃ¨re', 94230, 'cachan', 'france', 154789854, 1, 1, '2017-03-25 19:59:15');

-- --------------------------------------------------------

--
-- Structure de la table `secure`
--

DROP TABLE IF EXISTS `secure`;
CREATE TABLE IF NOT EXISTS `secure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre_pro` int(11) NOT NULL,
  `jeton` varchar(255) NOT NULL,
  `ip_connexion` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_membre_pro` (`id_membre_pro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `secure`
--

INSERT INTO `secure` (`id`, `id_membre_pro`, `jeton`, `ip_connexion`, `date`) VALUES
(4, 1, 'O>\Z›“?S©‚Ý"‡Ç ÜŽ˜', '::1', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
