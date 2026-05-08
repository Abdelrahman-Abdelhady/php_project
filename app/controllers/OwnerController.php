<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/Spot.php';

class OwnerController {

    public function addSpot() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $database = Database::getInstance();
            $db = $database->getConnection();

            $spotModel = new Spot($db);
           $formData = [
             'location' => $_POST['spot_name'],
              'zone' => $_POST['area'],
              'price_per_hour' => $_POST['price'],
             'status' => 'available',
                'ownerid' => $_SESSION['user_id']
];
         

            if ($spotModel->create($formData)) {
                header("Location: dashboard.php?msg=success");
                exit();
            } else {
                echo "Error: Could not save the spot.";
            }
        }
    }
}