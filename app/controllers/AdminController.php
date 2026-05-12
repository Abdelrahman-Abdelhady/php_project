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
        Auth::forbidIfNotAdmin();

        $this->spotModel = new SpotModel();
        $this->reservationModel = new ReservationModel();
        $this->sensorModel = new SensorModel();
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        $adminID = Auth::user()['id'];

        $totalSpots = method_exists($this->spotModel, 'getTotalSpots')
            ? $this->spotModel->getTotalSpots()
            : 0;

        $occupiedSpots = method_exists($this->spotModel, 'getOccupiedSpots')
            ? $this->spotModel->getOccupiedSpots()
            : 0;

        $availableSpots = method_exists($this->spotModel, 'getAvailableSpots')
            ? $this->spotModel->getAvailableSpots()
            : 0;

        $violations = method_exists($this->reservationModel, 'getOverstayViolations')
            ? $this->reservationModel->getOverstayViolations()
            : [];

        $fines = method_exists($this->reservationModel, 'getAllFines')
            ? $this->reservationModel->getAllFines()
            : [];

        $systemHealth = method_exists($this->sensorModel, 'getSensorStats')
            ? $this->sensorModel->getSensorStats()
            : [];

        $stats = [
            'total_users'       => $this->adminModel->getTotalUsers(),
            'total_drivers'     => $this->adminModel->getDriverCount(),
            'total_owners'      => $this->adminModel->getOwnerCount(),

            'total_spots'       => is_array($totalSpots) ? count($totalSpots) : $totalSpots,
            'occupied_spots'    => is_array($occupiedSpots) ? count($occupiedSpots) : $occupiedSpots,
            'available_spots'   => is_array($availableSpots) ? count($availableSpots) : $availableSpots,

            'active_violations' => is_array($violations) ? count($violations) : 0,
            'total_fines'       => is_array($fines) ? count($fines) : 0,
            'unpaid_fines'      => count($this->adminModel->getDriversWithUnpaidFines()),

            'system_health'     => $systemHealth
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
            $action = $_POST['action'] ?? null;

            if ($action === 'approve') {
                $status = 'available';
            } elseif ($action === 'reject') {
                $status = 'rejected';
            } else {
                $status = null;
            }

            if ($spotID && $status) {
                $updated = $this->adminModel->updateSpotStatus((int)$spotID, $status);

                $_SESSION['message'] = $updated
                    ? "Spot status updated successfully."
                    : "Could not update spot status.";
            } else {
                $_SESSION['message'] = "Invalid approval request.";
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

    public function dispatch()
    {
        $this->view("admin/dispatch");
    }

    public function eventLocking()
    {
        $this->view("admin/event_zone");
    }

    public function emergency()
    {
        $this->view("admin/emergency");
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

    public function systemHealth()
    {
        $stats = method_exists($this->sensorModel, 'getSensorStats')
            ? $this->sensorModel->getSensorStats()
            : [];

        $this->view("admin/system_health", [
            'stats' => $stats
        ]);
    }

    public function settings()
    {
        $this->view("admin/settings");
    }
}