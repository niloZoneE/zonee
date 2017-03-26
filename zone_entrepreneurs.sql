-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 26 Mars 2017 à 20:10
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
  `id_membre` int(11) NOT NULL,
  `jeton` varchar(150) NOT NULL,
  `depot` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_membre_pro` (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `infos_administrative`
--

DROP TABLE IF EXISTS `infos_administrative`;
CREATE TABLE IF NOT EXISTS `infos_administrative` (
  `id_membre` int(11) NOT NULL,
  `societe` text NOT NULL,
  `localcommercial` int(11) NOT NULL,
  `date_depot` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `infos_administrative`
--

INSERT INTO `infos_administrative` (`id_membre`, `societe`, `localcommercial`, `date_depot`) VALUES
(2, 'zonee', 0, '2017-03-26 19:59:37');

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

DROP TABLE IF EXISTS `membres`;
CREATE TABLE IF NOT EXISTS `membres` (
  `id_membre` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text CHARACTER SET utf8 NOT NULL,
  `prenom` text CHARACTER SET utf8 NOT NULL,
  `email` text CHARACTER SET utf8 NOT NULL,
  `password` text CHARACTER SET utf8 NOT NULL,
  `adresse` text CHARACTER SET utf8,
  `codepostal` int(6) DEFAULT NULL,
  `ville` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `telephone` int(11) DEFAULT NULL,
  `niveau` int(11) NOT NULL,
  `activation` int(11) NOT NULL DEFAULT '0',
  `url_inscription` text CHARACTER SET utf8,
  `date_depot` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `membres`
--

INSERT INTO `membres` (`id_membre`, `nom`, `prenom`, `email`, `password`, `adresse`, `codepostal`, `ville`, `telephone`, `niveau`, `activation`, `url_inscription`, `date_depot`) VALUES
(2, 'test1', 'test1', 'test1@zone.fr', '@( ãV¬ÉGüÒ¿¿\0ÎñHJ', '163 avenue de l''europe', 92220, 'cachan', 154789854, 1, 1, NULL, '2017-03-26 19:59:37');

-- --------------------------------------------------------

--
-- Structure de la table `secure`
--

DROP TABLE IF EXISTS `secure`;
CREATE TABLE IF NOT EXISTS `secure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  `jeton` varchar(255) NOT NULL,
  `ip_connexion` varchar(50) NOT NULL,
  `date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_membre_pro` (`id_membre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `secure`
--

INSERT INTO `secure` (`id`, `id_membre`, `jeton`, `ip_connexion`, `date`) VALUES
(8, 2, 'Ýæ¿ñK}ûL| JçÝ{–®', '::1', '1490551502');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
