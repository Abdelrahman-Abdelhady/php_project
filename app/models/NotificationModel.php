<?php

class NotificationModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Create a new notification
    public function create($userID, $type, $title, $message, $link = null)
    {
        $sql = "INSERT INTO notifications (userID, type, title, message, link, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issss", $userID, $type, $title, $message, $link);
        return $stmt->execute();
    }
    
    // Get all notifications for a user
    public function getUserNotifications($userID, $limit = 20)
    {
        $sql = "SELECT * FROM notifications 
                WHERE userID = ? 
                ORDER BY created_at DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userID, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get unread count for a user
    public function getUnreadCount($userID)
    {
        $sql = "SELECT COUNT(*) as count FROM notifications 
                WHERE userID = ? AND isRead = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }
    
    // Mark notification as read
    public function markAsRead($notificationID, $userID)
    {
        $sql = "UPDATE notifications SET isRead = 1 
                WHERE notificationID = ? AND userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $notificationID, $userID);
        return $stmt->execute();
    }
    
    // Mark all notifications as read for a user
    public function markAllAsRead($userID)
    {
        $sql = "UPDATE notifications SET isRead = 1 WHERE userID = ? AND isRead = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        return $stmt->execute();
    }
    
    // Delete notification
    public function delete($notificationID, $userID)
    {
        $sql = "DELETE FROM notifications WHERE notificationID = ? AND userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $notificationID, $userID);
        return $stmt->execute();
    }
    
    // Send notification to all admins
    public function notifyAllAdmins($type, $title, $message, $link = null)
    {
        $sql = "SELECT userID FROM users WHERE role = 'municipal_admin'";
        $result = $this->db->query($sql);
        $admins = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($admins as $admin) {
            $this->create($admin['userID'], $type, $title, $message, $link);
        }
        return true;
    }
    
    // Send notification to all users
    public function notifyAllUsers($type, $title, $message, $link = null)
    {
        $sql = "SELECT userID FROM users";
        $result = $this->db->query($sql);
        $users = $result->fetch_all(MYSQLI_ASSOC);
        
        foreach ($users as $user) {
            $this->create($user['userID'], $type, $title, $message, $link);
        }
        return true;
    }
}
?>