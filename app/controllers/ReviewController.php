<?php
require_once "../app/helpers/Auth.php";
require_once "../app/models/Review.php";

class ReviewController extends Controller
{
    private $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new Review();
    }

    public function store()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "Home/index");
            exit;
        }

        $userID = Auth::user()['id'];
        $spotID = $_POST['spotID'] ?? $_POST['spot_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = trim($_POST['comment'] ?? '');

        if (!$spotID || !$rating || $comment === '') {
            header("Location: " . BASE_URL . "Home/index");
            exit;
        }

        $rating = (int)$rating;

        if ($rating < 1 || $rating > 5) {
            header("Location: " . BASE_URL . "Home/index");
            exit;
        }

        $this->reviewModel->addReview($userID, (int)$spotID, $rating, $comment);

        header("Location: " . BASE_URL . "Home/index");
        exit;
    }

    public function delete()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('driver');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "Home/index");
            exit;
        }

        $userID = Auth::user()['id'];
        $reviewID = $_POST['reviewID'] ?? $_POST['review_id'] ?? null;

        if ($reviewID) {
            $this->reviewModel->deleteReview((int)$reviewID, $userID);
        }

        header("Location: " . BASE_URL . "Home/index");
        exit;
    }
}