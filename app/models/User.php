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
    public function createUser($name, $age, $email, $password, $userType)
    {
        $sql = "INSERT INTO user (name, age, email, password, userType) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sisss", $name, $age, $email, $password, $userType);

        return $stmt->execute();
    }

    /* GET ONE USER */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /* GET ALL user */
    public function getAllusers()
    {
        $sql = "SELECT * FROM user ORDER BY id DESC";
        $result = $this->db->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* UPDATE USER */
    public function updateUser($id, $name, $age, $email, $password, $userType)
    {
        $sql = "UPDATE user 
                SET name = ?, age = ?, email = ?, password = ?, userType = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("sisssi", $name, $age, $email, $password, $userType, $id);
        return $stmt->execute();
    }

    /* DELETE USER */
    public function deleteUser($id)
    {
        $sql = "DELETE FROM user WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
