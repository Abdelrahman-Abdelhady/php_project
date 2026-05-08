<?php

class User
{
    private $db;

    public function __construct()
    {
        // Use the Singleton
        $this->db = Database::getInstance()->getConnection();
    }

    /* CREATE USER */
    public function createUser($name, $phone_num, $email, $password, $role, $photo = null)
    {
        $sql = "INSERT INTO users (name, phone_num, email, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sdsss", $name, $phone_num, $email, $password, $role);

        return $stmt->execute();
    }

    /* GET ONE USER */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /* GET ALL USERS */
    public function getAllUsers()
    {
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $result = $this->db->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* LOGIN SUPPORT */
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);

        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* UPDATE USER */
    public function updateUser($id, $name, $phone_num, $email, $role, $profile_pic = null)
    {
        $sql = "UPDATE users 
                SET name = ?, phone_num = ?, email = ?, role = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("sissi", $name, $phone_num, $email, $role, $id);
        return $stmt->execute();
    }

    /* DELETE USER */
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
