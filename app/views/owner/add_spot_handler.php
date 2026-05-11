<?php
session_start();
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../../app/controllers/OwnerController.php';

// التأكد من أن المستخدم مسجل دخوله ولديه user_id
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// التأكد من أن الطلب هو POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php?msg=error');
    exit;
}

// جمع البيانات من النموذج
$data = [
    'spot_name'     => $_POST['spot_name'] ?? '',
    'zone'          => $_POST['area'] ?? '',       // لأن اسم الحقل في الفورم هو "area"
    'address'       => $_POST['address'] ?? '',
    'price_per_hour'=> $_POST['price'] ?? 0,
    'capacity'      => $_POST['capacity'] ?? 0,
    'status'        => 'available',
    'ownerID'       => $_SESSION['user_id']        // معرف المالك من الجلسة
];

// التحقق من البيانات الأساسية
if (empty($data['spot_name']) || empty($data['zone']) || empty($data['address'])) {
    header('Location: dashboard.php?msg=missing_data');
    exit;
}

$db = Database::getInstance()->getConnection();
$controller = new OwnerController($db);
$result = $controller->addSpot($data);

if ($result) {
    header('Location: dashboard.php?msg=added');
} else {
    header('Location: dashboard.php?msg=error');
}
exit;