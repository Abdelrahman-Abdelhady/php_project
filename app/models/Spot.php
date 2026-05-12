<?php
require_once __DIR__ . '/../../core/Database.php';

class Spot
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
    {
        $sql = "INSERT INTO spot 
                (ownerid, title, area, location, address, price_per_hour, capacity, status, height_limit, width_limit, ev_charging, cctv, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "issssdisddiis",
            $data['ownerid'],
            $data['title'],
            $data['area'],
            $data['location'],
            $data['address'],
            $data['price_per_hour'],
            $data['capacity'],
            $data['status'],
            $data['height_limit'],
            $data['width_limit'],
            $data['ev_charging'],
            $data['cctv'],
            $data['image']
        );

        return $stmt->execute();
    }

    public function getOwnerSpots($ownerid)
    {
        $sql = "SELECT * FROM spot WHERE ownerid = ? ORDER BY spotID DESC";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $ownerid);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSpotById($spotID)
    {
        $sql = "SELECT * FROM spot WHERE spotID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $spotID);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getActiveSpots()
    {
        $sql = "SELECT s.*, u.name AS owner_name
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

    public function deleteWithOwnerCheck($spotID, $ownerid)
    {
        $sql = "DELETE FROM spot WHERE spotID = ? AND ownerid = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $spotID, $ownerid);

        return $stmt->execute();
    }

    public function countByOwner($ownerid)
    {
        $sql = "SELECT COUNT(*) AS total FROM spot WHERE ownerid = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $ownerid);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return $row['total'] ?? 0;
    }
}