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

    public function index() {
        
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $reservations = $this->getReservationHistory();

        require_once __DIR__ . '/../views/users/Driver/History.php';
    }

    public function getReservationHistory(){
        
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $userId = $_SESSION['user_id'];

        $query = "SELECT * FROM reservation WHERE userID = ? ORDER BY reservationID DESC";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        return $stmt->get_result();
    }
}