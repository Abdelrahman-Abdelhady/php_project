<?php

class Review {

    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function addReview($userId, $spotId, $rating, $comment) {
        $query = "INSERT INTO review (userID, spotID, rating, comment, date)
              VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("iiis", $userId, $spotId, $rating, $comment);

        return $stmt->execute();
    }

    public function getSpotReviews($spotId) {
        $query = "SELECT * FROM review WHERE spotID = ? ORDER BY reviewID DESC";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i", $spotId);

        $stmt->execute();

        return $stmt->get_result();
    }

    public function deleteReview($reviewId, $userId) {
        $query = "DELETE FROM review WHERE reviewID = ? AND userID = ?";

        $stmt = $this->db->prepare($query);

        $stmt->bind_param("ii", $reviewId, $userId);

        return $stmt->execute();
    }
}