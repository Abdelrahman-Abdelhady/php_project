<?php
session_start();
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/vehicleModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['userID'])) {
    $vModel = new Vehicle();
    
    $success = $vModel->addVehicle(
        $_SESSION['userID'],
        $_POST['licensePlate'],
        $_POST['type'],
        $_POST['model'],
        $_POST['color'],
        $_POST['height'],
        $_POST['width'],
        isset($_POST['is_default']) ? 1 : 0
    );

    if ($success) {
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/php_project/app/views/users/profile.php';
        header("Location: " . $redirect);
    } else {
        echo "Error saving data!";
    }
}