<?php

require_once __DIR__ . '/../../../core/Database.php';

$database = Database::getInstance();
$conn = $database->getConnection();

/* Temporary owner id */
$owner_id = $_SESSION['user_id'] ?? 1;

/* Get form data */
$zone = $_POST['area'];
$location = $_POST['address'];
$price = $_POST['price'];

/* Default status */
$status = "available";

/* Insert into spot table */

$query = "INSERT INTO spot
(location, zone, price_per_hour, status, ownerID)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

$stmt->bind_param(
    "ssdsi",
    $location,
    $zone,
    $price,
    $status,
    $owner_id
);

if ($stmt->execute()) {

    header("Location: dashboard.php?msg=success");
    exit();

} else {

    echo "Error: " . $stmt->error;
}
?>