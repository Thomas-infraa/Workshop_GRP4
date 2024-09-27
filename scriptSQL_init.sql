-- Adminer 4.8.1 MySQL 5.7.11 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `forumbdd`;
CREATE DATABASE `forumbdd` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `forumbdd`;

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `idpost` int(11) NOT NULL AUTO_INCREMENT,
  `namepost` varchar(255) NOT NULL,
  `authorpost` varchar(100) NOT NULL,
  `datepost` datetime NOT NULL,
  PRIMARY KEY (`idpost`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `post` (`idpost`, `namepost`, `authorpost`, `datepost`) VALUES
(5,	'Mon serveur fonctionne plus HELP ??',	'Chloe',	'2024-09-25 16:41:34'),
(6,	'Soucis sur mon serveur Apache !',	'Admin',	'2024-09-26 09:43:04'),
(7,	'Probleme pour coder mon HTML',	'Thomas',	'2024-09-26 15:46:35'),
(8,	'',	'',	'2024-09-27 08:26:58'),
(9,	'Besooin d\'aide',	'Admin',	'2024-09-27 08:39:14');

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE `reponse` (
  `idresponse` int(11) NOT NULL AUTO_INCREMENT,
  `responsecontenet` varchar(2000) NOT NULL,
  `idauthor` varchar(100) NOT NULL,
  `datepost` datetime NOT NULL,
  `idpost` int(11) NOT NULL,
  PRIMARY KEY (`idresponse`),
  KEY `idpost` (`idpost`),
  CONSTRAINT `reponse_ibfk_1` FOREIGN KEY (`idpost`) REFERENCES `post` (`idpost`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `reponse` (`idresponse`, `responsecontenet`, `idauthor`, `datepost`, `idpost`) VALUES
(8,	'Il faudrait que tu nous partage ton code pour que l\'on t\'aide',	'Marie',	'2024-09-27 07:12:33',	7),
(11,	'Essaie de verifier si tu n\'as pas oublier une balise',	'Admin',	'2024-09-26 15:48:12',	7),
(12,	'Peut Ãªtre que tu dois redÃ©marrer !',	'Admin',	'2024-09-26 15:48:31',	5),
(13,	'Essaie de redÃ©marrer ton serveur !',	'Maxence',	'2024-09-27 07:13:45',	6),
(14,	'Ton Apache est bien a jour?',	'Thomas',	'2024-09-27 07:15:07',	6),
(15,	'A quoi servait ton serveur ?',	'Leo',	'2024-09-27 07:16:35',	5),
(16,	'blablablablabla',	'Admin',	'2024-09-27 08:39:02',	7);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `user` (`first_name`, `last_name`, `email`, `password`) VALUES
('Admin',	'Admin',	'admin@ecoles-epsi.net',	'ab4f63f9ac65152575886860dde480a1'),
('Thomas',	'Bremard',	'bremard@ecoles-epsi.net',	'ab4f63f9ac65152575886860dde480a1'),
('Chloe',	'Pleui',	'chloe@ecoles-epsi.net',	'ab4f63f9ac65152575886860dde480a1'),
('Pierre',	'Dupont',	'dupont@ecoles-epsi.net',	'ab4f63f9ac65152575886860dde480a1');

-- 2024-09-27 07:34:10
