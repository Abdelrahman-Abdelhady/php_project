<?php
require_once "C:/xampp/htdocs/php_project/core/Database.php";
class SpotModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllSpots() {
        $result = $this->db->query("SELECT s.*, u.name as owner_name FROM spot s LEFT JOIN users u ON s.ownerID = u.userID ORDER BY s.spotID DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getTotalSpots() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM spot");
        return $result->fetch_assoc()['total'];
    }
    
    public function getOccupiedSpots() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM spot WHERE status = 'occupied'");
        return $result->fetch_assoc()['total'];
    }
    
    public function getPendingSpots() {
        $result = $this->db->query("SELECT s.*, u.name as owner_name, u.email, u.phone_num FROM spot s JOIN users u ON s.ownerID = u.userID WHERE s.status = 'pending' OR s.status = 'under_review'");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function updateSpotStatus($spotID, $status) {
        $stmt = $this->db->prepare("UPDATE spot SET status = ? WHERE spotID = ?");
        $stmt->bind_param("si", $status, $spotID);
        return $stmt->execute();
    }
    
    public function lockZone($zone) {
        $stmt = $this->db->prepare("UPDATE spot SET status = 'locked' WHERE zone = ?");
        $stmt->bind_param("s", $zone);
        return $stmt->execute();
    }
    
    public function getZones() {
        $result = $this->db->query("SELECT DISTINCT zone FROM spot WHERE zone IS NOT NULL AND zone != ''");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getAvailableSpots() {
        $result = $this->db->query("SELECT s.*, u.name as owner_name FROM spot s LEFT JOIN users u ON s.ownerID = u.userID WHERE s.status = 'available'");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>