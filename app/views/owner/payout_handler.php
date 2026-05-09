<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/controllers/OwnerController.php';

$controller = new OwnerController();
$controller->processPayout();
?>