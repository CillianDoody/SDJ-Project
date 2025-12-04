<?php
include 'head.html';
require_once '../../Database.php';
require_once '../../Database.php';
require_once '../../Observer/Observer.php';
require_once '../../Observer/UserNotifier.php';
require_once '../../Observer/Notifier.php';

try { 
     $pdo = Database::getInstance()->connect();
     $sql = 'CALL deleteCustomer(:cid)';
     $result = $pdo->prepare($sql);
     $result->bindValue(':cid', $_POST['accountID']); 
     $result->execute();
          
     $notifier = new Notifier();
     $userNotifier = new UserNotifier();
     $notifier->attach($userNotifier);
     $notifier->notify("You just deleted customer no: " . $_POST['accountID'] .".");

     $pdo = Database::getInstance()->disconnect();
     echo "click<a href='view all delete.php'> here</a> to go back.";
} 
catch (PDOException $e) { 

if ($e->getCode() == 23000) {
          echo "ooops couldnt delete as that record is linked to other tables click<a href='view all update delete.php'> here</a> to go back ";
     }

}
?>
