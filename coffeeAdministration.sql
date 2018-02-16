-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 16. Feb 2018 um 16:01
-- Server-Version: 10.1.30-MariaDB
-- PHP-Version: 7.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `coffeeAdministration`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Buchung`
--

CREATE TABLE `Buchung` (
  `ID` int(11) NOT NULL,
  `KonsumentID` int(11) NOT NULL,
  `BuchungsArt` int(20) NOT NULL,
  `Datum` datetime NOT NULL,
  `Betrag` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Buchung`
--

INSERT INTO `Buchung` (`ID`, `KonsumentID`, `BuchungsArt`, `Datum`, `Betrag`) VALUES
(1, 1, 1, '2018-02-13 13:18:34', '9.99'),
(2, 1, 1, '2018-02-14 13:23:21', '9.99'),
(3, 1, 1, '2018-02-14 13:23:26', '9.99'),
(4, 2, 2, '2018-02-14 13:25:26', '9.99'),
(5, 2, 1, '2018-02-14 13:25:44', '9.99'),
(7, 2, 1, '2018-02-14 14:06:37', '1.33'),
(8, 2, 1, '2018-02-14 14:06:42', '4.22'),
(10, 2, 1, '2018-02-14 14:06:54', '5.30'),
(12, 2, 1, '2018-02-14 14:07:05', '2.00'),
(15, 2, 2, '2018-02-15 10:44:00', '5.00'),
(16, 2, 2, '2018-02-15 10:44:11', '5.00'),
(17, 2, 2, '2018-02-15 10:44:32', '5.00'),
(18, 2, 1, '2018-02-15 10:47:53', '9.99'),
(22, 2, 1, '2018-02-15 10:53:50', '1.00'),
(23, 2, 2, '2018-02-15 10:54:00', '2.00'),
(24, 2, 1, '2018-02-15 11:00:38', '1.00'),
(26, 2, 2, '2018-02-15 11:16:37', '4.00'),
(28, 1, 2, '2018-02-15 14:25:45', '9.99'),
(29, 1, 2, '2018-02-15 14:25:57', '1.00'),
(30, 1, 1, '2018-02-15 14:26:08', '1.00'),
(31, 1, 1, '2018-02-15 17:25:57', '9.99'),
(32, 1, 2, '2018-02-16 09:44:36', '9.99'),
(33, 1, 2, '2018-02-16 09:44:47', '0.30'),
(34, 1, 1, '2018-02-16 09:45:06', '2.00'),
(35, 1, 2, '2018-02-16 09:45:20', '2.00'),
(36, 1, 2, '2018-02-16 10:58:38', '1.00'),
(37, 1, 1, '2018-02-16 10:59:10', '2.00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Buchungsart`
--

CREATE TABLE `Buchungsart` (
  `ID` int(11) NOT NULL,
  `Bezeichnung` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Buchungsart`
--

INSERT INTO `Buchungsart` (`ID`, `Bezeichnung`) VALUES
(2, 'Abbuchung'),
(1, 'Ueberweisung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Gesamtguthaben`
--

CREATE TABLE `Gesamtguthaben` (
  `ID` int(11) NOT NULL,
  `Sollguthaben` double NOT NULL,
  `Istguthaben` double NOT NULL,
  `Datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Gesamtguthaben`
--

INSERT INTO `Gesamtguthaben` (`ID`, `Sollguthaben`, `Istguthaben`, `Datum`) VALUES
(1, 20, 12, '2018-02-08 08:00:00'),
(2, 25, 20, '2018-02-09 13:00:00'),
(3, 25, 25, '2018-02-10 11:07:00'),
(4, 30, 14.07, '2018-02-11 01:00:00'),
(10, 32, 14.07, '2018-02-12 19:00:00'),
(11, 25, 34.07, '2018-02-13 06:00:00'),
(13, 35, 34.07, '2018-02-14 10:00:00'),
(14, 35, 34.07, '2018-02-15 00:00:00'),
(15, 30, 14.77, '2018-02-16 10:58:18'),
(16, 25, 14.77, '2018-02-16 10:58:27');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Konsument`
--

CREATE TABLE `Konsument` (
  `ID` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Pin` int(4) NOT NULL,
  `Rolle` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Konsument`
--

INSERT INTO `Konsument` (`ID`, `Name`, `Pin`, `Rolle`) VALUES
(1, 'Lory', 1234, 'Admin'),
(2, 'Sunderland', 1111, 'Konsument');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Rolle`
--

CREATE TABLE `Rolle` (
  `ID` int(11) NOT NULL,
  `Bezeichnung` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `Rolle`
--

INSERT INTO `Rolle` (`ID`, `Bezeichnung`) VALUES
(1, 'Admin'),
(2, 'Konsument');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `Buchung`
--
ALTER TABLE `Buchung`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `BuchungsArt` (`BuchungsArt`);

--
-- Indizes für die Tabelle `Buchungsart`
--
ALTER TABLE `Buchungsart`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Bezeichnung` (`Bezeichnung`);

--
-- Indizes für die Tabelle `Gesamtguthaben`
--
ALTER TABLE `Gesamtguthaben`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `Konsument`
--
ALTER TABLE `Konsument`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Rolle` (`Rolle`);

--
-- Indizes für die Tabelle `Rolle`
--
ALTER TABLE `Rolle`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Bezeichnung` (`Bezeichnung`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `Buchung`
--
ALTER TABLE `Buchung`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT für Tabelle `Buchungsart`
--
ALTER TABLE `Buchungsart`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `Gesamtguthaben`
--
ALTER TABLE `Gesamtguthaben`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT für Tabelle `Konsument`
--
ALTER TABLE `Konsument`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `Rolle`
--
ALTER TABLE `Rolle`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `Buchung`
--
ALTER TABLE `Buchung`
  ADD CONSTRAINT `BuchungsArt` FOREIGN KEY (`BuchungsArt`) REFERENCES `Buchungsart` (`ID`);

--
-- Constraints der Tabelle `Konsument`
--
ALTER TABLE `Konsument`
  ADD CONSTRAINT `Rolle` FOREIGN KEY (`Rolle`) REFERENCES `Rolle` (`Bezeichnung`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
