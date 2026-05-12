<?php
require_once __DIR__ . '/../../core/Database.php';

class SpotModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllSpots()
    {
        $sql = "SELECT 
                    s.*, 
                    u.name AS owner_name
                FROM spot s
                LEFT JOIN users u ON s.ownerid = u.userID
                ORDER BY s.spotID DESC";

        $result = $this->db->query($sql);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalSpots()
    {
        $sql = "SELECT COUNT(*) AS total FROM spot";

        $result = $this->db->query($sql);

        if (!$result) {
            return 0;
        }

        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }

    public function getOccupiedSpots()
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM spot 
                WHERE status = 'occupied'";

        $result = $this->db->query($sql);

        if (!$result) {
            return 0;
        }

        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }

    public function getAvailableSpots()
    {
        $sql = "SELECT 
                    s.*, 
                    u.name AS owner_name
                FROM spot s
                LEFT JOIN users u ON s.ownerid = u.userID
                WHERE s.status = 'available'
                ORDER BY s.spotID DESC";

        $result = $this->db->query($sql);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPendingSpots()
    {
        $sql = "SELECT 
                    s.*,
                    u.name AS owner_name,
                    u.email,
                    u.phone_num
                FROM spot s
                JOIN users u ON s.ownerid = u.userID
                WHERE s.status = 'pending'
                   OR s.status = 'under_review'
                ORDER BY s.created_at DESC";

        $result = $this->db->query($sql);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateSpotStatus($spotID, $status)
    {
        $allowedStatuses = [
            'available',
            'rejected',
            'pending',
            'under_review',
            'occupied',
            'locked'
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            return false;
        }

        $sql = "UPDATE spot 
                SET status = ? 
                WHERE spotID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("si", $status, $spotID);

        return $stmt->execute();
    }

    public function lockArea($area)
    {
        $sql = "UPDATE spot 
                SET status = 'locked' 
                WHERE area = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $area);

        return $stmt->execute();
    }

    public function getAreas()
    {
        $sql = "SELECT DISTINCT area 
                FROM spot 
                WHERE area IS NOT NULL 
                  AND area != ''";

        $result = $this->db->query($sql);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}