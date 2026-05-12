<?php
require_once __DIR__ . '/../../core/Database.php';

class requestModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createRequest($userID, $type, $description)
    {
        $sql  = "INSERT INTO requests (userID, type, description, status, created_at) 
                 VALUES (?, ?, ?, 'Pending', NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $userID, $type, $description);
        return $stmt->execute();
    }

    public function getRequestsByUserId($userID)
    {
        $sql  = "SELECT * FROM requests WHERE userID = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllRequests()
    {
        $sql    = "SELECT r.*, u.name FROM requests r 
                   JOIN users u ON r.userID = u.userID 
                   ORDER BY r.created_at DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $sql  = "UPDATE requests SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}
?>