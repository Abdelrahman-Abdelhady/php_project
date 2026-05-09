<?php

class Payout {

    private $conn;

    public function __construct($db) {
        // Store database connection
        $this->conn = $db;
    }

    // Create payout request
    public function requestPayout($ownerid, $amount, $method, $account_info) {

        // Insert payout request into database
        $query = "INSERT INTO payouts
        (
            ownerid,
            amount,
            method,
            account_info,
            status,
            created_at
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            'Pending',
            NOW()
        )";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            // Handle SQL preparation error
            return false;
        }

        $stmt->bind_param(
            "idss",
            $ownerid,
            $amount,
            $method,
            $account_info
        );

        // Execute query and return result
        return $stmt->execute();
    }
}