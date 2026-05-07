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
    public function createUser($name, $email, $age)
    {
        $sql = "INSERT INTO users (name, email, age) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $name, $email, $age);

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

    /* UPDATE USER */
    public function updateUser($id, $name, $email, $age)
    {
        $sql = "UPDATE users 
                SET name = ?, email = ?, age = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $stmt->bind_param("ssii", $name, $email, $age, $id);
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
