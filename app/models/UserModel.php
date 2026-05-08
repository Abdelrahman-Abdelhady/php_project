<?php
require_once "C:/xampp/htdocs/php_project/core/Database.php";

class UserModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getUserById($userID)
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return [
                'userID' => $row['userID'],
                'id' => $row['userID'],
                'name' => $row['name'],
                'email' => $row['email'],
                'role' => $row['role'],
                'phone_num' => $row['phone_num'] ?? null,
                'profile_pic' => $row['profile_pic'] ?? null
            ];
        }
        
        return null;
    }
    
    public function findByEmail($email)
    {
        $sql = "SELECT userID, name, email, password, role, phone_num, profile_pic 
                FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return [
                'userID' => $row['userID'],
                'id' => $row['userID'],
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => $row['password'],
                'role' => $row['role'],
                'phone_num' => $row['phone_num'] ?? null,
                'profile_pic' => $row['profile_pic'] ?? null
            ];
        }
        
        return null;
    }
    
    public function emailExists($email)
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
    
    public function create($name, $email, $password, $role, $phone_num = null, $profile_pic = null)
    {
        $sql = "INSERT INTO users (name, email, password, role, phone_num, profile_pic) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $password, $role, $phone_num, $profile_pic);
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        
        return false;
    }
    
    public function getAllUsers()
    {
        $sql = "SELECT userID, name, email, role, phone_num, profile_pic FROM users";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function updateUser($id, $name, $email, $phone_num, $role)
    {
        $sql = "UPDATE users SET name = ?, email = ?, phone_num = ?, role = ? WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone_num, $role, $id);
        return $stmt->execute();
    }
    
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // ============ Admin part(مؤقتاً) =============
    
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
    
    public function getBlacklistedUsers()
    {
        $sql = "SELECT * FROM users WHERE role = 'blacklisted'";
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
    
    public function getPendingSpaceOwners()
    {
        $sql = "SELECT u.*, 'pending' as verification_status 
                FROM users u 
                WHERE u.role = 'space_owner'";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function verifyOwner($userID, $status)
    {
        if ($status === 'approved') {
            $sql = "UPDATE users SET role = 'space_owner_verified' WHERE userID = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userID);
            return $stmt->execute();
        }
        return true;
    }
}
?>