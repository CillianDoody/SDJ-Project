<?php
include 'addCustomerHeader.html';
require_once '../../Database.php';
require_once '../../Observer/Observer.php';
require_once '../../Observer/UserNotifier.php';
require_once '../../Observer/Notifier.php';

if (isset($_POST['submit'])) {
    
    try {

        $forename = $_POST['forename'];
        $surname = $_POST['surname'];
        $DOB = $_POST['DOB'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $postcode = $_POST['postcode'];

        $pdo = Database::getInstance()->connect();
                
        if ($forename == '' || $surname == '' || $DOB == '' || $phone == '' || $email == '' || $postcode == ''){
        
            echo "Form was not filled out.";
        }

        else{
        
            $sql = "CALL createCustomer(:forename, :surname, :DOB, :phone, :email, :postcode, 'a', 0.00)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':forename', $forename);
            $stmt->bindValue(':surname', $surname);
            $stmt->bindValue(':DOB', $DOB);
            $stmt->bindValue(':phone', $phone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':postcode', $postcode);
            $stmt->execute();
    
            $notifier = new Notifier();
            $userNotifier = new UserNotifier();
            $notifier->attach($userNotifier);
            $notifier->notify("Account for $forename $surname has been successfully created.");
        }

        $pdo = Database::getInstance()->disconnect();
    }
    
    catch (PDOException $e) {
    
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }    
}

include 'addCustomerFoot.html';
?>
