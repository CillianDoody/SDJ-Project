<?php
include 'Place Bet Header.html';
require_once '../../Database.php';
require_once '../../Observer/Observer.php';
require_once '../../Observer/UserNotifier.php';
require_once '../../Observer/Notifier.php'
    session_start();
    $fixtureID = $_SESSION['fixtureID'];

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
	        $HTeamOdds = $row['OddsHTeam'];
            $ATeamOdds = $row['OddsATeam'];

            $email = $_POST['email'];
            $Team = $_POST['team'];
            $amount = $_POST['amount'];
                    
            if ($email == '' || $Team == '' || $amount == ''){
                echo "Form was not filled out.";
            }

            else{
                if (($Team == $HTeam)) {
                    $Odds = $HTeamOdds;
                }
                else {
                    $Odds = $ATeamOdds;
                }

                $sql="CALL selectCustomerByEmail(:email)";
                $result = $pdo->prepare($sql);
                $result->bindValue(':email', $email);
                $result->execute();
                if ($result->fetchColumn() > 0) {
                    $sql="CALL selectCustomerByEmail(:email)";
                $result = $pdo->prepare($sql);
                $result->bindValue(':email', $email);
                    $result->execute();

                    $row = $result->fetch();
                    $accountID = $row['accountID'];
                    $balance = $row['balance'];

                    if ($amount > $balance) {
                        echo "This amount (", $amount, ") is more then your balance (", $balance,")";
                    }
                    else {
                        $sql = "CALL insertBet(:betAmount, :Odds, 'np', :fk_accountID, :fk_fixtureID, :fk_TeamPicked)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':betAmount', $amount);
                        $stmt->bindValue(':Odds', $Odds);
                        $stmt->bindValue(':fk_accountID', $accountID);
                        $stmt->bindValue(':fk_fixtureID', $fixtureID);
                        $stmt->bindValue(':fk_TeamPicked', $Team);
                        $stmt->execute();
                        
                        
                        if ($Team == $HTeam) {
                            if ($HTeamOdds > 1.01) {
                                $HTeamOdds = ($HTeamOdds - 0.01);
                            }                              
                            $ATeamOdds = ($ATeamOdds + 0.01);  
                            $sql = 'CALL updateOdds(:OddsHTeam, :OddsATeam, :fixtureID)';
                            $stmt = $pdo->prepare($sql);                             
                            $stmt->bindValue(':OddsHTeam', $HTeamOdds);
                            $stmt->bindValue(':OddsATeam', $ATeamOdds);
                            $stmt->bindValue(':fixtureID', $fixtureID);
                            $stmt->execute();  
                        }
                            
                        else {
                            if ($ATeamOdds > 1.01) {
                                $ATeamOdds = ($ATeamOdds - 0.01);
                            }
                            $HTeamOdds = ($HTeamOdds + 0.01);
                            $sql = 'CALL updateOdds(:OddsHTeam, :OddsATeam, :fixtureID)';
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindValue(':OddsHTeam', $HTeamOdds);
                            $stmt->bindValue(':OddsATeam', $ATeamOdds);
                            $stmt->bindValue(':fixtureID', $fixtureID);
                            $stmt->execute();
                        }

                        $balance = ($balance - $amount);    
                        $sql = 'CALL updateBalance(:Balance, :accountID)';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':Balance', $balance);
                        $stmt->bindValue(':accountID', $accountID);
                        $stmt->execute(); 
                        
                        $notifier = new Notifier();
                        $userNotifier = new UserNotifier();
                        $notifier->attach($userNotifier);
                        $notifier->notify("Bet has been placed!");
                        }
                    
                }
                    
                else {
                    $notifier = new Notifier();
                    $userNotifier = new UserNotifier();
                    $notifier->attach($userNotifier);
                    $notifier->notify("That email is not linked to an account!");
                }
            }
            $pdo = Database::getInstance()->disconnect();
        }
        
        catch (PDOException $e) {
        
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }   
    }
?>
 <a href="viewAllPlaceBet.php">Go Back</a>