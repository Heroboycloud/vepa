// api/db-pool.php
<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $this->connection = new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );
        
        if ($this->connection->connect_error) {
            throw new Exception("DB Connection failed");
        }
        
        // Set charset
        $this->connection->set_charset("utf8mb4");
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
    
    // Prevent cloning
    private function __clone() {}
    public function __wakeup() {}
}

// Usage in your API:
function getDB() {
    return Database::getInstance();
}
?>
