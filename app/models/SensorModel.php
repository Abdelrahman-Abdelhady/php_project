<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/core/Database.php';
class SensorModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllSensors() {
        $result = $this->db->query(
            "SELECT i.*, s.location, s.zone 
             FROM iot_sensor i 
             LEFT JOIN spot s ON i.spotID = s.spotID"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getInactiveSensors() {
        $result = $this->db->query(
            "SELECT * FROM iot_sensor 
             WHERE last_Heartbeat < DATE_SUB(NOW(), INTERVAL 5 MINUTE) 
             OR last_Heartbeat IS NULL"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function resetSensor($sensorID) {
        $stmt = $this->db->prepare("UPDATE iot_sensor SET isActive = 1, last_Heartbeat = NOW() WHERE sensorID = ?");
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