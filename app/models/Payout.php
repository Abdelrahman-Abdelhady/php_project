<?php

class Payout {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    // CREATE PAYOUT REQUEST
   
    public function requestPayout($ownerid, $amount, $method, $account_info) {

        $sql = "INSERT INTO payouts
                (ownerid, amount, method, account_info, status, created_at)
                VALUES (?, ?, ?, ?, 'Pending', NOW())";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "idss",
            $ownerid,
            $amount,
            $method,
            $account_info
        );

        return $stmt->execute();
    }

  
    // GET OWNER PAYOUTS
   
    public function getOwnerPayouts($ownerid) {

        $sql = "SELECT * FROM payouts 
                WHERE ownerid = ? 
                ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $ownerid);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

  
    // GET TOTAL PENDING PAYOUTS
  
    public function getPendingTotal($ownerid) {

        $sql = "SELECT COALESCE(SUM(amount),0) as total 
                FROM payouts 
                WHERE ownerid = ? AND status = 'Pending'";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $ownerid);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }

    
    // DECREASE WALLET BALANCE
    // (helper used in controller)
    
    public function decreaseBalance($ownerid, $amount) {

        $sql = "UPDATE wallet 
                SET balance = balance - ?, last_updated = NOW() 
                WHERE userID = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("di", $amount, $ownerid);

        return $stmt->execute();
    }

  
    // GET WALLET BALANCE
    
    public function getBalance($ownerid) {

        $sql = "SELECT balance FROM wallet WHERE userID = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $ownerid);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return $row['balance'] ?? 0;
    }
}