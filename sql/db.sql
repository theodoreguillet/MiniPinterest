-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Lien vers l'index: https://bdw1.univ-lyon1.fr/p1718423/
-- username: p1808309
-- password: 522672
--
-- Base de données :  `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `rights` varchar(255) NOT NULL DEFAULT 'USER',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

--
-- Structure de la table `Theme`
--

CREATE TABLE IF NOT EXISTS `Theme` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `userId` INT NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`userId`) REFERENCES User(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

--
-- Structure de la table `Picture`
--

CREATE TABLE IF NOT EXISTS `Picture` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `themeId` INT NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`themeId`) REFERENCES Theme(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO User (`email`,`pwd`,`pseudo`,`rights`) VALUES ('admin@default.com','21232f297a57a5a743894a0e4a801fc3','Admin','ADMIN'); -- pwd: 'admin'
