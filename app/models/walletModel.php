<?php

class walletModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    }

    public function getBalance($user_id) {
        // userID and balance match your database exactly
        $this->db->query("SELECT balance FROM wallet WHERE userID = 1");
        $this->db->bind(':id', $user_id);
        
        return $this->db->single();
    }

    public function deposit($user_id, $amount) {
        $amount = (float)$amount;

        // The exact SQL that worked in your phpMyAdmin
        $this->db->query("UPDATE wallet SET balance = balance + :amount WHERE userID = :id");
        
        $this->db->bind(':amount', $amount);
        $this->db->bind(':id', $user_id);
        
        return $this->db->execute();
    }
}