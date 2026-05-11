<?php
require_once __DIR__ . '/../../core/Database.php';

class AdminModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ============ USER MANAGEMENT (Admin Functions) ============
    
    /**
     * Get all users
     */
    public function getAllUsers()
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users ORDER BY userID DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users WHERE role = ? ORDER BY userID DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Count users by role
     */
    public function countUsersByRole($role)
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Update user information
     */
    public function updateUser($id, $name, $phone_num, $email, $role)
    {
        $sql = "UPDATE users SET name = ?, phone_num = ?, email = ?, role = ? WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $name, $phone_num, $email, $role, $id);
        return $stmt->execute();
    }
    
    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Search users by name or email
     */
    public function searchUsers($keyword)
    {
        $searchTerm = "%{$keyword}%";
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic 
                FROM users 
                WHERE name LIKE ? OR email LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // ============ STATISTICS ============
    
    /**
     * Get total number of users
     */
    public function getTotalUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    /**
     * Get number of drivers
     */
    public function getDriverCount()
    {
        return $this->countUsersByRole('driver');
    }
    
    /**
     * Get number of space owners
     */
    public function getOwnerCount()
    {
        return $this->countUsersByRole('space_owner');
    }
    
    // ============ VIOLATIONS & FINES (Admin Functions) ============
    
    /**
     * Get users with violations (overstay > 30 minutes)
     */
    public function getUsersWithViolations()
    {
        $sql = "SELECT u.*, COUNT(r.reservationID) as violation_count 
                FROM users u 
                JOIN reservation r ON u.userID = r.userID 
                WHERE r.status = 'active' AND r.endTime < DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                GROUP BY u.userID";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get drivers with unpaid fines
     */
    public function getDriversWithUnpaidFines()
    {
        $sql = "SELECT u.*, COUNT(f.fineID) as unpaid_fines 
                FROM users u 
                LEFT JOIN fines f ON u.userID = (SELECT userID FROM reservation WHERE reservationID = f.reservationID) AND f.status = 'unpaid'
                WHERE u.role = 'driver' 
                GROUP BY u.userID 
                HAVING unpaid_fines > 0";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>