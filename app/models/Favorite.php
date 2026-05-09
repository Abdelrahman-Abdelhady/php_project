<?php

class Favorite extends Eloquent {

    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addFavorite($userId, $itemId) {
        
    }

    public function removeFavorite($userId, $itemId) {
        
    }
    
    public function getUserFavorites($userId) {
        
    }
}