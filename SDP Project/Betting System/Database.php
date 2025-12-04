<?php
class Database {
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private function __construct() {}
    // Prevent cloning

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function connect(): PDO {
        if ($this->connection === null) {
            $this->connection = new PDO("mysql:host=localhost;dbname=BetSYS;charset=utf8", "root", "");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->connection;
    }

    public function disconnect(): void {
        $this->connection = null;
    }
}
?>