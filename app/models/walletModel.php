<?php
require_once __DIR__ . '/../../core/Database.php';

class WalletModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getWalletByUserId($userID)
    {
        $sql  = "SELECT * FROM wallet WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function topUp($userID, $amount)
    {
        $sql  = "UPDATE wallet 
                 SET balance = balance + ?, lastUpdated = NOW() 
                 WHERE userID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("di", $amount, $userID);
        return $stmt->execute();
    }
    public function createWalletForUser($userID)
    {
        $sql = "INSERT INTO wallet (userID, balance, currency, lastUpdated)
                VALUES (?, 0, 'EGP', NOW())";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $userID);

        return $stmt->execute();
    }
}
?>