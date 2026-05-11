<?php
// Controllers/AdminController.php
require_once "../Models/SpotModel.php";
require_once "../Models/ReservationModel.php";
require_once "../Models/SensorModel.php";
require_once "../Models/AdminModel.php";

class AdminController
{
    private $spotModel;
    private $reservationModel;
    private $sensorModel;
    private $AdminModel;
    
    public function __construct()
    {
        
        // Check if user is admin
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
            header("Location: ../simple_login.php");
            exit;
        }
        
        $this->spotModel = new SpotModel();
        $this->reservationModel = new ReservationModel();
        $this->sensorModel = new SensorModel();
        $this->AdminModel = new AdminModel();
    }
    
    public function dashboard()
    {
        $totalSpots = $this->spotModel->getTotalSpots();
        $occupiedSpots = $this->spotModel->getOccupiedSpots();
        $violations = $this->reservationModel->getOverstayViolations();
        $recentReservations = $this->reservationModel->getRecentReservations(10);
        $sensors = $this->sensorModel->getAllSensors();
        $stats = $this->sensorModel->getSensorStats();
        
        require_once "../Views/admin/dashboard.php";
    }
    
    public function verifyOwner()
    {
        $pendingSpots = $this->spotModel->getPendingSpots();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $spotID = $_POST['spotID'];
            $status = $_POST['status'];
            $this->spotModel->updateSpotStatus($spotID, $status);
            $_SESSION['message'] = "Owner verification updated";
            header("Location: ../index.php?controller=admin&action=verifyOwner");
            exit;
        }
        
        require_once "../Views/admin/verify_owner.php";
    }
    
    // Add more actions as needed...
}

// Router logic
if (isset($_GET['action'])) {
    $controller = new AdminController();
    $action = $_GET['action'];
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    }
}
?>