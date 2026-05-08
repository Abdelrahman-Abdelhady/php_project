<?php

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
}