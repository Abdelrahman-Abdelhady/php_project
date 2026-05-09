<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/core/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/controllers/OwnerController.php';

$controller = new OwnerController();
$spots = $controller->mySpots();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Spots</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{
    background:#f4f7f6;
    font-family:Segoe UI;
    margin:0;
}

/* ===== SIDEBAR (same dashboard) ===== */
.side-drawer {
    height: 100vh;
    width: 0;
    position: fixed;
    z-index: 2000;
    top: 0;
    left: 0;
    background-color: #000000;
    overflow-x: hidden;
    transition: 0.5s;
    padding-top: 3rem;
}

.side-drawer a {
    padding: 1rem 2rem;
    text-decoration: none;
    font-size: 1rem;
    color: #ffffff;
    display: flex;
    align-items: center;
    transition: 0.3s;
    border-bottom: 1px solid #111;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.side-drawer a i {
    margin-right: 15px;
    width: 20px;
    text-align: center;
    color: #888;
}

.side-drawer a:hover {
    background-color: #ffffff;
    color: #000000;
}

.side-drawer a:hover i {
    color: #000000;
}

.close-btn {
    color: #666 !important;
    font-size: 0.8rem !important;
    cursor: pointer;
}

/* ===== NAVBAR ===== */
.page-wrapper{
    background:#fff;
    border-bottom:1px solid #eee;
    box-shadow:0 2px 5px rgba(0,0,0,0.05);
}

.navbar{
    padding:15px 3%;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.menu{
    font-size:22px;
    cursor:pointer;
    color:#4F5D95;
}

/* ===== CARDS ===== */
.spot-card{
    background:#fff;
    padding:20px;
    margin-bottom:15px;
    border-radius:15px;
    box-shadow:0 4px 15px rgba(0,0,0,0.08);
    border-left:5px solid #4F5D95;
}

.btn-delete{
    border-radius:20px;
    font-weight:500;
}
</style>
</head>

<body>

<!-- Sidebar (DASHBOARD STYLE) -->
<div id="sideDrawer" class="side-drawer">
    <a href="javascript:void(0)" class="close-btn" onclick="toggleSidebar()">
        Close Menu ×
    </a>

    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="spots.php"><i class="fas fa-parking"></i> My Spots</a>
    <a href="earnings.php"><i class="fas fa-wallet"></i> Earnings</a>
    <a href="reviews.php"><i class="fas fa-star"></i> Reviews</a>
    <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
</div>

<!-- Navbar -->
<div class="page-wrapper">
    <nav class="navbar">
        <div class="menu" onclick="toggleSidebar()">☰</div>
        <div><b>CitySlot 🚘</b></div>
    </nav>
</div>

<!-- Content -->
<div class="container mt-4">

<h2 style="color:#4F5D95; font-weight:bold;">My Spots</h2>

<?php if($spots && $spots->num_rows > 0): ?>

    <?php while($row = $spots->fetch_assoc()): ?>

        <?php $id = $row['ID'] ?? 0; ?>

        <div class="spot-card">

            <h4><?= $row['location'] ?></h4>

            <p><b>Area:</b> <?= $row['zone'] ?></p>
            <p><b>Price:</b> <?= $row['price_per_hour'] ?> EGP</p>
            <p><b>Status:</b> <?= $row['status'] ?></p>

            <a href="../../controllers/OwnerController.php?action=deleteSpot&id=<?= $id ?>"
               class="btn btn-danger btn-sm btn-delete"
               onclick="return confirm('Are you sure you want to delete this spot?')">
               Delete
            </a>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="alert alert-warning">
        No spots found.
    </div>

<?php endif; ?>

</div>

<script>
function toggleSidebar(){
    let d = document.getElementById("sideDrawer");
    d.style.width = (d.style.width === "260px") ? "0" : "260px";
}
</script>

</body>
</html>