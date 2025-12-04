<?php
include 'Place Bet Header.html';
require_once '../../Database.php';

    session_start();
    if (isset($_GET['fixtureID'])) {
        $_SESSION['fixtureID']=$_GET['fixtureID'];
    }
    $fixtureID = $_SESSION['fixtureID'];

    try {  
        $pdo = Database::getInstance()->connect();

        $sql = "CALL PROCEDURE countFixtures()";
        $result = $pdo->prepare($sql);
        $result->execute();
        if($result->fetchColumn() > 0) {
            $sql = "CALL PROCEDURE selectOneFixture($fixtureID)";
            $result = $pdo->prepare($sql);
            $result->execute();

            $row = $result->fetch();
            $id = $row['fixtureID'];
	        $HTeam = $row['fk_HTeam'];
	        $ATeam = $row['fk_ATeam'];
	        $HTeamOdds = $row['OddsHTeam'];
            $ATeamOdds = $row['OddsATeam'];
   
        }
        $pdo = Database::getInstance()->disconnect();
    }
    
    catch (PDOException $e) {
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }

include 'placeBetBody.php';
?>