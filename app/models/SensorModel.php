<?php
require_once __DIR__ . '/../../core/Database.php';

class SensorModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllSensors() {
        $result = $this->db->query(
            "SELECT s.*, sp.location 
             FROM sensors s
             LEFT JOIN spot sp ON s.spotID = sp.spotID"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getInactiveSensors() {
        $result = $this->db->query(
            "SELECT * FROM sensors 
             WHERE last_Heartbeat < DATE_SUB(NOW(), INTERVAL 5 MINUTE) 
             OR last_Heartbeat IS NULL"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function resetSensor($sensorID) {
        $stmt = $this->db->prepare("UPDATE sensors SET isActive = 1, last_Heartbeat = NOW() WHERE sensorID = ?");
        $stmt->bind_param("i", $sensorID);
        return $stmt->execute();
    }
    
    public function getSensorStats() {
        $sensors = $this->getAllSensors();
        $inactive = $this->getInactiveSensors();
        
        $active = 0;
        $online = 0;
        foreach ($sensors as $sensor) {
            if ($sensor['isActive']) $active++;
            if ($sensor['last_Heartbeat'] && strtotime($sensor['last_Heartbeat']) > strtotime('-5 minutes')) $online++;
        }
        
        return [
            'total' => count($sensors),
            'active' => $active,
            'inactive' => count($inactive),
            'online' => $online
        ];
    }
}
?>