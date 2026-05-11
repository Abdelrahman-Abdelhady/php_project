<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../login.php');
    exit;
}

require_once __DIR__ . '/../../../core/Database.php';

$db = Database::getInstance()->getConnection();

$userId = $_SESSION['user_id'];
$fullName = $_POST['full_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// التحقق من صحة الإيميل
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: settings.php?msg=error');
    exit;
}

// التحقق من تطابق كلمة المرور إذا تم إدخالها
if (!empty($newPassword) && $newPassword !== $confirmPassword) {
    header('Location: settings.php?msg=error');
    exit;
}

// رفع الصورة إذا وُجدت
$profilePicPath = null;
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../../uploads/profiles/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
    $fileName = 'user_' . $userId . '_' . time() . '.' . $ext;
    $destination = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination)) {
        $profilePicPath = 'uploads/profiles/' . $fileName;
    }
}

// بناء استعلام التحديث
$updateFields = [];
$params = [];
$types = '';

$updateFields[] = "name = ?";
$params[] = $fullName;
$types .= 's';

$updateFields[] = "phone = ?";
$params[] = $phone;
$types .= 's';

$updateFields[] = "email = ?";
$params[] = $email;
$types .= 's';

if (!empty($newPassword)) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateFields[] = "password = ?";
    $params[] = $hashedPassword;
    $types .= 's';
}

if ($profilePicPath) {
    $updateFields[] = "profile_pic = ?";
    $params[] = $profilePicPath;
    $types .= 's';
}

$params[] = $userId;
$types .= 'i';

$sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header('Location: settings.php?msg=success');
} else {
    header('Location: settings.php?msg=error');
}
exit;
