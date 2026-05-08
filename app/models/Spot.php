<?php

class Spot {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {

        $query = "INSERT INTO spot 
        (location, zone, price_per_hour, status, ownerid)
        VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("SQL Error: " . $this->conn->error);
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
}