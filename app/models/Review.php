<?php

class Review {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // =========================
    // GET REVIEWS FOR OWNER SPOTS
    // =========================
    public function getOwnerReviews($owner_id) {

        $sql = "SELECT 
                    r.id,
                    r.rating,
                    r.comment,
                    r.created_at,
                    u.name AS user_name,
                    s.spot_name AS spot_location
                FROM review r
                JOIN user u ON r.userID= u.ID
                JOIN spot s ON r.spotID= s.ID
                WHERE s.ownerID = ?
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $owner_id);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // =========================
    // GET AVERAGE RATING (OWNER)
    // =========================
    public function getOwnerAverageRating($owner_id) {

        $sql = "SELECT COALESCE(AVG(r.rating),0) AS avg_rating
                FROM review r
                JOIN spot s ON r.spotID= s.spotID
                WHERE s.ownerID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $owner_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return $row['avg_rating'] ?? 0;
    }

    // =========================
    // COUNT REVIEWS (OWNER)
    // =========================
    public function countOwnerReviews($owner_id) {

        $sql = "SELECT COUNT(*) AS total
                FROM review r
                JOIN spot s ON r.spotID = s.spotID
                WHERE s.ownerID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $owner_id);

        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }
}