<?php

class Earnings {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add earning
    public function addEarning($ownerid, $amount, $source) {

        $query = "INSERT INTO earnings (ownerid, amount, source)
                  VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ids", $ownerid, $amount, $source);

        return $stmt->execute();
    }

    // Get total earnings
    public function getTotalEarnings($ownerid) {

        $query = "SELECT SUM(amount) as total FROM earnings WHERE ownerid = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerid);

        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }
}