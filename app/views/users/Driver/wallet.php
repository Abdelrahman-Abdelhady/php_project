<?php

require_once "../app/core/Database.php";

class Wallet {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getBalance($user_id) {

        $query = "SELECT balance FROM wallets WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([$user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deposit($user_id, $amount) {

        $query = "UPDATE wallets
                  SET balance = balance + ?
                  WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$amount, $user_id]);
    }

    public function withdraw($user_id, $amount) {

        $query = "UPDATE wallets
                  SET balance = balance - ?
                  WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$amount, $user_id]);
    }
}