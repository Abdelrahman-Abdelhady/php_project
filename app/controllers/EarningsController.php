<?php
 
class EarningsController {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

/*
    // Get wallet balance
    public function getBalance($user_id) {

        $query = "SELECT balance FROM wallet WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['balance'] ?? 0;
    }
*/
/*
    // Add earning and update wallet
    public function addEarning($user_id, $amount, $source) {

        // Insert earning history
        $query = "INSERT INTO earnings (ownerid, amount, source)
                  VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ids", $user_id, $amount, $source);
        $stmt->execute();

        // Update wallet balance
        $query2 = "UPDATE wallet 
                   SET balance = balance + ?, 
                       last_updated = NOW()
                   WHERE user_id = ?";

        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bind_param("di", $amount, $user_id);
        $stmt2->execute();
    }

    */
}