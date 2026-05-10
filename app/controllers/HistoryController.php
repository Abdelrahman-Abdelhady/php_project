<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../core/Database.php';

class HistoryController {
    
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    public function getReservationHistory(){
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: /php_project/public/login.php");
            exit;
        }

        $userId = $_SESSION['user_id'];

        $query = "SELECT * FROM reservation WHERE userID = ? ORDER BY reservationID DESC";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        return $stmt->get_result();
    }
}