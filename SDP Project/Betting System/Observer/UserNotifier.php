<?php
require_once 'Observer.php';

class UserNotifier implements Observer {
    public function update($message) {
            echo "<script>alert(".json_encode($message).");</script>";
        
    }
}
?>
