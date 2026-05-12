<?php
require_once "../app/helpers/Auth.php";
require_once "../app/models/Spot.php";
require_once "../app/models/Review.php";

class OwnerController extends Controller
{
    private $spotModel;
    private $reviewModel;

    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole('space_owner');

        $this->spotModel = new Spot();
        $this->reviewModel = new Review();
    }

    public function dashboard()
    {
        $ownerid = Auth::user()['id'];

        $spotCount = $this->spotModel->countByOwner($ownerid);
        $averageRating = $this->reviewModel->getOwnerAverageRating($ownerid);

        $this->view("owner/dashboard", [
            'user' => Auth::user(),
            'spotCount' => $spotCount,
            'averageRating' => $averageRating,
            'totalEarnings' => 0
        ]);
    }

    public function spots()
    {
        $ownerid = Auth::user()['id'];

        $spots = $this->spotModel->getOwnerSpots($ownerid);

        $this->view("owner/spots", [
            'spots' => $spots
        ]);
    }

    public function storeSpot()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "Owner/dashboard");
            exit;
        }

        $ownerid = Auth::user()['id'];

        $title = trim($_POST['title'] ?? '');
        $area = trim($_POST['area'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $price = $_POST['price_per_hour'] ?? '';
        $capacity = $_POST['capacity'] ?? '';

        $heightLimit = $_POST['height_limit'] ?? 0;
        $widthLimit = $_POST['width_limit'] ?? 0;
        $evCharging = isset($_POST['ev_charging']) ? 1 : 0;
        $cctv = isset($_POST['cctv']) ? 1 : 0;
        $image = "uploads/img/default_spot.png";

        $errors = [];

        if ($title === '') {
            $errors['title'] = "Title is required.";
        }

        if ($area === '') {
            $errors['area'] = "Area is required.";
        }

        if ($location === '') {
            $errors['location'] = "Location is required.";
        }

        if ($address === '') {
            $errors['address'] = "Address is required.";
        }

        if ($price === '' || !is_numeric($price) || $price <= 0) {
            $errors['price_per_hour'] = "Price must be greater than 0.";
        }

        if ($capacity === '' || !is_numeric($capacity) || $capacity <= 0) {
            $errors['capacity'] = "Capacity must be greater than 0.";
        }

        if (!empty($errors)) {
            $this->view("owner/dashboard", [
                'user' => Auth::user(),
                'spotCount' => $this->spotModel->countByOwner($ownerid),
                'averageRating' => $this->reviewModel->getOwnerAverageRating($ownerid),
                'totalEarnings' => 0,
                'errors' => $errors,
                'old' => $_POST
            ]);
            return;
        }

        $created = $this->spotModel->create([
            'ownerid' => $ownerid,
            'title' => $title,
            'area' => $area,
            'location' => $location,
            'address' => $address,
            'price_per_hour' => (float)$price,
            'capacity' => (int)$capacity,
            'status' => 'pending',
            'height_limit' => (float)$heightLimit,
            'width_limit' => (float)$widthLimit,
            'ev_charging' => $evCharging,
            'cctv' => $cctv,
            'image' => $image
        ]);

        if (!$created) {
            $this->view("owner/dashboard", [
                'user' => Auth::user(),
                'spotCount' => $this->spotModel->countByOwner($ownerid),
                'averageRating' => $this->reviewModel->getOwnerAverageRating($ownerid),
                'totalEarnings' => 0,
                'errors' => ['database' => 'Could not add spot. Please check database columns.'],
                'old' => $_POST
            ]);
            return;
        }

        $_SESSION['message'] = "Spot added successfully. Waiting for admin approval.";

        header("Location: " . BASE_URL . "Owner/dashboard");
        exit;
    }

    public function deleteSpot($spotID = null)
    {
        $ownerid = Auth::user()['id'];

        if ($spotID !== null) {
            $this->spotModel->deleteWithOwnerCheck((int)$spotID, (int)$ownerid);
        }

        header("Location: " . BASE_URL . "Owner/spots");
        exit;
    }

    public function deleteSpotAjax()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method.'
            ]);
            exit;
        }

        $ownerid = Auth::user()['id'];
        $spotID = $_POST['spotID'] ?? null;

        if (!$spotID) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing spot ID.'
            ]);
            exit;
        }

        $deleted = $this->spotModel->deleteWithOwnerCheck((int)$spotID, (int)$ownerid);

        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Spot deleted successfully.' : 'Could not delete spot.'
        ]);
        exit;
    }

    public function reviews()
    {
        $ownerid = Auth::user()['id'];

        $reviews = $this->reviewModel->getOwnerReviews($ownerid);
        $averageRating = $this->reviewModel->getOwnerAverageRating($ownerid);
        $reviewCount = $this->reviewModel->countOwnerReviews($ownerid);

        $this->view("owner/reviews", [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'reviewCount' => $reviewCount
        ]);
    }

    public function earnings()
    {
        $this->view("owner/earnings", [
            'totalEarnings' => 0
        ]);
    }

    public function settings()
    {
        $this->view("owner/settings", [
            'user' => Auth::user()
        ]);
    }
}