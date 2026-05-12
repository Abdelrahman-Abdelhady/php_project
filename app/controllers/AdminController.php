<?php
// Controllers/AdminController.php

// Using __DIR__ ensures these paths work correctly regardless of the URL
require_once __DIR__ . "/../models/SpotModel.php";
require_once __DIR__ . "/../models/ReservationModel.php";
require_once __DIR__ . "/../models/SensorModel.php";
require_once __DIR__ . "/../models/AdminModel.php";

class AdminController extends Controller
{
    private $spotModel;
    private $reservationModel;
    private $sensorModel;
    private $AdminModel;
    
    public function __construct()
    {
        // Start the session if it hasn't been started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is admin
        // Redirecting to User/index prevents the 404 error from direct file access
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
            header("Location: " . BASE_URL . "User/index");
            exit;
        }
        
        // Moving instantiation inside the constructor fixes the Parse Error on line 11
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
        
        // Loads the dashboard view
        $this->view("admin/dashboard", [
            'totalSpots' => $totalSpots,
            'occupiedSpots' => $occupiedSpots,
            'violations' => $violations,
            'recentReservations' => $recentReservations,
            'sensors' => $sensors,
            'stats' => $stats
        ]);
    }
    
    public function verifyOwner()
    {
        $pendingSpots = $this->spotModel->getPendingSpots();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $spotID = $_POST['spotID'];
            $status = $_POST['status'];
            $this->spotModel->updateSpotStatus($spotID, $status);
            
            $_SESSION['message'] = "Owner verification updated";
            
            header("Location: " . BASE_URL . "Admin/verifyOwner");
            exit;
        }
        
        $this->view("admin/verify_owner", ['pendingSpots' => $pendingSpots]);
    }
}