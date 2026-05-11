<?php

require_once __DIR__ . '/../../../core/Database.php';

class Transaction {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createTransaction(
        $wallet_id,
        $amount,
        $type,
        $status
    ) {

        $query = "INSERT INTO transactions
                  (wallet_id, amount, type, status)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $wallet_id,
            $amount,
            $type,
            $status
        ]);
    }

    public function getTransactions($wallet_id) {

        $query = "SELECT * FROM transactions
                  WHERE wallet_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([$wallet_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}