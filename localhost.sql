-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 25. Jan 2015 um 16:14
-- Server Version: 5.5.38
-- PHP-Version: 5.4.4-14+deb7u14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `bepr`
--
CREATE DATABASE `bepr` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bepr`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transmitter` int(11) NOT NULL,
  `receiver` text NOT NULL,
  `message` int(11) NOT NULL,
  `sentdate` text NOT NULL,
  `readdate` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` text NOT NULL COMMENT 'Random',
  `path` text NOT NULL COMMENT 'include/image/user/',
  `user` text NOT NULL COMMENT 'serialized array'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `image`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `music`
--

DROP TABLE IF EXISTS `music`;
CREATE TABLE IF NOT EXISTS `music` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET latin1 NOT NULL,
  `album` text CHARACTER SET latin1 NOT NULL,
  `interpreter` text CHARACTER SET latin1 NOT NULL,
  `path` text CHARACTER SET latin1 NOT NULL,
  `cover` longblob NOT NULL,
  `subtitles` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=374 ;

--
-- Daten für Tabelle `music`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `user`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `video`
--

DROP TABLE IF EXISTS `video`;
CREATE TABLE IF NOT EXISTS `video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8mb4 NOT NULL,
  `year` int(11) NOT NULL,
  `path` text CHARACTER SET utf8mb4 NOT NULL,
  `cover` longblob NOT NULL,
  `series` text NOT NULL,
  `season` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `video`
--

--
-- Datenbank: `centralos`
--
CREATE DATABASE `centralos` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `centralos`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `data`
--

DROP TABLE IF EXISTS `data`;
CREATE TABLE IF NOT EXISTS `data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sensorid` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `station` int(11) NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11679 ;

--
-- Daten für Tabelle `data`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pi`
--

DROP TABLE IF EXISTS `pi`;
CREATE TABLE IF NOT EXISTS `pi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `md5` text NOT NULL,
  `online` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `pi`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sensors`
--

DROP TABLE IF EXISTS `sensors`;
CREATE TABLE IF NOT EXISTS `sensors` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` text NOT NULL,
  `elementtype` text NOT NULL,
  `room` text NOT NULL,
  `pin` int(11) NOT NULL,
  `station` text NOT NULL,
  `loglimit` int(11) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `sensors`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `statusoverview` text NOT NULL,
  `webdav` text NOT NULL COMMENT 'username:password',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Benutzer-centralOS' AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `user`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
