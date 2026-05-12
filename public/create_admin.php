<?php
require_once "../core/Database.php";

$db = Database::getInstance()->getConnection();

$name = "Main Admin";
$email = "admin@cityslot.com";
$password = "admin123456";
$role = "admin";

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Check if admin already exists
$checkSql = "SELECT adminID FROM admins WHERE email = ?";
$checkStmt = $db->prepare($checkSql);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->fetch_assoc()) {
    echo "Admin already exists. Delete this file now.";
    exit;
}

$sql = "INSERT INTO admins (name, email, password, role, created_at)
        VALUES (?, ?, ?, ?, NOW())";

$stmt = $db->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $db->error);
}

$stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    echo "Admin created successfully.<br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br>";
    echo "<strong>IMPORTANT: Delete create_admin.php now.</strong>";
} else {
    echo "Error: " . $stmt->error;
}