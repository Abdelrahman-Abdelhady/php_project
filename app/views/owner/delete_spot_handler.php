<?php
session_start();
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../../app/controllers/OwnerController.php';

$db = Database::getInstance()->getConnection();
$controller = new OwnerController($db);
$id = $_GET['id'] ?? 0;
$controller->deleteSpot($id);
?>