<?php
session_start();
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../../app/controllers/OwnerController.php';

// نعطي رد بصيغة JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing spot ID']);
    exit;
}

$spotId = $_POST['id'];
$ownerId = $_SESSION['user_id'] ?? 0; 

$db = Database::getInstance()->getConnection();
$controller = new OwnerController($db);
$result = $controller->deleteSpot($spotId, $ownerId); 

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not delete spot']);
}