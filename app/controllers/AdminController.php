<?php
require_once "../app/helpers/Auth.php";
require_once "../app/models/SpotModel.php";
require_once "../app/models/ReservationModel.php";
require_once "../app/models/SensorModel.php";
require_once "../app/models/AdminModel.php";

class AdminController extends Controller
{
    private $spotModel;
    private $reservationModel;
    private $sensorModel;
    private $adminModel;

    public function __construct()
    {
        Auth::redirectIfNotLogged("Auth/adminLogin");
        Auth::forbidIfNotRole('admin');

        $this->spotModel = new SpotModel();
        $this->reservationModel = new ReservationModel();
        $this->sensorModel = new SensorModel();
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        $adminID = Auth::user()['id'];

        $stats = [
            'total_users'       => $this->adminModel->getTotalUsers(),
            'total_drivers'     => $this->adminModel->getDriverCount(),
            'total_owners'      => $this->adminModel->getOwnerCount(),
            'total_spots'       => method_exists($this->spotModel, 'getTotalSpots') ? $this->spotModel->getTotalSpots() : 0,
            'occupied_spots'    => method_exists($this->spotModel, 'getOccupiedSpots') ? $this->spotModel->getOccupiedSpots() : 0,
            'available_spots'   => method_exists($this->spotModel, 'getAvailableSpots') ? $this->spotModel->getAvailableSpots() : 0,
            'active_violations' => method_exists($this->reservationModel, 'getOverstayViolations') ? count($this->reservationModel->getOverstayViolations()) : 0,
            'total_fines'       => method_exists($this->reservationModel, 'getAllFines') ? count($this->reservationModel->getAllFines()) : 0,
            'unpaid_fines'      => count($this->adminModel->getDriversWithUnpaidFines()),
            'system_health'     => method_exists($this->sensorModel, 'getSensorStats') ? $this->sensorModel->getSensorStats() : []
        ];

        $this->view("admin/dashboard", [
            'stats'   => $stats,
            'adminID' => $adminID
        ]);
    }

    public function dashboard()
    {
        $this->index();
    }

    public function verifyOwner()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $spotID = $_POST['spotID'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($spotID && $status) {
                $this->adminModel->updateSpotStatus($spotID, $status);
                $_SESSION['message'] = "Owner verification updated successfully.";
            }

            header("Location: " . BASE_URL . "Admin/verifyOwner");
            exit;
        }

        $pendingSpots = $this->adminModel->getPendingSpots();

        $this->view("admin/verify_owner", [
            'pendingSpots' => $pendingSpots
        ]);
    }

    public function requests()
    {
        $pendingSpots = $this->adminModel->getPendingSpots();

        $this->view("admin/requests", [
            'pendingSpots' => $pendingSpots
        ]);
    }

    public function systemHealth()
    {
        $stats = method_exists($this->sensorModel, 'getSensorStats') ? $this->sensorModel->getSensorStats() : [];

        $this->view("admin/system_health", [
            'stats' => $stats
        ]);
    }

    public function sensors()
    {
        $sensors = method_exists($this->sensorModel, 'getAllSensors') ? $this->sensorModel->getAllSensors() : [];

        $this->view("admin/sensors", [
            'sensors' => $sensors
        ]);
    }

    public function eventLocking()
    {
        $this->view("admin/event_zone");
    }

    public function fines()
    {
        $driversWithFines = $this->adminModel->getDriversWithUnpaidFines();

        $this->view("admin/fines", [
            'driversWithFines' => $driversWithFines
        ]);
    }

    public function blacklist()
    {
        $blacklistedUsers = $this->adminModel->getBlacklistedUsers();

        $this->view("admin/blacklist", [
            'blacklistedUsers' => $blacklistedUsers
        ]);
    }
}