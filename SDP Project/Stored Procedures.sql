USE BetSYS;

DELIMITER $$
CREATE PROCEDURE createCustomer(IN forename varchar(20), surname varchar(20), DOB date, phone varchar(10), email varchar(40), postcode char(7), status enum('a','c'), balance decimal(7,2))
BEGIN
	INSERT INTO Customers (forename, surname, DOB, phone, email, postcode, status, balance) VALUES(forename, surname, DOB, phone, email, postcode, 'a', 0.00);
END $$

CREATE PROCEDURE selectCustomer(IN cid numeric)
BEGIN
	SELECT count(*) FROM Customers WHERE accountID=cid;
END $$

CREATE PROCEDURE deleteCustomer(IN cid numeric)
BEGIN
	DELETE FROM Customers WHERE accountID = cid;
END $$

CREATE PROCEDURE selectOneFixture(IN FFixtureID smallint(3))
BEGIN
	SELECT * FROM Fixtures where fixtureID = $fixtureID;
END $$

CREATE PROCEDURE selectByDate()
BEGIN
	SELECT * FROM Fixtures ORDER BY Fdate;
END $$

CREATE PROCEDURE selectHTeamEachFixture(IN counter numeric)
BEGIN
	SELECT * FROM teams WHERE name = (SELECT fk_HTeam FROM Fixtures WHERE fixtureID = counter);
END $$

CREATE PROCEDURE selectATeamEachFixture(IN counter numeric)
BEGIN
	SELECT * FROM teams WHERE name = (SELECT fk_ATeam FROM Fixtures WHERE fixtureID = counter);
END $$

CREATE PROCEDURE selectFixturesForBet()
BEGIN
	SELECT * FROM fixtures WHERE OddsHTeam>0 AND OddsATeam>0;
END $$

CREATE PROCEDURE countFixtures()
BEGIN
	SELECT count(*) FROM Fixtures;
END $$

CREATE PROCEDURE updateScore(IN $Score1 numeric, $Score2 numeric, $fixtureID smallint(3))
BEGIN
	UPDATE fixtures SET Score1=$Score1, Score2=$Score2 WHERE fixtureID = $fixtureID;
END $$

CREATE PROCEDURE updateHomeWin(IN $fixtureID smallint(3), $HTeam varchar(30))
BEGIN
	UPDATE bets SET betStatus='w' WHERE fk_fixtureID = $fixtureID AND fk_TeamPicked = $HTeam;
END $$

CREATE PROCEDURE updateAwayLoss(IN $fixtureID smallint(3), $ATeam varchar(30))
BEGIN
	UPDATE bets SET betStatus='l' WHERE fk_fixtureID = $fixtureID  AND fk_TeamPicked = $ATeam;
END $$

CREATE PROCEDURE updateHomeLoss(IN $fixtureID smallint(3), $HTeam varchar(30))
BEGIN
	UPDATE bets SET betStatus='l' WHERE fk_fixtureID = $fixtureID AND fk_TeamPicked = $HTeam;
END $$

CREATE PROCEDURE updateAwayWin(IN $fixtureID smallint(3), $ATeam varchar(30))
BEGIN
	UPDATE bets SET betStatus='w' WHERE fk_fixtureID = $fixtureID  AND fk_TeamPicked = $ATeam;
END $$

CREATE PROCEDURE updateLoss(IN $fixtureID smallint(3))
BEGIN
	UPDATE bets SET betStatus='l' WHERE fk_fixtureID = $fixtureID;
END $$

CREATE PROCEDURE selectWinningBets()
BEGIN
	SELECT fk_accountID, Odds, betAmount FROM bets WHERE betStatus='w';
END $$

CREATE PROCEDURE selectUserBalance(IN $accountID numeric)
BEGIN
	SELECT balance FROM customers WHERE accountID= $accountID;
END $$

CREATE PROCEDURE updateUserBalance(IN $balance decimal(7,2), $accountID numeric)
BEGIN
	UPDATE customers SET balance = $balance WHERE accountID = $accountID;
END $$

CREATE PROCEDURE deleteFinishedBets()
BEGIN
	DELETE FROM Bets WHERE betStatus='w' OR betStatus='l';
END $$

CREATE PROCEDURE deleteFinishedMatches(IN $fixtureID smallint(3))
BEGIN
	DELETE FROM Fixtures WHERE fixtureID = $fixtureID;
END $$

DELIMITER ;