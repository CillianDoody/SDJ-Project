<?php
include 'Enter Result Head.html';
require_once '../../Database.php';

    session_start();
    $fixtureID = $_SESSION['fixtureID'];
    $updateScore = "CALL updateScore(:score1, :score2, :id)"

    if (isset($_POST['submit'])) {
        try {
            $pdo = Database::getInstance()->connect();

            $sql = "CALL selectOneFixture(:id)";
            $result = $pdo->prepare($sql);
            $result->bindValue(':id', $fixtureID);
            $result->execute();

            $row = $result->fetch();
            $id = $row['fixtureID'];
	        $HTeam = $row['fk_HTeam'];
	        $ATeam = $row['fk_ATeam'];

            $Score1 = $_POST['Score1'];
            $Score2 = $_POST['Score2'];
                    
            if ($Score1 == '' || $Score2 == '') { 
                echo"Form was not filled out.";
            }

            else{
                if ($Score1 > $Score2) {
                    $sql = $updateScore;
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':score1', $Score1);
                    $stmt->bindValue(':score2', $Score2);
                    $stmt->bindValue(':id', $fixtureID);
                    $stmt->execute();
                    
                    $sql = "CALL updateHomeWin(:id, :team)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $fixtureID);
                    $stmt->bindValue(':team', $HTeam);
                    $stmt->execute();

                    $sql = "CALL updateAwayLoss(:id, :team)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $fixtureID);
                    $stmt->bindValue(':team', $ATeam);
                    $stmt->execute();

                    $sql = "CALL selectWinningBets()";
                    $result = $pdo->prepare($sql);
                    $result->execute();

                    while ($row = $result->fetch()) {
                        $accountID = $row['fk_accountID'];
                        $Odds = $row['Odds'];
                        $amount = $row['betAmount'];

                        $sql = "CALL selectUserBalance(:id)";
                        $result1 = $pdo->prepare($sql);
                        $result1->bindValue(':id', $accountID);
                        $result1->execute();
                        $row1 = $result1->fetch();

                        $currentBalance = $row1['balance'];
                        $balance = ($amount*$Odds)+$currentBalance;

                        $sql = "CALL updateUserBalance(:balance, :id)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':balance', $balance);
                        $stmt->bindValue(':id', $accountID);
                        $stmt->execute();
                    }
                }

                else if ($Score2 > $Score1) {
                    $sql = $updateScore;
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':score1', $Score1);
                    $stmt->bindValue(':score2', $Score2);
                    $stmt->bindValue(':id', $fixtureID);
                    $stmt->execute();
                    
                    $sql = "CALL updateAwayWin(:id, :team)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $fixtureID);
                    $stmt->bindValue(':team', $ATeam);
                    $stmt->execute();
                    
                    $sql = "CALL updateHomeLoss(:id, :team)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $fixtureID);
                    $stmt->bindValue(':team', $HTeam);
                    $stmt->execute();

                    $sql = "CALL selectWinningBets()";
                    $result = $pdo->prepare($sql);
                    $result->execute();

                    while ($row = $result->fetch()) {
                        $accountID = $row['fk_accountID'];
                        $Odds = $row['Odds'];
                        $amount = $row['betAmount'];

                        $sql = "CALL selectUserBalance(:id)";
                        $result1 = $pdo->prepare($sql);
                        $result1->bindValue(':id', $accountID);
                        $result1->execute();
                        $row1 = $result1->fetch();

                        $currentBalance = $row1['balance'];
                        $balance = ($amount*$Odds)+$currentBalance;

                        $sql = "CALL updateUserBalance(:balance, :id)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':balance', $balance);
                        $stmt->bindValue(':id', $accountID);
                        $stmt->execute();
                    }   
                }

                else {
                    $sql = $updateScore;
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':score1', $Score1);
                    $stmt->bindValue(':score2', $Score2);
                    $stmt->bindValue(':id', $fixtureID);
                    $stmt->execute();
                    
                    $sql = "CALL updateLoss(:id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $fixtureID );
                    $stmt->execute();
                }

                $sql = "CALL deleteFinishedBets()";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                $sql = "CALL deleteFinishedMatches(:id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', $fixtureID );
                $stmt->execute();
            }
            $pdo = Database::getInstance()->disconnect();
        }
        
        catch (PDOException $e) {
        
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }   
    }
?>
 <p>The result has been entered <a href="View All Enter Result.php">Go Back</a></p>