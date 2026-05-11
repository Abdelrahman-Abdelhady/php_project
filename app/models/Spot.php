<?php
require_once __DIR__ . '/../../core/Database.php';

class Spot
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // إنشاء موقف جديد
    public function create($data)
    {
        $sql = "INSERT INTO spot (spot_name, zone, address, price_per_hour, capacity, status, ownerID) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sssdisi",
            $data['spot_name'],
            $data['zone'],
            $data['address'],
            $data['price_per_hour'],
            $data['capacity'],
            $data['status'],
            $data['ownerID']
        );

        return $stmt->execute();
    }

    // جلب جميع الأماكن لمالك معين
    public function getOwnerSpots($ownerID)
    {
        $sql = "SELECT * FROM spot WHERE ownerID = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("i", $ownerID);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // حذف مكان مع التحقق من المالك
    public function deleteWithOwnerCheck($spotID, $ownerID)
    {
        $sql = "DELETE FROM spot WHERE SpotID = ? AND ownerID = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ii", $spotID, $ownerID);
        return $stmt->execute();
    }

    // عدد الأماكن لمالك معين
    public function countByOwner($ownerID)
    {
        $sql = "SELECT COUNT(*) as total FROM spot WHERE ownerID = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $ownerID);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
?>