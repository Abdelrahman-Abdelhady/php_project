<?php

class Earnings {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

   
    // ADD EARNING
    
    public function addEarning($ownerid, $amount, $source) {

        $sql = "INSERT INTO earnings (ownerid, amount, source)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ids", $ownerid, $amount, $source);

        return $stmt->execute();
    }

   
    // GET TOTAL EARNINGS
  
    public function getTotalEarnings($ownerid) {

        $sql = "SELECT COALESCE(SUM(amount), 0) as total 
                FROM earnings 
                WHERE ownerid = ?";

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

    
    // GET ALL EARNINGS
    
    public function getOwnerEarnings($ownerid) {

        $sql = "SELECT * FROM earnings 
                WHERE ownerid = ? 
                ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $ownerid);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}