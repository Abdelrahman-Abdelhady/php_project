<?php
require_once __DIR__ . '/../../core/Database.php';

class TransactionModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function logTransaction($userID, $type, $amount, $description = '')
    {
        $sql  = "INSERT INTO transactions (userID, type, amount, description) 
                 VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isds", $userID, $type, $amount, $description);
        return $stmt->execute();
    }

    public function getTransactionsByUserId($userID)
    {
        $sql  = "SELECT * FROM transactions WHERE userID = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>