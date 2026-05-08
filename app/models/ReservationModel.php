<?php

require_once "C:/xampp/htdocs/php_project/core/Database.php";

class ReservationModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getRecentReservations($limit = 10) {
        $result = $this->db->query(
            "SELECT r.*, u.name as user_name, s.location as spot_location 
             FROM reservation r 
             LEFT JOIN users u ON r.userID = u.userID 
             LEFT JOIN spot s ON r.spotID = s.spotID 
             ORDER BY r.reservationID DESC LIMIT $limit"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getOverstayViolations() {
        $result = $this->db->query(
            "SELECT r.*, u.name as user_name, u.phone_num, s.location, s.zone 
             FROM reservation r 
             JOIN users u ON r.userID = u.userID 
             JOIN spot s ON r.spotID = s.spotID 
             WHERE r.status = 'active' 
             AND r.endTime < DATE_SUB(NOW(), INTERVAL 30 MINUTE)"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getActiveReservations() {
        $result = $this->db->query(
            "SELECT r.*, u.name as user_name, u.phone_num, s.location, s.zone 
             FROM reservation r 
             JOIN users u ON r.userID = u.userID 
             JOIN spot s ON r.spotID = s.spotID 
             WHERE r.status = 'active' AND r.endTime > NOW()
             ORDER BY r.startTime ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function overrideReservation($reservationID) {
        // Update reservation status
        $stmt = $this->db->prepare("UPDATE reservation SET status = 'overridden' WHERE reservationID = ?");
        $stmt->bind_param("i", $reservationID);
        $stmt->execute();
        
        // Free the spot
        $stmt2 = $this->db->prepare("UPDATE spot SET status = 'available' WHERE spotID = (SELECT spotID FROM reservation WHERE reservationID = ?)");
        $stmt2->bind_param("i", $reservationID);
        return $stmt2->execute();
    }
    
    public function getViolationsForFines() {
        $result = $this->db->query(
            "SELECT r.*, u.name as user_name, u.email, s.location, s.zone,
                    TIMESTAMPDIFF(MINUTE, r.endTime, NOW()) as overstay_minutes
             FROM reservation r 
             JOIN users u ON r.userID = u.userID 
             JOIN spot s ON r.spotID = s.spotID 
             WHERE r.status = 'active' 
             AND r.endTime < NOW()
             ORDER BY r.endTime ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function generateFine($reservationID, $amount, $reason) {
        $stmt = $this->db->prepare("INSERT INTO fines (reservationID, amount, reason, status, generated_at) VALUES (?, ?, ?, 'unpaid', NOW())");
        $stmt->bind_param("ids", $reservationID, $amount, $reason);
        return $stmt->execute();
    }
        // Get all fines with user and spot details
    public function getAllFines()
    {
        $sql = "SELECT f.*, u.name as user_name, r.startTime, r.endTime, s.location 
                FROM fines f
                JOIN reservation r ON f.reservationID = r.reservationID
                JOIN users u ON r.userID = u.userID
                JOIN spot s ON r.spotID = s.spotID
                ORDER BY f.generated_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>