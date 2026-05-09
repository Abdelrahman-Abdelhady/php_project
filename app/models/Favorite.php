<?php

class Favorite extends Eloquent {

    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addFavorite($userId, $itemId) {
        $query = "INSERT INTO favorites (user_id, item_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId, $itemId]);
    }

    public function removeFavorite($userId, $itemId) {
        
    }
    
    public function getUserFavorites($userId) {
        
    }
}