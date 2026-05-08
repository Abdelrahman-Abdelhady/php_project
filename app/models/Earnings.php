<?php

class Earnings {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // get earnings
    public function getEarnings($ownerid) {

        $query = "SELECT total_earnings FROM owner_earnings WHERE ownerid = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerid);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total_earnings'] ?? 0;
    }
}