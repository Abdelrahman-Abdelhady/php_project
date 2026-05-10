<?php

class Spot {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

   
    // CREATE SPOT
   
    public function create($data) {

        $sql = "INSERT INTO spot 
                (location, zone, price_per_hour, status, ownerid)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssdsi",
            $data['location'],
            $data['zone'],
            $data['price_per_hour'],
            $data['status'],
            $data['ownerid']
        );

        return $stmt->execute();
    }

  
    // GET ALL SPOTS FOR OWNER
    
    public function getOwnerSpots($ownerid) {

        $sql = "SELECT * FROM spots WHERE ownerid = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $ownerid);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

  
    // GET SINGLE SPOT
   
    public function getSpotById($id) {

        $sql = "SELECT * FROM spot WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    
    // UPDATE SPOT
   
    public function update($data) {

        $sql = "UPDATE spot 
                SET location = ?, zone = ?, price_per_hour = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ssdi",
            $data['location'],
            $data['zone'],
            $data['price_per_hour'],
            $data['id']
        );

        return $stmt->execute();
    }

    
    // DELETE SPOT
    
    public function delete($id) {

        $sql = "DELETE FROM spot WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

   
    // COUNT SPOTS (for dashboard)
    
    public function countByOwner($ownerid) {

        $sql = "SELECT COUNT(*) as total FROM spots WHERE ownerid = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $ownerid);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }
}