<?php
include 'head.html';
require_once '../../Database.php';

try { 
    $pdo = Database::getInstance()->connect();
    $sql = $sql = 'CALL countCustomer(:cid)';
    $result = $pdo->prepare($sql);
    $result->bindValue(':cid', $_GET['accountID']); 
    $result->execute();

    if($result->fetchColumn() > 0) 
    {
        $sql = 'CALL selectCustomerByID(:cid)';
        $result = $pdo->prepare($sql);
        $result->bindValue(':cid', $_GET['accountID']); 
        $result->execute();
        
    while ($row = $result->fetch()) { 
        
        echo "Are you sure you want to delete" . $row['forename'] . " " . $row['surname'] . "?" ?>";
    
    <form action="deletecustomer.php" method="post">
            <input type="hidden" name="accountID" value="<?php echo $row['accountID'] ?>"> 
            <input type="submit" value="yes delete" name="delete">
    </form>

    <?php      
        
        
    }
    }
    else {
        print "No rows matched the query.";
    }
    $pdo = Database::getInstance()->disconnect();
} 
catch (PDOException $e) { 
$output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}



?>