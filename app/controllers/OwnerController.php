<?php
require_once __DIR__ . '/../models/Spot.php';
require_once __DIR__ . '/../models/Earnings.php';
require_once __DIR__ . '/../models/Review.php';

class OwnerController {
    private $spotModel;
    private $earningsModel;
    private $reviewModel;

    public function __construct($db) {
        $this->spotModel = new Spot($db);
        $this->earningsModel = new Earnings($db);
        $this->reviewModel = new Review($db);
    }

    public function addSpot() {
        $ownerid = $_SESSION['user']['id'] ?? null;
        if (!$ownerid) {
            header("Location: dashboard.php?error=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'location' => $_POST['spot_name'],
                'zone' => $_POST['area'],
                'address' => $_POST['address'],
                'price_per_hour' => $_POST['price'],
                'capacity' => $_POST['capacity'],
                'status' => 'available',
                'ownerid' => $ownerid
            ];
            $success = $this->spotModel->create($data);
            if ($success) {
                header("Location: spots.php?msg=added");
            } else {
                header("Location: dashboard.php?error=db_failed");
            }
            exit();
        }
        header("Location: dashboard.php");
    }

    public function mySpots() {
        $ownerid = $_SESSION['user']['id'] ?? null;
        return $ownerid ? $this->spotModel->getOwnerSpots($ownerid) : [];
    }

    public function deleteSpot($id) {
        $ownerid = $_SESSION['user']['id'] ?? null;
        if ($ownerid) {
            $this->spotModel->deleteWithOwnerCheck($id, $ownerid);
        }
        header("Location: spots.php");
        exit();
    }

    public function countSpots() {
        $ownerid = $_SESSION['user']['id'] ?? null;
        return $ownerid ? $this->spotModel->countByOwner($ownerid) : 0;
    }

    public function totalEarnings() {
        $ownerid = $_SESSION['user']['id'] ?? null;
        return $ownerid ? $this->earningsModel->getTotalEarnings($ownerid) : 0;
    }

    public function getAverageRating() {
        $ownerid = $_SESSION['user']['id'] ?? null;
        return $ownerid ? $this->reviewModel->getOwnerAverageRating($ownerid) : 0;
    }
}
?>