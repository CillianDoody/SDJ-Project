<?php
include 'Enter Result Head.html';
require_once '../../Database.php';

    session_start();
    if (isset($_GET['fixtureID'])) {
        $_SESSION['fixtureID']=$_GET['fixtureID'];
    }
    $fixtureID = $_SESSION['fixtureID'];

    try {  
        $pdo = Database::getInstance()->connect();

        $sql = "CALL selectOneFixture(:id)";
        $result = $pdo->prepare($sql);
        $result->bindValue(':id', $fixtureID);
        $result->execute();
        if($result->fetchColumn() > 0) {
            $sql = "CALL selectOneFixture(:id)";
            $result = $pdo->prepare($sql);
            $result->bindValue(':id', $fixtureID);
            $result->execute();

            $row = $result->fetch();
            $id = $row['fixtureID'];
	        $HTeam = $row['fk_HTeam'];
	        $ATeam = $row['fk_ATeam'];
	        $Score1 = $row['Score1'];
            $Score2 = $row['Score2'];
   
        }
        $pdo = Database::getInstance()->disconnect();
    }
    
    catch (PDOException $e) {
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }

include 'Enter Result Body.php';
?>