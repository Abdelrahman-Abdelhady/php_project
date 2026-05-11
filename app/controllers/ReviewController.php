<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/Review.php';

class ReviewController {

    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    public function add() {

        if (!isset($_SESSION['user_id'])) {
            header("Location: /php_project/public/login.php");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $spotId = $_POST['spot_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        $review = new Review($this->db);
        $review->addReview($userId, $spotId, $rating, $comment);

        header("Location: /php_project/public/history.php");
        exit;
    }

    public function delete() {

        if (!isset($_SESSION['user_id'])) {
            header("Location: /php_project/public/login.php");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $reviewId = $_POST['review_id'];

        $review = new Review($this->db);
        $review->deleteReview($reviewId, $userId);

        header("Location: /php_project/public/history.php");
        exit;
    }

    public function getReviewsBySpot($spotId) {

        $review = new Review($this->db);

        return $review->getSpotReviews($spotId);
    }
}