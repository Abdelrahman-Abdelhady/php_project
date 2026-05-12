<?php
require_once __DIR__ . '/../../core/Database.php';

class Review
{
    private $db;

    public function __construct($database = null)
    {
        $this->db = $database ?? Database::getInstance()->getConnection();
    }

    // =========================
    // ADD REVIEW - DRIVER
    // =========================
    public function addReview($userID, $spotID, $rating, $comment)
    {
        $sql = "INSERT INTO reviews (userID, spotID, rating, comment, created_at)
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("iiis", $userID, $spotID, $rating, $comment);

        return $stmt->execute();
    }

    // =========================
    // GET REVIEWS FOR ONE SPOT
    // =========================
    public function getSpotReviews($spotID)
    {
        $sql = "SELECT 
                    r.reviewID,
                    r.rating,
                    r.comment,
                    r.created_at,
                    u.name AS user_name
                FROM reviews r
                JOIN users u ON r.userID = u.userID
                WHERE r.spotID = ?
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $spotID);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // =========================
    // GET ALL REVIEWS
    // =========================
    public function getAllReviews()
    {
        $sql = "SELECT * FROM reviews ORDER BY reviewID DESC";

        $result = $this->db->query($sql);

        if (!$result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // =========================
    // DELETE REVIEW - DRIVER
    // =========================
    public function deleteReview($reviewID, $userID)
    {
        $sql = "DELETE FROM reviews 
                WHERE reviewID = ? AND userID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $reviewID, $userID);

        return $stmt->execute();
    }

    // =========================
    // GET REVIEWS FOR OWNER SPOTS
    // =========================
    public function getOwnerReviews($ownerID)
    {
        $sql = "SELECT 
                    r.reviewID,
                    r.rating,
                    r.comment,
                    r.created_at,
                    r.userID,
                    r.spotID,
                    u.name AS user_name,
                    s.spot_name,
                    s.address AS spot_location
                FROM reviews r
                JOIN users u ON r.userID = u.userID
                JOIN spot s ON r.spotID = s.spotID
                WHERE s.ownerID = ?
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $ownerID);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // =========================
    // GET AVERAGE RATING FOR OWNER
    // =========================
    public function getOwnerAverageRating($ownerID)
    {
        $sql = "SELECT COALESCE(AVG(r.rating), 0) AS avg_rating
                FROM reviews r
                JOIN spot s ON r.spotID = s.spotID
                WHERE s.ownerID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $ownerID);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return $row['avg_rating'] ?? 0;
    }

    // =========================
    // COUNT OWNER REVIEWS
    // =========================
    public function countOwnerReviews($ownerID)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM reviews r
                JOIN spot s ON r.spotID = s.spotID
                WHERE s.ownerID = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $ownerID);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return $row['total'] ?? 0;
    }
}