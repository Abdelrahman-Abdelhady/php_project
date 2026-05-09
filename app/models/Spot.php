<?php

class Spot {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insert a new parking spot into the database
    public function create($data) {

        $query = "INSERT INTO spot 
        (location, zone, price_per_hour, status, ownerID)
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

    // Get all spots that belong to a specific owner
    public function getOwnerSpots($ownerid) {

        $query = "SELECT * FROM spot WHERE ownerID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $ownerid);
        $stmt->execute();

        return $stmt->get_result();
    }

    // Get a single spot by its ID
    public function getById($id) {

        $query = "SELECT * FROM spot WHERE ID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

   

    // Delete a spot from the database
    public function delete($id) {

    $query = "DELETE FROM spot WHERE ID = ?";

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}
}