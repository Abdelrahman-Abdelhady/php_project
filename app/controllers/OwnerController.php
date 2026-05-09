<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/Spot.php';
require_once __DIR__ . '/../models/Earnings.php';

class OwnerController {

    // Create a new parking spot for the logged-in owner
    public function addSpot() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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

    public function mySpots() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $db = Database::getInstance()->getConnection();
        $spotModel = new Spot($db);

        $ownerid = $_SESSION['user']['id'] ?? 1;

        return $spotModel->getOwnerSpots($ownerid);
    }
public function deleteSpot() {

    // Get database connection
    $db = Database::getInstance()->getConnection();

    // Create Spot model object
    $spotModel = new Spot($db);

    // Get spot id from URL
    $id = $_GET['id'] ?? null;

    // If id exists
    if ($id) {

        // Delete spot from database
        $spotModel->delete($id);
    }

    // Redirect back to spots page
    header("Location: ../views/owner/spots.php?msg=deleted");
    exit();
}


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

    public function processPayout() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $db = Database::getInstance()->getConnection();
            require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/models/Payout.php';

            $payoutModel = new Payout($db);

            $ownerid = $_SESSION['user']['id'] ?? 1;

            $amount = $_POST['amount'];
            $method = $_POST['method'];
            $account_info = $_POST['account_info'];

            $query = "SELECT balance FROM wallet WHERE userID = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $ownerid);
            $stmt->execute();

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $balance = $row['balance'] ?? 0;

            if ($amount > $balance) {
                header("Location: ../views/owner/payout.php?error=balance");
                exit();
            }

            if ($payoutModel->requestPayout($ownerid, $amount, $method, $account_info)) {

                $query2 = "UPDATE wallet SET balance = balance - ?, last_updated = NOW() WHERE userID = ?";
                $stmt2 = $db->prepare($query2);
                $stmt2->bind_param("di", $amount, $ownerid);
                $stmt2->execute();

                header("Location: ../views/owner/payout.php?msg=success");
                exit();
            }
        }
    }

    public function updateProfile() {

        session_start();

        $db = Database::getInstance()->getConnection();

        $user_id = $_SESSION['user']['id'] ?? null;

        if (!$user_id) {
            header("Location: /php_project/app/views/owner/settings.php?msg=error");
            exit;
        }

        $name = $_POST['full_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['new_password'] ?? '';

        $profile_pic = null;

        if (!empty($_FILES['profile_pic']['name'])) {

            $imgName = time() . "_" . $_FILES['profile_pic']['name'];
            $path = __DIR__ . "/../../uploads/" . $imgName;

            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $path);

            $profile_pic = $imgName;
        }

        if (!empty($password)) {

            $password = password_hash($password, PASSWORD_DEFAULT);

            $query = "UPDATE users 
                      SET name=?, phone=?, email=?, password=?" .
                      ($profile_pic ? ", profile_pic=?" : "") .
                      " WHERE id=?";

            $stmt = $db->prepare($query);

            if ($profile_pic) {
                $stmt->bind_param("sssssi", $name, $phone, $email, $password, $profile_pic, $user_id);
            } else {
                $stmt->bind_param("ssssi", $name, $phone, $email, $password, $user_id);
            }

        } else {

            $query = "UPDATE users 
                      SET name=?, phone=?, email=?" .
                      ($profile_pic ? ", profile_pic=?" : "") .
                      " WHERE id=?";

            $stmt = $db->prepare($query);

            if ($profile_pic) {
                $stmt->bind_param("ssssi", $name, $phone, $email, $profile_pic, $user_id);
            } else {
                $stmt->bind_param("sssi", $name, $phone, $email, $user_id);
            }
        }

        $stmt->execute();

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;
        $_SESSION['user']['email'] = $email;

        if ($profile_pic) {
            $_SESSION['user']['profile_pic'] = $profile_pic;
        }

        header("Location: /php_project/app/views/owner/settings.php?msg=success");
        exit;
    }
}


/* ================= ROUTER (action handler) ================= */

$action = $_GET['action'] ?? '';

$controller = new OwnerController();

if ($action == 'updateProfile') {
    $controller->updateProfile();
}

if ($action == 'addSpot') {
    $controller->addSpot();
}

if ($action == 'deleteSpot') {
    $controller->deleteSpot();
}

if ($action == 'updateSpot') {
    $controller->updateSpot();
}

if ($action == 'processPayout') {
    $controller->processPayout();
}