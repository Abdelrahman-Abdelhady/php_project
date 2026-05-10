<?php

require_once __DIR__ . '/../models/Spot.php';
require_once __DIR__ . '/../models/Earnings.php';
require_once __DIR__ . '/../models/Payout.php';
require_once __DIR__ . '/../models/Review.php';

class OwnerController {

    private $spotModel;
    private $earningsModel;
    private $payoutModel;
    private $reviewModel;

    public function __construct($db) {

        $this->spotModel = new Spot($db);
        $this->earningsModel = new Earnings($db);
        $this->payoutModel = new Payout($db);
        $this->reviewModel = new Review($db);
    }

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // =========================
    // SPOTS
    // =========================

    public function addSpot() {

        $this->startSession();

        $ownerid = $_SESSION['user'];

        $data = [
            'location' => $_POST['spot_name'],
            'zone' => $_POST['area'],
            'price_per_hour' => $_POST['price'],
            'status' => 'available',
            'ownerid' => $ownerid
        ];

        return $this->spotModel->create($data);
    }

    public function mySpots() {

        $this->startSession();

        $ownerid = $_SESSION['user'];

        return $this->spotModel->getOwnerSpots($ownerid);
    }

    public function deleteSpot($id) {

        return $this->spotModel->delete($id);
    }

    public function updateSpot() {

        $data = [
            'id' => $_POST['id'],
            'location' => $_POST['spot_name'],
            'zone' => $_POST['area'],
            'price_per_hour' => $_POST['price']
        ];

        return $this->spotModel->update($data);
    }

    public function countSpots() {

        $this->startSession();

        return $this->spotModel->countByOwner($_SESSION['user']);
    }

    // =========================
    // EARNINGS
    // =========================

    public function totalEarnings() {

        $this->startSession();

        return $this->earningsModel->getTotalEarnings($_SESSION['user']);
    }

    public function earningsList() {

        $this->startSession();

        return $this->earningsModel->getOwnerEarnings($_SESSION['user']);
    }

    // =========================
    // PAYOUT
    // =========================

    public function requestPayout() {

        $this->startSession();

        $ownerid = $_SESSION['user'];

        $amount = $_POST['amount'];
        $method = $_POST['method'];
        $account_info = $_POST['account_info'];

        $balance = $this->payoutModel->getBalance($ownerid);

        if ($amount > $balance) {
            return "INSUFFICIENT_BALANCE";
        }

        $success = $this->payoutModel->requestPayout(
            $ownerid,
            $amount,
            $method,
            $account_info
        );

        if ($success) {
            $this->payoutModel->decreaseBalance($ownerid, $amount);
        }

        return $success;
    }

    public function getPayouts() {

        $this->startSession();

        return $this->payoutModel->getOwnerPayouts($_SESSION['user']);
    }

   
    // REVIEWS
    

    public function getReviews() {

        $this->startSession();

        return $this->reviewModel->getOwnerReviews($_SESSION['user']);
    }

    public function getAverageRating() {

        $this->startSession();

        return $this->reviewModel->getOwnerAverageRating($_SESSION['user']);
    }

    public function countReviews() {

        $this->startSession();

        return $this->reviewModel->countOwnerReviews($_SESSION['user']);
    }
}