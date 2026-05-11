<?php
require_once __DIR__ . '/../../core/Database.php';

class AdminModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // ============ USER MANAGEMENT ============
    
    public function getAllUsers()
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users ORDER BY userID DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getUserById($id)
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getUsersByRole($role)
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users WHERE role = ? ORDER BY userID DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
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
    
    public function updateUser($id, $name, $phone_num, $email, $role)
    {
        $sql = "UPDATE users SET name = ?, phone_num = ?, email = ?, role = ? WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $name, $phone_num, $email, $role, $id);
        return $stmt->execute();
    }
    
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
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
    
    public function getTotalUsers()
    {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    public function getDriverCount()
    {
        return $this->countUsersByRole('driver');
    }
    
    public function getOwnerCount()
    {
        return $this->countUsersByRole('space_owner');
    }
    
    // ============ VIOLATIONS & FINES ============
    
    public function getUsersWithViolations()
    {
        $sql = "SELECT u.userID, u.name, u.email, u.phone_num, u.role, u.profile_pic,
                       COUNT(r.reservationID) as violation_count 
                FROM users u 
                JOIN reservation r ON u.userID = r.userID 
                WHERE r.status = 'active' AND r.endTime < DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                GROUP BY u.userID";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getDriversWithUnpaidFines()
    {
        $sql = "SELECT u.userID, u.name, u.email, u.phone_num, u.role, u.profile_pic,
                       COUNT(f.fineID) as unpaid_fines 
                FROM users u 
                LEFT JOIN fines f ON u.userID = (SELECT userID FROM reservation WHERE reservationID = f.reservationID) AND f.status = 'unpaid'
                WHERE u.role = 'driver' 
                GROUP BY u.userID 
                HAVING unpaid_fines > 0";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // ============ BLACKLIST MANAGEMENT ============
    
    public function getBlacklistedUsers()
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users WHERE role = 'blacklisted'";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function blacklistUser($userID)
    {
        $sql = "UPDATE users SET role = 'blacklisted' WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        return $stmt->execute();
    }
    
    public function removeFromBlacklist($userID)
    {
        $sql = "UPDATE users SET role = 'driver' WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        return $stmt->execute();
    }
    
    // ============ SPACE OWNER VERIFICATION ============
    
    public function getPendingSpaceOwners()
    {
        $sql = "SELECT DISTINCT u.userID, u.name, u.email, u.phone_num, u.profile_pic, u.role,
                       'pending' as verification_status
                FROM users u
                JOIN spot s ON u.userID = s.ownerID
                WHERE (s.status = 'pending' OR s.status = 'under_review') AND u.role = 'space_owner'
                GROUP BY u.userID";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function verifyOwner($userID, $status)
    {
        if ($status === 'approved') {
            $sql = "UPDATE spot SET status = 'available' WHERE ownerID = ? AND (status = 'pending' OR status = 'under_review')";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userID);
            return $stmt->execute();
        } elseif ($status === 'rejected') {
            $sql = "UPDATE spot SET status = 'rejected' WHERE ownerID = ? AND (status = 'pending' OR status = 'under_review')";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userID);
            return $stmt->execute();
        }
        return false;
    }
    
    public function getPendingSpots()
    {
        $sql = "SELECT s.*, u.name as owner_name, u.email, u.phone_num 
                FROM spot s 
                JOIN users u ON s.ownerID = u.userID 
                WHERE s.status = 'pending' OR s.status = 'under_review'";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function updateSpotStatus($spotID, $status)
    {
        $sql = "UPDATE spot SET status = ? WHERE spotID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $spotID);
        return $stmt->execute();
    }
}
?>