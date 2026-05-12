<?php

require_once __DIR__ . '/../../core/Database.php';

class Review {

    private $db;

    public function __construct($database = null) {
        if ($database !== null) {
            $this->db = $database;
        } else {
            $this->db = Database::getInstance()->getConnection();
        }
    }

    // =========================
    // ADD REVIEW - DRIVER
    // =========================
    public function addReview($userId, $spotId, $rating, $comment) {
        $query = "INSERT INTO review (userID, spotID, rating, comment, date)
                  VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("iiis", $userId, $spotId, $rating, $comment);

        return $stmt->execute();
    }

    // =========================
    // GET REVIEWS FOR ONE SPOT
    // =========================
    public function getSpotReviews($spotId) {
        $query = "SELECT * FROM review 
                  WHERE spotID = ? 
                  ORDER BY reviewID DESC";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $spotId);

        $stmt->execute();

        return $stmt->get_result();
    }

    // =========================
    // GET ALL REVIEWS
    // =========================
    public function getAllReviews() {
        $query = "SELECT * FROM review 
                  ORDER BY reviewID DESC";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        $stmt->execute();

        return $stmt->get_result();
    }

    // =========================
    // DELETE REVIEW - DRIVER
    // =========================
    public function deleteReview($reviewId, $userId) {
        $query = "DELETE FROM review 
                  WHERE reviewID = ? AND userID = ?";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $reviewId, $userId);

        return $stmt->execute();
    }

    // =========================
    // GET REVIEWS FOR OWNER SPOTS
    // =========================
    public function getOwnerReviews($ownerId) {
        $query = "SELECT 
                    r.reviewID,
                    r.rating,
                    r.comment,
                    r.date,
                    r.userID,
                    r.spotID,
                    s.location AS spot_location
                  FROM review r
                  JOIN spot s ON r.spotID = s.spotID
                  WHERE s.ownerID = ?
                  ORDER BY r.date DESC";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $ownerId);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // =========================
    // GET AVERAGE RATING FOR OWNER
    // =========================
    public function getOwnerAverageRating($ownerId) {
        $query = "SELECT COALESCE(AVG(r.rating), 0) AS avg_rating
                  FROM review r
                  JOIN spot s ON r.spotID = s.spotID
                  WHERE s.ownerID = ?";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $ownerId);

        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['avg_rating'] ?? 0;
    }

    // =========================
    // COUNT OWNER REVIEWS
    // =========================
    public function countOwnerReviews($ownerId) {
        $query = "SELECT COUNT(*) AS total
                  FROM review r
                  JOIN spot s ON r.spotID = s.spotID
                  WHERE s.ownerID = ?";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $ownerId);

        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'] ?? 0;
    }
}