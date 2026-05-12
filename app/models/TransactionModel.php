<?php
require_once __DIR__ . '/../../core/Database.php';

class TransactionModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Log any transaction (topup / payment / refund)
    public function logTransaction($userID, $type, $amount, $description = '')
    {
        $stmt = $this->db->prepare(
            "INSERT INTO transactions (userID, type, amount, description)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("isds", $userID, $type, $amount, $description);
        return $stmt->execute();
    }

    // Get all transactions for a user, newest first
    public function getTransactionsByUserId($userID)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM transactions WHERE userID = ? ORDER BY created_at DESC"
        );
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>