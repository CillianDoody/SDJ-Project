<?php
include 'head.html';
require_once '../../Database.php';
require_once '../../Observer/Observer.php';
require_once '../../Observer/UserNotifier.php';
require_once '../../Observer/Notifier.php';
try { 
    $pdo = Database::getInstance()->connect();
    $sql = 'CALL updateCustomer(:cFname, :cSname, :cDOB, :cPhone, :cEmail, :cPostcode, :cBalance, :cid)';
    $result = $pdo->prepare($sql);
    $result->bindValue(':cid', $_POST['ud_accountID']); 
    $result->bindValue(':cFname', $_POST['ud_forename']); 
    $result->bindValue(':cSname', $_POST['ud_surname']); 
    $result->bindValue(':cDOB', $_POST['ud_DOB']); 
    $result->bindValue(':cPhone', $_POST['ud_phone']); 
    $result->bindValue(':cEmail', $_POST['ud_email']); 
    $result->bindValue(':cPostcode', $_POST['ud_postcode']); 
    $result->bindValue(':cBalance', $_POST['ud_balance']); 
    $result->execute();
        
    //For most databases, PDOStatement::rowCount() does not return the number of rows affected by a SELECT statement. id
        
    $count = $result->rowCount();
    if ($count > 0)
    {
        $notifier = new Notifier();
        $userNotifier = new UserNotifier();
        $notifier->attach($userNotifier);
        $notifier->notify("You just updated customer no: " . $_POST['ud_accountID']);
    }
    else
    {
        $notifier = new Notifier();
        $userNotifier = new UserNotifier();
        $notifier->attach($userNotifier);
        $notifier->notify("nothing updated.");
    }
    echo "click<a href='view all update.php'> here</a> to go back.";
    $pdo = Database::getInstance()->disconnect();
}
 
catch (PDOException $e) { 

$output = 'Unable to process query sorry : ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 

}
?>
