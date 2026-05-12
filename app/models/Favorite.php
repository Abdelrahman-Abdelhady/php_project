<?php

class Favorite{

    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addFavorite($userId, $spotId) {
        $query = "INSERT INTO favorites (userID, spotID) VALUES (?, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("ii", $userId, $spotId);

        return $stmt->execute();
    }

    public function removeFavorite($userId, $spotId) {
        $query = "DELETE FROM favorites WHERE userID = ? AND spotID = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("ii", $userId, $spotId);

        return $stmt->execute();
    }
    
    public function getUserFavorites($userId) {
        $query = "SELECT spotID FROM favorites WHERE userID = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        return $stmt->get_result();
    }
}

