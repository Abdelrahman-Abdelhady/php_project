<?php

class EventZoneModel
{
    private $db;
    private $notificationModel;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        $this->notificationModel = new NotificationModel();
    }
    
    // Get all zones
    public function getAllZones()
    {
        $sql = "SELECT * FROM event_zones ORDER BY created_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get active zones
    public function getActiveZones()
    {
        $sql = "SELECT * FROM event_zones WHERE status = 'active'";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get locked zones
    public function getLockedZones()
    {
        $sql = "SELECT * FROM event_zones WHERE status = 'locked' AND expires_at > NOW()";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lock a zone for event
    public function lockZone($zoneName, $eventName, $adminID, $hours = 4)
    {
        // Check if zone exists
        $check = $this->db->prepare("SELECT * FROM event_zones WHERE zone_name = ?");
        $check->bind_param("s", $zoneName);
        $check->execute();
        $result = $check->get_result();
        
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$hours} hours"));
        
        if ($result->num_rows > 0) {
            // Update existing zone
            $sql = "UPDATE event_zones 
                    SET status = 'locked', locked_by = ?, locked_at = NOW(), expires_at = ?, event_name = ?
                    WHERE zone_name = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("isss", $adminID, $expiresAt, $eventName, $zoneName);
        } else {
            // Create new zone
            $sql = "INSERT INTO event_zones (zone_name, event_name, status, locked_by, locked_at, expires_at) 
                    VALUES (?, ?, 'locked', ?, NOW(), ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssis", $zoneName, $eventName, $adminID, $expiresAt);
        }
        
        if ($stmt->execute()) {
            // Lock spots in this zone
            $this->db->query("UPDATE spot SET status = 'locked' WHERE zone = '$zoneName'");
            
            // Notify all users
            $this->notificationModel->notifyAllUsers(
                'event_lock',
                'Event Zone Locked',
                "Zone '{$zoneName}' has been locked for event '{$eventName}' until {$expiresAt}",
                "/php_project/app/views/notifications.php"
            );
            
            // Log admin action
            $this->logAdminAction($adminID, 'lock_zone', "Locked zone: {$zoneName} for event: {$eventName}");
            
            return true;
        }
        return false;
    }
    
    // Unlock a zone
    public function unlockZone($zoneName, $adminID)
    {
        $sql = "UPDATE event_zones SET status = 'expired' WHERE zone_name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $zoneName);
        
        if ($stmt->execute()) {
            // Unlock spots
            $this->db->query("UPDATE spot SET status = 'available' WHERE zone = '$zoneName'");
            
            // Notify all users
            $this->notificationModel->notifyAllUsers(
                'event_lock',
                'Event Zone Unlocked',
                "Zone '{$zoneName}' is now available again",
                "/php_project/app/views/notifications.php"
            );
            
            // Log admin action
            $this->logAdminAction($adminID, 'unlock_zone', "Unlocked zone: {$zoneName}");
            
            return true;
        }
        return false;
    }
    
    // Log admin action
    public function logAdminAction($adminID, $action, $details)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $sql = "INSERT INTO admin_logs (adminID, action, details, ip_address, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isss", $adminID, $action, $details, $ip);
        return $stmt->execute();
    }
    
    // Get admin logs
    public function getAdminLogs($limit = 50)
    {
        $sql = "SELECT l.*, u.name as admin_name 
                FROM admin_logs l
                JOIN users u ON l.adminID = u.userID
                ORDER BY l.created_at DESC
                LIMIT $limit";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get current locked zones with remaining time
    public function getLockedZonesWithTime()
    {
        $sql = "SELECT *, 
                TIMESTAMPDIFF(MINUTE, NOW(), expires_at) as remaining_minutes
                FROM event_zones 
                WHERE status = 'locked' AND expires_at > NOW()";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>