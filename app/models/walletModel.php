<?php
require_once __DIR__ . '/../../core/Database.php';

class WalletModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
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

    public function getBalance($userID)
    {
        $sql = "SELECT walletID, userID, balance, currency, lastUpdated
                FROM wallet
                WHERE userID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return [
                'balance' => 0,
                'currency' => 'EGP'
            ];
        }

        $stmt->bind_param("i", $userID);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row;
        }

        $this->createWalletForUser($userID);

        return [
            'balance' => 0,
            'currency' => 'EGP'
        ];
    }

    public function deposit($userID, $amount)
    {
        $amount = (float)$amount;

        $this->getBalance($userID);

        $sql = "UPDATE wallet
                SET balance = balance + ?, lastUpdated = NOW()
                WHERE userID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("di", $amount, $userID);

        return $stmt->execute();
    }
}