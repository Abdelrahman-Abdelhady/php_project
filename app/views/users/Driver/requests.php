<?php

require_once __DIR__ . '/../../../core/Database.php';

class Request {

    private $conn;

    public function __construct() {

        $database = new Database();
        $this->conn = $database->connect();
    }

    public function createRequest(
        $user_id,
        $request_type,
        $description
    ) {

        $query = "INSERT INTO requests
                  (user_id, request_type, description)
                  VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $user_id,
            $request_type,
            $description
        ]);
    }

    public function getAllRequests() {

        $query = "SELECT * FROM requests";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}