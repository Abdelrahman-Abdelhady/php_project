<?php
session_start();

require_once dirname(__DIR__, 2) . '/controllers/OwnerController.php';

$controller = new OwnerController();
$controller->addSpot();
?>