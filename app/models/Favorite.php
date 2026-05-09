<?php

class Favorite{

    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addFavorite($userId, $spotId) {
        $query = "INSERT INTO favorites (user_id, spot_id) VALUES (?, ?)";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("ii", $userId, $spotId);

        return $stmt->execute();
    }

    public function removeFavorite($userId, $spotId) {
        $query = "DELETE FROM favorites WHERE user_id = ? AND spot_id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("ii", $userId, $spotId);

        return $stmt->execute();
    }
    
    public function getUserFavorites($userId) {
        $query = "SELECT spot_id FROM favorites WHERE user_id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i", $userId);

        $stmt->execute();

        return $stmt->get_result();
    }
}