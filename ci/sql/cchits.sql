-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : db.cchits.net
-- Généré le :  sam. 10 fév. 2018 à 03:26
-- Version du serveur :  5.6.34-log
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `cchits`
--

-- --------------------------------------------------------

--
-- Structure de la table `applications`
--

CREATE TABLE `applications` (
  `intApplicationID` int(11) NOT NULL,
  `intDeveloperID` int(11) NOT NULL,
  `strApplicationName` varchar(254) NOT NULL,
  `strApplicationDescription` text,
  `strApplicationURL` varchar(512) DEFAULT NULL,
  `strApplicationClientID` varchar(8) NOT NULL,
  `strSharedSecret` varchar(36) DEFAULT NULL,
  `strApplicationState` varchar(16) NOT NULL DEFAULT 'live'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `artists`
--

CREATE TABLE `artists` (
  `intArtistID` int(11) NOT NULL,
  `strArtistName` text NOT NULL,
  `strArtistNameSounds` text NOT NULL,
  `strArtistUrl` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `chart`
--

CREATE TABLE `chart` (
  `intChartID` bigint(20) UNSIGNED NOT NULL,
  `datChart` date NOT NULL,
  `intPositionID` int(11) NOT NULL,
  `intTrackID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE `config` (
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `developers`
--

CREATE TABLE `developers` (
  `intDeveloperID` int(11) NOT NULL,
  `strEmail` varchar(255) NOT NULL,
  `strPassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `processing`
--

CREATE TABLE `processing` (
  `intProcessingID` int(11) NOT NULL,
  `strTrackName` text NOT NULL,
  `strTrackNameSounds` text NOT NULL,
  `strTrackUrl` text NOT NULL,
  `enumTrackLicense` enum('cc-by','cc-by-sa','cc-by-nd','cc-by-nc','cc-by-nc-sa','cc-by-nc-nd','cc-0','none specified','cc-nc-sampling+','cc-sampling+','cc-sa','cc-nc','cc-nd','cc-nc-sa','cc-nc-nd') NOT NULL,
  `intArtistID` int(11) NOT NULL,
  `strArtistName` text NOT NULL,
  `strArtistNameSounds` text NOT NULL,
  `strArtistUrl` text NOT NULL,
  `isNSFW` tinyint(1) NOT NULL,
  `fileUrl` text NOT NULL,
  `fileName` text NOT NULL,
  `intUserID` int(11) NOT NULL,
  `fileMD5` varchar(64) NOT NULL,
  `forceMD5Duplicate` tinyint(1) NOT NULL,
  `forceTrackNameDuplicate` tinyint(1) NOT NULL,
  `forceTrackUrlDuplicate` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `redirectmedia`
--

CREATE TABLE `redirectmedia` (
  `localvalue` varchar(255) NOT NULL,
  `remotevalue` text NOT NULL,
  `hitcount` int(255) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `shows`
--

CREATE TABLE `shows` (
  `intShowID` int(11) NOT NULL,
  `intShowUrl` int(11) NOT NULL,
  `enumShowType` enum('daily','weekly','monthly','external','extra') NOT NULL,
  `strShowName` text,
  `strShowUrl` text,
  `intUserID` int(11) NOT NULL,
  `timeLength` time NOT NULL DEFAULT '00:00:00',
  `shaHash` text,
  `strCommentUrl` text,
  `jsonAudioLayout` text,
  `datDateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `showtracks`
--

CREATE TABLE `showtracks` (
  `intShowID` int(11) NOT NULL,
  `intPartID` int(11) NOT NULL,
  `intTrackID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tracks`
--

CREATE TABLE `tracks` (
  `intTrackID` int(11) NOT NULL,
  `intArtistID` int(11) NOT NULL,
  `strTrackName` text NOT NULL,
  `strTrackNameSounds` text NOT NULL,
  `strTrackUrl` text NOT NULL,
  `enumTrackLicense` enum('cc-by','cc-by-sa','cc-by-nd','cc-by-nc','cc-by-nc-sa','cc-by-nc-nd','cc-0','none specified','cc-nc-sampling+','cc-sampling+','cc-sa','cc-nc','cc-nd','cc-nc-sa','cc-nc-nd') NOT NULL DEFAULT 'none specified',
  `isNSFW` tinyint(1) NOT NULL DEFAULT '0',
  `needsReview` tinyint(1) NOT NULL DEFAULT '0',
  `fileSource` text NOT NULL,
  `timeLength` time NOT NULL,
  `md5FileHash` varchar(32) NOT NULL,
  `dtsAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isApproved` tinyint(1) NOT NULL DEFAULT '0',
  `intDuplicateID` int(11) NOT NULL,
  `datDailyShow` int(11) DEFAULT NULL,
  `intChartPlace` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `trends`
--

CREATE TABLE `trends` (
  `intTrendID` bigint(20) NOT NULL,
  `datTrendDay` date NOT NULL,
  `intTrackID` int(11) NOT NULL,
  `intVotes` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `intUserID` int(11) NOT NULL,
  `strOpenID` text,
  `strEMail` text,
  `strCookieID` varchar(255) DEFAULT NULL,
  `sha1Pass` varchar(255) DEFAULT NULL,
  `isAuthorized` tinyint(1) NOT NULL DEFAULT '1',
  `isUploader` tinyint(1) NOT NULL DEFAULT '0',
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `datLastSeen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE `votes` (
  `intVoteID` int(11) NOT NULL,
  `intTrackID` int(11) NOT NULL,
  `intUserID` int(11) NOT NULL,
  `intShowID` int(11) NOT NULL,
  `datTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_firstshowtracks`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `v_firstshowtracks` (
`intTrackID` int(11)
,`intShowID` int(11)
,`intUserID` int(11)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_nonredirectedmedia`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `v_nonredirectedmedia` (
`localvalue` mediumtext
,`remotevalue` mediumtext
,`datDateAdded` timestamp
,`daysSinceAdded` bigint(20)
,`redirectType` varchar(8)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_redirectformats`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `v_redirectformats` (
`format_from` varchar(3)
,`format_to` varchar(3)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_trackcount`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `v_trackcount` (
`intUserID` int(11)
,`strEMail` text
,`intTrackCount` bigint(21)
);

-- --------------------------------------------------------

--
-- Structure de la vue `v_firstshowtracks`
--
DROP TABLE IF EXISTS `v_firstshowtracks`;

CREATE ALGORITHM=UNDEFINED VIEW `v_firstshowtracks`  AS  select `showtracks`.`intTrackID` AS `intTrackID`,min(`showtracks`.`intShowID`) AS `intShowID`,`shows`.`intUserID` AS `intUserID` from ((`showtracks` join `shows`) join `users`) where ((`shows`.`intShowID` = `showtracks`.`intShowID`) and (`users`.`intUserID` = `shows`.`intUserID`)) group by `showtracks`.`intTrackID` ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_nonredirectedmedia`
--
DROP TABLE IF EXISTS `v_nonredirectedmedia`;

CREATE ALGORITHM=UNDEFINED VIEW `v_nonredirectedmedia`  AS  select concat(`s`.`enumShowType`,'/',`s`.`intShowUrl`,'.',convert(`f`.`format_from` using utf8)) AS `localvalue`,concat('https://archive.org/download/cchits_',`s`.`enumShowType`,'_',convert(substr(`s`.`intShowUrl`,1,4) using utf8),convert((case when (`s`.`enumShowType` <> 'monthly') then concat('_',substr(`s`.`intShowUrl`,5,2)) else '' end) using utf8),'/',`s`.`intShowUrl`,'.',convert(`f`.`format_to` using utf8)) AS `remotevalue`,`s`.`datDateAdded` AS `datDateAdded`,(to_days(curdate()) - to_days(`s`.`datDateAdded`)) AS `daysSinceAdded`,`s`.`enumShowType` AS `redirectType` from (`shows` `s` join `v_redirectformats` `f`) where ((`s`.`enumShowType` in ('daily','weekly','monthly')) and (not(concat(`s`.`enumShowType`,'/',`s`.`intShowUrl`,'.',convert(`f`.`format_from` using utf8)) in (select `redirectmedia`.`localvalue` from `redirectmedia`)))) union select concat('track','/',`t`.`fileSource`) AS `localvalue`,concat('https://archive.org/download/cchits_track_',convert(substr(concat(repeat('0',(5 - length(`t`.`intTrackID`))),`t`.`intTrackID`),1,3) using utf8),'00','/',`t`.`fileSource`) AS `remotevalue`,`t`.`dtsAdded` AS `dtsAdded`,(to_days(curdate()) - to_days(`t`.`dtsAdded`)) AS `daysSinceAdded`,'track' AS `redirectType` from `tracks` `t` where (not(concat('track','/',`t`.`fileSource`) in (select `redirectmedia`.`localvalue` from `redirectmedia`))) order by `daysSinceAdded` desc,`localvalue` ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_redirectformats`
--
DROP TABLE IF EXISTS `v_redirectformats`;

CREATE ALGORITHM=UNDEFINED VIEW `v_redirectformats`  AS  select 'mp3' AS `format_from`,'mp3' AS `format_to` union select 'ogg' AS `ogg`,'ogg' AS `ogg` union select 'oga' AS `oga`,'ogg' AS `ogg` union select 'm4a' AS `m4a`,'m4a' AS `m4a` ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_trackcount`
--
DROP TABLE IF EXISTS `v_trackcount`;

CREATE ALGORITHM=UNDEFINED VIEW `v_trackcount`  AS  select `v_firstshowtracks`.`intUserID` AS `intUserID`,`users`.`strEMail` AS `strEMail`,count(1) AS `intTrackCount` from (`v_firstshowtracks` join `users`) where (`users`.`intUserID` = `v_firstshowtracks`.`intUserID`) group by `v_firstshowtracks`.`intUserID` order by `intTrackCount` desc ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`intApplicationID`);

--
-- Index pour la table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`intArtistID`);

--
-- Index pour la table `chart`
--
ALTER TABLE `chart`
  ADD PRIMARY KEY (`intChartID`),
  ADD UNIQUE KEY `UniqueDateAndPosition` (`datChart`,`intPositionID`),
  ADD KEY `datChart` (`datChart`,`intPositionID`,`intTrackID`),
  ADD KEY `chart_int_track_id_idx` (`intTrackID`);

--
-- Index pour la table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `developers`
--
ALTER TABLE `developers`
  ADD PRIMARY KEY (`intDeveloperID`);

--
-- Index pour la table `processing`
--
ALTER TABLE `processing`
  ADD PRIMARY KEY (`intProcessingID`),
  ADD KEY `processing_int_user_id` (`intUserID`);

--
-- Index pour la table `redirectmedia`
--
ALTER TABLE `redirectmedia`
  ADD PRIMARY KEY (`localvalue`);

--
-- Index pour la table `shows`
--
ALTER TABLE `shows`
  ADD PRIMARY KEY (`intShowID`),
  ADD KEY `intUserID` (`intUserID`),
  ADD KEY `datDateAdded` (`datDateAdded`);

--
-- Index pour la table `showtracks`
--
ALTER TABLE `showtracks`
  ADD UNIQUE KEY `intShowID` (`intShowID`,`intPartID`);

--
-- Index pour la table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`intTrackID`);

--
-- Index pour la table `trends`
--
ALTER TABLE `trends`
  ADD PRIMARY KEY (`intTrendID`),
  ADD KEY `trends_int_track_id_idx` (`intTrackID`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`intUserID`),
  ADD KEY `users_str_email_idx` (`strEMail`(64));

--
-- Index pour la table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`intVoteID`),
  ADD UNIQUE KEY `intTrackID` (`intTrackID`,`intUserID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `applications`
--
ALTER TABLE `applications`
  MODIFY `intApplicationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `artists`
--
ALTER TABLE `artists`
  MODIFY `intArtistID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1721;

--
-- AUTO_INCREMENT pour la table `chart`
--
ALTER TABLE `chart`
  MODIFY `intChartID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3909152;

--
-- AUTO_INCREMENT pour la table `developers`
--
ALTER TABLE `developers`
  MODIFY `intDeveloperID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `processing`
--
ALTER TABLE `processing`
  MODIFY `intProcessingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2716;

--
-- AUTO_INCREMENT pour la table `shows`
--
ALTER TABLE `shows`
  MODIFY `intShowID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3860;

--
-- AUTO_INCREMENT pour la table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `intTrackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2862;

--
-- AUTO_INCREMENT pour la table `trends`
--
ALTER TABLE `trends`
  MODIFY `intTrendID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35005;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `intUserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239641;

--
-- AUTO_INCREMENT pour la table `votes`
--
ALTER TABLE `votes`
  MODIFY `intVoteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43865;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
