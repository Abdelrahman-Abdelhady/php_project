<?php
require_once __DIR__ . '/../../core/Database.php';

class WalletModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Get wallet row for a user (creates one if missing)
    public function getWallet($userID)
    {
        $stmt = $this->db->prepare("SELECT * FROM wallet WHERE userID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            $this->createWallet($userID);
            return ['userID' => $userID, 'balance' => 0.00];
        }

        return $result;
    }

    // Create a wallet row for a new user
    public function createWallet($userID)
    {
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO wallet (userID, balance, currency, lastUpdated)
             VALUES (?, 0.00, 'EGP', NOW())"
        );
        $stmt->bind_param("i", $userID);
        $stmt->execute();
    }

    // Get balance only
    public function getBalance($userID)
    {
        $wallet = $this->getWallet($userID);
        return $wallet['balance'] ?? 0.00;
    }

    // Add funds to wallet
    public function addFunds($userID, $amount)
    {
        $stmt = $this->db->prepare(
            "UPDATE wallet SET balance = balance + ?, lastUpdated = NOW() WHERE userID = ?"
        );
        $stmt->bind_param("di", $amount, $userID);
        return $stmt->execute();
    }

    // Deduct funds from wallet (returns false if insufficient balance)
    public function deductFunds($userID, $amount)
    {
        $balance = $this->getBalance($userID);
        if ($balance < $amount) {
            return false;
        }
        $stmt = $this->db->prepare(
            "UPDATE wallet SET balance = balance - ?, lastUpdated = NOW() WHERE userID = ?"
        );
        $stmt->bind_param("di", $amount, $userID);
        return $stmt->execute();
    }
}
?>