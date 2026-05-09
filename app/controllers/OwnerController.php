<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/Spot.php';
require_once __DIR__ . '/../models/Earnings.php';

class OwnerController {

    // Create a new parking spot for the logged-in owner
    public function addSpot() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $db = Database::getInstance()->getConnection();
            $spotModel = new Spot($db);

            $ownerid = $_SESSION['user']['id'] ?? 1;

            $data = [
                'location' => $_POST['spot_name'],
                'zone' => $_POST['area'],
                'price_per_hour' => $_POST['price'],
                'status' => 'available',
                'ownerid' => $ownerid
            ];

            if ($spotModel->create($data)) {
                header("Location: ../views/owner/dashboard.php?msg=success");
                exit();
            }

            echo "Error saving spot";
        }
    }

    // Retrieve all spots belonging to the current owner
    public function mySpots() {

        $db = Database::getInstance()->getConnection();
        $spotModel = new Spot($db);

        $ownerid = $_SESSION['user']['id'] ?? 1;

        return $spotModel->getOwnerSpots($ownerid);
    }

    // Delete a specific spot by ID
 public function deleteSpot() {

    $db = Database::getInstance()->getConnection();
    $spotModel = new Spot($db);

    $id = $_GET['id'] ?? null;

    if ($id) {
        $spotModel->delete($id);
    }

    header("Location: ../views/owner/spots.php?msg=deleted");
    exit();
}

    

    // Update an existing parking spot
    public function updateSpot() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $db = Database::getInstance()->getConnection();
            $spotModel = new Spot($db);

            $data = [
                'id' => $_POST['id'],
                'location' => $_POST['spot_name'],
                'zone' => $_POST['area'],
                'price_per_hour' => $_POST['price']
            ];

            $spotModel->update($data);

            header("Location: ../views/owner/spots.php?msg=updated");
            exit();
        }
    }

    // Handle payout request and wallet deduction
    public function processPayout() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $db = Database::getInstance()->getConnection();
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/models/Payout.php';

            $payoutModel = new Payout($db);

            $ownerid = $_SESSION['user']['id'] ?? 1;

            $amount = $_POST['amount'];
            $method = $_POST['method'];
            $account_info = $_POST['account_info'];

            // Get current wallet balance
            $query = "SELECT balance FROM wallet WHERE userID = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $ownerid);
            $stmt->execute();

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $balance = $row['balance'] ?? 0;

            // Validate sufficient balance
            if ($amount > $balance) {
                header("Location: ../views/owner/payout.php?error=balance");
                exit();
            }

            // Create payout request
            if ($payoutModel->requestPayout($ownerid, $amount, $method, $account_info)) {

                // Deduct amount from wallet
                $query2 = "UPDATE wallet SET balance = balance - ?, last_updated = NOW() WHERE userID = ?";
                $stmt2 = $db->prepare($query2);
                $stmt2->bind_param("di", $amount, $ownerid);
                $stmt2->execute();

                header("Location: ../views/owner/payout.php?msg=success");
                exit();
            }
        }
    }

    // Update owner profile information and optional image/password
    public function updateProfile() {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') return;

        $db = Database::getInstance()->getConnection();

        $id = $_SESSION['user']['id'];

        $name  = trim($_POST['full_name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);

        if ($name == "" || $phone == "" || $email == "") {
            header("Location: ../views/owner/settings.php?msg=empty");
            exit();
        }

        $profile_pic = $_SESSION['user']['profile_pic'] ?? null;

        // Upload new profile image if provided
        if (!empty($_FILES['profile_pic']['name'])) {

            $file = time() . "_" . $_FILES['profile_pic']['name'];
            $path = "../uploads/img/" . $file;

            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $path);

            $profile_pic = "uploads/img/" . $file;
        }

        // Update including password
        if (!empty($_POST['new_password'])) {

            $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            $query = "UPDATE users SET name=?, phone=?, email=?, password=?, profile_pic=? WHERE id=?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("sssssi", $name, $phone, $email, $password, $profile_pic, $id);

        } else {

            // Update without changing password
            $query = "UPDATE users SET name=?, phone=?, email=?, profile_pic=? WHERE id=?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssssi", $name, $phone, $email, $profile_pic, $id);
        }

        if ($stmt->execute()) {

            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['profile_pic'] = $profile_pic;

            header("Location: ../views/owner/dashboard.php?msg=updated");
            exit();
        }

        echo "Error updating profile";
    }
}