-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Počítač: wm50.wedos.net:3306
-- Vygenerováno: Ned 30. lis 2014, 18:29
-- Verze serveru: 5.6.14
-- Verze PHP: 5.4.23

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `d9350_kivweb`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `vr_firmy`
--

CREATE TABLE IF NOT EXISTS `vr_firmy` (
  `idf` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(35) COLLATE utf8_czech_ci NOT NULL,
  `spz` varchar(10) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`idf`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=5 ;

--
-- Vypisuji data pro tabulku `vr_firmy`
--

INSERT INTO `vr_firmy` (`idf`, `nazev`, `spz`) VALUES
(1, 'Skeleton software s.r.o.', 'PMN 10-15'),
(2, 'Diginex', 'PSE 11-22'),
(3, 'TechDot', 'PLH 99-71'),
(4, '', '4P7 7691');

-- --------------------------------------------------------

--
-- Struktura tabulky `vr_navstevy`
--

CREATE TABLE IF NOT EXISTS `vr_navstevy` (
  `idn` int(11) NOT NULL AUTO_INCREMENT,
  `idf` int(11) DEFAULT NULL,
  `ucel` varchar(200) COLLATE utf8_czech_ci NOT NULL,
  `zakym` int(11) DEFAULT NULL,
  `zadano` datetime NOT NULL,
  `uvolnil` int(11) DEFAULT NULL,
  `uvolneno` datetime DEFAULT NULL,
  `odjezd` datetime DEFAULT NULL,
  PRIMARY KEY (`idn`),
  KEY `fk_vr_navstevy_zamestnanci1_idx` (`zakym`),
  KEY `fk_vr_navstevy_zamestnanci2_idx` (`uvolnil`),
  KEY `fk_vr_navstevy_vr_firmy1_idx` (`idf`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=8 ;

--
-- Vypisuji data pro tabulku `vr_navstevy`
--

INSERT INTO `vr_navstevy` (`idn`, `idf`, `ucel`, `zakym`, `zadano`, `uvolnil`, `uvolneno`, `odjezd`) VALUES
(1, 1, 'Prezentace SW pro workflow', 2, '2014-11-30 14:08:50', 2, '2014-11-30 14:19:09', '2014-11-30 14:27:17'),
(2, 2, 'Montáž wi-fi', 4, '2014-11-30 14:29:10', 3, '2014-11-30 15:31:20', '2014-11-30 15:34:41'),
(3, NULL, 'Pracovní pohovor na pozici mistra', 6, '2014-11-30 14:36:07', 6, '2014-11-30 15:17:43', '2014-11-30 15:25:07'),
(4, 1, 'Prezentace SW', 2, '2014-11-30 14:53:23', 2, '2014-11-30 15:32:34', '2014-11-30 15:35:27'),
(5, 3, 'Montáž rolet', 3, '2014-11-30 14:58:22', 3, '2014-11-30 16:16:44', '2014-11-30 16:23:47'),
(6, 4, 'Odvoz odpadu', 5, '2014-11-30 15:08:29', 1, '2014-11-30 15:29:47', '2014-11-30 15:33:24'),
(7, NULL, 'Nový zaměstnanec', 4, '2014-11-30 15:24:08', NULL, NULL, '2014-11-30 15:34:04');

-- --------------------------------------------------------

--
-- Struktura tabulky `vr_navstevy_osoby`
--

CREATE TABLE IF NOT EXISTS `vr_navstevy_osoby` (
  `ido` int(11) NOT NULL,
  `idn` int(11) NOT NULL,
  PRIMARY KEY (`idn`,`ido`),
  KEY `fk_vr_osoby_has_vr_navstevy_vr_navstevy1_idx` (`idn`),
  KEY `fk_vr_osoby_has_vr_navstevy_vr_osoby1_idx` (`ido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `vr_navstevy_osoby`
--

INSERT INTO `vr_navstevy_osoby` (`ido`, `idn`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 3),
(1, 4),
(2, 4),
(5, 5),
(6, 5),
(7, 5),
(8, 5),
(9, 6),
(10, 7);

-- --------------------------------------------------------

--
-- Struktura tabulky `vr_osoby`
--

CREATE TABLE IF NOT EXISTS `vr_osoby` (
  `ido` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `cop` varchar(20) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`ido`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `vr_osoby`
--

INSERT INTO `vr_osoby` (`ido`, `jmeno`, `prijmeni`, `cop`) VALUES
(1, 'Jan', 'Kohlíček', '123465789'),
(2, 'Václav', 'Haramule', '89765431'),
(3, 'Petr', 'Pešek', '4848789789'),
(4, 'Karel', 'Janoušek', ''),
(5, 'Jiří', 'Homolka', ''),
(6, 'Petr', 'Homolka', '48978971'),
(7, 'Matěj', 'Fantiš', '45645648'),
(8, 'František', 'Mleziva', '97135146'),
(9, 'Jiří', 'Husinec', ''),
(10, 'Ondřej', 'Krátký', '78978978979');

-- --------------------------------------------------------

--
-- Struktura tabulky `vr_poznamky`
--

CREATE TABLE IF NOT EXISTS `vr_poznamky` (
  `idp` int(11) NOT NULL AUTO_INCREMENT,
  `idn` int(11) NOT NULL,
  `text` text COLLATE utf8_czech_ci NOT NULL,
  `zadal` int(11) NOT NULL,
  `zadano` datetime NOT NULL,
  PRIMARY KEY (`idp`),
  KEY `idn_p_idx` (`idn`),
  KEY `fk_vr_poznamky_zamestnanci1_idx` (`zadal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=11 ;

--
-- Vypisuji data pro tabulku `vr_poznamky`
--

INSERT INTO `vr_poznamky` (`idp`, `idn`, `text`, `zadal`, `zadano`) VALUES
(1, 1, 'Přivezli si vlastní projektor', 1, '2014-11-30 14:08:50'),
(2, 1, 'V projektoru jim praskla žárovka, takže jedou pro novou.', 2, '2014-11-30 14:16:08'),
(3, 4, 'Už jsou tu zpátky se žárovkou', 1, '2014-11-30 14:53:23'),
(4, 5, 'Přijeli ve dvou dodávkách s SPZ: PLH 99-71 a ROD 11-47', 1, '2014-11-30 14:58:22'),
(5, 2, 'Přebírám návštěvu pod svůj dozor', 3, '2014-11-30 15:16:52'),
(6, 7, 'První den v práci; návštěva bude v systému ukončena na žádost slečny Kratochvílové', 1, '2014-11-30 15:24:08'),
(7, 2, 'Jedou pro kabeláž', 3, '2014-11-30 15:31:49'),
(8, 7, 'Byla mu vydána zaměstnanecká kartička', 1, '2014-11-30 15:34:21'),
(9, 5, 'F. Mleziva odjíždí v dodávce se starými naloženými roletami.', 3, '2014-11-30 15:38:06'),
(10, 5, 'Dodávka PLH 99-71 s panem Mlezivou odjela - zkontrolováno. Stav ok.', 1, '2014-11-30 15:40:12');

-- --------------------------------------------------------

--
-- Struktura tabulky `zamestnanci`
--

CREATE TABLE IF NOT EXISTS `zamestnanci` (
  `idz` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `prijmeni` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `telefon` smallint(6) NOT NULL,
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `heslo` char(32) COLLATE utf8_czech_ci NOT NULL,
  `admin` enum('0','1') COLLATE utf8_czech_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`idz`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=7 ;

--
-- Vypisuji data pro tabulku `zamestnanci`
--

INSERT INTO `zamestnanci` (`idz`, `jmeno`, `prijmeni`, `telefon`, `email`, `heslo`, `admin`) VALUES
(1, 'Jan', 'Vrátník', 100, 'vratny@dacryo.cz', '0192023a7bbd73250516f069df18b500', '1'),
(2, 'Luboš', 'Hubáček', 123, 'hubacek@dacryo.cz', '6a284155906c26cbca20c53376bc63ac', '0'),
(3, 'Milan', 'Roubal', 201, 'roubal@dacryo.cz', '6a284155906c26cbca20c53376bc63ac', '0'),
(4, 'Kateřina', 'Kratochvílová', 203, 'kratochvilova@dacryo.cz', '6a284155906c26cbca20c53376bc63ac', '0'),
(5, 'Jan', 'Sobek', 204, 'sobek@dacryo.cz', '6a284155906c26cbca20c53376bc63ac', '0'),
(6, 'Luboš', 'Dlouhý', 177, 'dlouhy@dacryo.cz', '6a284155906c26cbca20c53376bc63ac', '0');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `vr_navstevy`
--
ALTER TABLE `vr_navstevy`
  ADD CONSTRAINT `fk_vr_navstevy_zamestnanci1` FOREIGN KEY (`zakym`) REFERENCES `zamestnanci` (`idz`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vr_navstevy_zamestnanci2` FOREIGN KEY (`uvolnil`) REFERENCES `zamestnanci` (`idz`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vr_navstevy_vr_firmy1` FOREIGN KEY (`idf`) REFERENCES `vr_firmy` (`idf`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Omezení pro tabulku `vr_navstevy_osoby`
--
ALTER TABLE `vr_navstevy_osoby`
  ADD CONSTRAINT `fk_vr_osoby_has_vr_navstevy_vr_osoby1` FOREIGN KEY (`ido`) REFERENCES `vr_osoby` (`ido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vr_osoby_has_vr_navstevy_vr_navstevy1` FOREIGN KEY (`idn`) REFERENCES `vr_navstevy` (`idn`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Omezení pro tabulku `vr_poznamky`
--
ALTER TABLE `vr_poznamky`
  ADD CONSTRAINT `idn` FOREIGN KEY (`idn`) REFERENCES `vr_navstevy` (`idn`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vr_poznamky_zamestnanci1` FOREIGN KEY (`zadal`) REFERENCES `zamestnanci` (`idz`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
