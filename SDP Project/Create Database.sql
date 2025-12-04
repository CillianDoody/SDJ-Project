drop database BetSYS;
CREATE DATABASE BetSYS;
USE BetSYS;

CREATE TABLE `bets` (
  `betID` tinyint(3) NOT NULL AUTO_INCREMENT,
  `betAmount` decimal(5,2) DEFAULT NULL,
  `Odds` decimal(3,2) NOT NULL,
  `betStatus` enum('w','l','np') NOT NULL,
  `fk_accountID` tinyint(3) NOT NULL,
  `fk_fixtureID` smallint(3) NOT NULL,
  `fk_TeamPicked` varchar(30) NOT NULL,
  PRIMARY KEY (`betID`),
  FOREIGN KEY (`fk_fixtureID`) REFERENCES `fixtures` (`fixtureID`),
  FOREIGN KEY (`fk_accountID`) REFERENCES `customers` (`accountID`),
  FOREIGN KEY (`fk_TeamPicked`) REFERENCES `teams` (`name`)
);

CREATE TABLE `customers` (
  `accountID` tinyint(3) NOT NULL AUTO_INCREMENT,
  `forename` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `DOB` date NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `postcode` char(7) NOT NULL,
  `status` enum('a','c') NOT NULL,
  `balance` decimal(7,2) DEFAULT 0.00,
  PRIMARY KEY (`accountID`)
);

CREATE TABLE `fixtures` (
  `fixtureID` smallint(3) NOT NULL AUTO_INCREMENT,
  `fk_HTeam` varchar(30) NOT NULL,
  `fk_ATeam` varchar(30) NOT NULL,
  `OddsHTeam` decimal(3,2) NOT NULL,
  `OddsATeam` decimal(3,2) NOT NULL,
  `Fdate` date NOT NULL,
  `Ftime` time DEFAULT NULL,
  `Score1` tinyint(2) DEFAULT NULL,
  `Score2` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`fixtureID`),
  FOREIGN KEY (`fk_HTeam`) REFERENCES `teams` (`name`),
  FOREIGN KEY (`fk_ATeam`) REFERENCES `teams` (`name`)
);

CREATE TABLE `teams` (
  `name` varchar(30) NOT NULL,
  `manager` varchar(40) NOT NULL,
  `grounds` varchar(40) NOT NULL,
  PRIMARY KEY (`name`)
);

