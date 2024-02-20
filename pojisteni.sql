-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 20. úno 2024, 10:14
-- Verze serveru: 10.4.28-MariaDB
-- Verze PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `pojisteni`
--
CREATE DATABASE IF NOT EXISTS `pojisteni` DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `pojisteni`;

-- --------------------------------------------------------

--
-- Struktura tabulky `pojistenci`
--

CREATE TABLE `pojistenci` (
  `pojistenci_id` int(11) NOT NULL,
  `jmeno` varchar(60) NOT NULL,
  `prijmeni` varchar(60) NOT NULL,
  `datum_narozeni` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(9) NOT NULL,
  `adresa` text NOT NULL,
  `mesto` varchar(255) NOT NULL,
  `psc` varchar(5) NOT NULL,
  `url` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `pojistenci`
--

INSERT INTO `pojistenci` (`pojistenci_id`, `jmeno`, `prijmeni`, `datum_narozeni`, `email`, `telefon`, `adresa`, `mesto`, `psc`, `url`) VALUES
(2, 'Shrek', 'Zlobr', '1990-12-17', 'shrek@bazina.com', '985471236', 'Blátivá 25', 'Bažina', '84596', 2),
(3, 'Oslík', 'Otravný', '1992-06-25', 'oslik@ukecany.com', '985471221', 'Blátivá 25', 'Bažina', '84596', 3),
(4, 'Lord', 'Farquaad', '1992-04-15', 'lord@farquaad.com', '985471237', 'Duloc 4', 'Duloc', '84598', 4),
(5, 'Magráta', 'Česneková', '1995-03-18', 'magrata@lancre.com', '985471156', 'Královský hrad', 'Lancre', '84554', 5),
(6, 'Gyta', 'Oggová', '1953-06-23', 'starenka@oggova.com', '985471487', 'U Stařenky Oggové 5', 'Lancre', '84554', 6),
(7, 'Esmeralda', 'Zlopočasná', '1947-01-17', 'babi@zlopocasna.com', '985471111', 'Chaloupka 27', 'Lancre', '84554', 7),
(8, 'Rumcajs', 'Loupežník', '1820-05-31', 'rumcajs@loupeznik.com', '985411235', 'Jeskyně', 'Řáholec', '48651', 8),
(9, 'Rákosníček', 'Skřítek', '1978-02-02', 'rakosnicek@brcalnik.com', '985471112', 'Brčálník 7', 'Pohádkový Les', '84522', 9),
(23, 'Rapunzel', 'Princezna', '1990-06-25', 'rapunzel@vez.com', '985471264', 'Vysoká Věž 89', 'Království Za Sedmero horami', '84510', 23),
(26, 'Frodo', 'Pytlík', '1939-09-02', 'ten@kdoneseprsten.com', '846517985', 'Dno Pytle', 'Hobitín', '24897', 26),
(27, 'Samvěd', 'Křepelka', '1939-07-19', 'samved@udatny.com', '985789541', 'Bagshot Row 3', 'Hobitín', '24897', 27),
(28, 'Sauron', 'Temný', '1900-01-01', 'sauron@temnypan.com', '985471255', 'Barad-dûr', 'Mordor', '24847', 28),
(29, 'Gandalf', 'Bílý', '1900-12-31', 'gandalf@carodej.com', '985471965', 'Kdekoli', 'Kdekoli', '11111', 29),
(30, 'Stromovous', 'Ent', '1899-01-01', 'stromovous@fangorn.com', '985471258', 'Fangornský hvozd', 'Středozem', '54762', 30),
(31, 'Harry', 'Potter', '1980-07-31', 'harry@bradavice.com', '985471299', 'Zobí ulice 4', 'Kvikálkov', '75968', 31),
(32, 'Ron', 'Weasley', '1980-03-01', 'ronald@weasley.com', '985471235', 'Doupě', 'Vydrník sv. Drába', '54798', 32),
(59, 'Darth', 'Vader', '1752-07-05', 'vader@hvezdasmrti.com', '985471227', 'Hvězda Smrti 4', 'Hluboký Vesmír', '63547', 59),
(67, 'Rainbow', 'Dash', '2003-08-17', 'dash@cloudsdale.com', '486165461', 'Na Obláčku 89', 'Cloudsdale', '14527', 67),
(79, 'Modryn', 'Oreyn', '1962-04-18', 'modryn@cechbojovniku.com', '985471230', 'Cech bojovníků', 'Chorrol', '84591', 79),
(80, 'Lucien', 'Lachance', '1954-09-17', 'lucien@cernaruka.com', '146746276', 'Farragut', 'Cheydinhal', '84596', 80),
(81, 'Agronak', 'gro Malog', '1921-06-26', 'sampion@arena.com', '985471244', 'Aréna', 'Imperial City', '78944', 81),
(82, 'Rosentia', 'Gallenus', '1971-09-18', 'rosentia@gallenus.com', '364752476', 'Velký oranžový dům', 'Leyawiin', '84522', 82),
(83, 'Hannibal', 'Traven', '1948-01-28', 'hannibal@arcimag.com', '654968484', 'Magická univerzita', 'Imperial City', '78944', 83),
(90, 'Reynald', 'Jemane', '1974-05-28', 'cislo1@dvojce.com', '985471557', 'Větrov', 'Chorrol', '84591', 90),
(91, 'Gilbert', 'Jemane', '1974-05-28', 'cislo2@dvojce.com', '985471558', 'Větrov', 'Chorrol', '84591', 91),
(92, 'Twilight', 'Sparkle', '2002-02-14', 'pratelstvi@jemagicke.com', '754785785', 'Knihovna Zlatého dubu', 'Ponyville', '14525', 92),
(93, 'Granny', 'Smith', '1900-08-23', 'babi@smithova.com', '561446545', 'Sweet Apple Acress', 'Ponyville', '14525', 93),
(94, 'Sweetie', 'Belle', '2009-09-05', 'sweetiebelle@crusaders.com', '746785785', 'Carousel Boutique', 'Ponyville', '14525', 94),
(95, 'Apple', 'Bloom', '2009-08-17', 'applebloom@crusaders.com', '416514651', 'Sweet Apple Acress', 'Ponyville', '14525', 95),
(99, 'Merlin', 'Mocný', '1541-08-04', 'merlin@kouzelnik.com', '164164169', 'Chaloupka', 'Začarovaný les', '78546', 99),
(132, 'Anduin', 'Lothar', '1981-02-28', 'lothar@aliance.com', '469489486', 'Hrad', 'Stormwind', '32536', 132),
(133, 'Medivh', 'Strážce', '1942-12-01', 'strazce@aliance.com', '145654684', 'Karazhan', 'Deadwind Pass', '54816', 133),
(138, 'Orgrim', 'Doomhammer', '1974-11-29', 'ork@horda.com', '865486541', 'Stan 246', 'Draenor', '98496', 138),
(140, 'Durotan', 'Frostwolf', '1972-04-13', 'nacelnik@horda.com', '958964684', 'Stan 12', 'Draenor', '98496', 140),
(141, 'Grommash', 'Hellscream', '1992-12-25', 'grom@warsong.com', '949198449', 'Stan 89', 'Draenor', '98496', 141),
(158, 'Marty', 'Zebra', '1999-06-23', 'marty@zoo.com', '777777777', '5th Ave', 'New York', '10021', 158),
(164, 'Alex', 'Lev', '2001-02-06', 'alex@zoo.com', '888888888', '5th Ave', 'New York', '10021', 164),
(165, 'Gloria', 'Hrošice', '2000-02-13', 'gloria@zoo.com', '555555555', '5th Ave', 'New York', '10021', 165),
(170, 'Melman', 'Žirafák', '2003-09-18', 'melman@zoo.com', '444444444', '5th Ave', 'New York', '10021', 170);

-- --------------------------------------------------------

--
-- Struktura tabulky `pojisteni`
--

CREATE TABLE `pojisteni` (
  `pojisteni_id` int(11) NOT NULL,
  `typ_pojisteni` varchar(60) NOT NULL,
  `predmet_pojisteni` varchar(60) NOT NULL,
  `od` date NOT NULL DEFAULT current_timestamp(),
  `do` date NOT NULL,
  `castka` int(11) NOT NULL,
  `frekvence_platby` varchar(60) NOT NULL,
  `pojistenec_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `pojisteni`
--

INSERT INTO `pojisteni` (`pojisteni_id`, `typ_pojisteni`, `predmet_pojisteni`, `od`, `do`, `castka`, `frekvence_platby`, `pojistenec_id`) VALUES
(84, 'Úrazové', 'Pojištěnec', '2024-02-05', '0000-00-00', 100, 'Měsíčně', 159),
(86, 'Úrazové', 'Pojištěnec', '2024-02-06', '0000-00-00', 500, 'Měsíčně', 158),
(190, 'Úrazové', 'Pojištěnec', '2024-02-13', '0000-00-00', 10, 'Měsíčně', 164),
(191, 'Úrazové', 'Pojištěnec', '2024-02-15', '0000-00-00', 10, 'Měsíčně', 170),
(192, 'Úrazové', 'Pojištěnec', '2024-02-15', '0000-00-00', 10, 'Měsíčně', 165),
(194, 'Havarijní', 'Vozidlo', '2024-02-16', '0000-00-00', 14, 'Měsíčně', 170);

-- --------------------------------------------------------

--
-- Struktura tabulky `uzivatele`
--

CREATE TABLE `uzivatele` (
  `uzivatele_id` int(11) NOT NULL,
  `uzivatelske_jmeno` varchar(60) NOT NULL,
  `jmeno` varchar(60) NOT NULL,
  `prijmeni` varchar(60) NOT NULL,
  `datum_narozeni` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefon` varchar(9) NOT NULL,
  `adresa` text NOT NULL,
  `mesto` varchar(255) NOT NULL,
  `psc` varchar(5) NOT NULL,
  `heslo` varchar(255) NOT NULL,
  `admin` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `uzivatele`
--

INSERT INTO `uzivatele` (`uzivatele_id`, `uzivatelske_jmeno`, `jmeno`, `prijmeni`, `datum_narozeni`, `email`, `telefon`, `adresa`, `mesto`, `psc`, `heslo`, `admin`) VALUES
(26, 'oslik', 'Rybička', 'Nemo', '2001-01-24', 'nemo@ocean.com', '600500401', 'Wallaby Way 42', 'Sydney', '20900', '$2y$10$72Fw5GdQGwW27PCeonWyXec3lnpWJRNEs8pcgcguPzo6yq.2xnhMq', 0),
(65, 'PinkiePie', 'Pinkie', 'Pie', '1998-11-05', 'dvornikova.gabi@gmail.com', '561261265', 'Sugarcube Corner', 'Ponyville', '14525', '$2y$10$RJbmbOGtIn.JnNVHKlCgPuI8IhqaAsa.3JvyyGNhbbXKyZcFd3xKq', 1);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `pojistenci`
--
ALTER TABLE `pojistenci`
  ADD PRIMARY KEY (`pojistenci_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefon` (`telefon`);

--
-- Indexy pro tabulku `pojisteni`
--
ALTER TABLE `pojisteni`
  ADD PRIMARY KEY (`pojisteni_id`);

--
-- Indexy pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  ADD PRIMARY KEY (`uzivatele_id`),
  ADD UNIQUE KEY `uzivatele_id` (`uzivatele_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefon` (`telefon`),
  ADD UNIQUE KEY `uzivatelske_jmeno` (`uzivatelske_jmeno`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `pojistenci`
--
ALTER TABLE `pojistenci`
  MODIFY `pojistenci_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT pro tabulku `pojisteni`
--
ALTER TABLE `pojisteni`
  MODIFY `pojisteni_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT pro tabulku `uzivatele`
--
ALTER TABLE `uzivatele`
  MODIFY `uzivatele_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
