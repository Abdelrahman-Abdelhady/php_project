<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/Reviews.php';
require_once __DIR__ . '/../helpers/Auth.php';

class ReviewController {

    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    public function add() {

        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

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

        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $userId = $_SESSION['user_id'];
        $reviewId = $_POST['review_id'];

        $review = new Review($this->db);
        $review->deleteReview($reviewId, $userId);

        header("Location: /php_project/public/history.php");
        exit;
    }

    public function getReviewsBySpot($spotId) {

        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        $review = new Review($this->db);

        return $review->getSpotReviews($spotId);
    }
}