<?php
class Spot {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO spot (location, zone, address, price_per_hour, capacity, status, ownerid) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("sssdisi",
            $data['location'],
            $data['zone'],
            $data['address'],
            $data['price_per_hour'],
            $data['capacity'],
            $data['status'],
            $data['ownerid']
        );
        return $stmt->execute();
    }

    public function getOwnerSpots($ownerid) {
        $sql = "SELECT * FROM spot WHERE ownerid = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $ownerid);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteWithOwnerCheck($id, $ownerid) {
        $sql = "DELETE FROM spot WHERE id = ? AND ownerid = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("ii", $id, $ownerid);
        return $stmt->execute();
    }

    public function countByOwner($ownerid) {
        $sql = "SELECT COUNT(*) as total FROM spot WHERE ownerid = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return 0;
        $stmt->bind_param("i", $ownerid);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
?>