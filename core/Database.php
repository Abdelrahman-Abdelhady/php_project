<?php 

class Database
{
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $username = "root";
    private $password = "";
	// Write database name here
    private $dbname = "";


    // Private constructor → prevent direct creation
    private function __construct()
    {
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname
        );

        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }

        // Important for Arabic or UTF8 text
        $this->conn->set_charset("utf8mb4");
    }

    // Singleton static method → returns same instance always
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Getter for mysqli connection
    public function getConnection()
    {
        return $this->conn;
    }
}
