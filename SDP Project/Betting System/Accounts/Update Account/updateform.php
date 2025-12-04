<?php
include 'head.html';
require_once '../../Database.php';
try { 
    $pdo = Database::getInstance()->connect();

    $sql="CALL selectCustomerByID(:cid)";

    $result = $pdo->prepare($sql);
    $result->bindValue(':cid', $_GET['accountID']); 
    $result->execute();
    if($result->fetchColumn() > 0) 
    {
        $sql = 'CALL selectCustomerByID(:cid)';
        $result = $pdo->prepare($sql);
        $result->bindValue(':cid', $_GET['accountID']); 
        $result->execute();

        $row = $result->fetch();
        $id = $row['accountID'];
	    $Fname= $row['forename'];
	    $Sname=$row['surname'];
	    $DOB= $row['DOB'];
        $phone= $row['phone'];
	    $email=$row['email'];
        $postcode= $row['postcode'];
	    $balance=$row['balance'];  
   
    }

    else {
          print "No rows matched the query. try again click<a href='view all update.php'> here</a> to go back";
         }

    $pdo = Database::getInstance()->disconnect();
}
     
catch (PDOException $e) { 
$output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}

include 'updatedetails.html';
?>
