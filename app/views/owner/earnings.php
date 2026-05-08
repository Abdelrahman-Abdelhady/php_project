<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/core/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/php_project/app/controllers/EarningsController.php';

$controller = new EarningsController();

// لازم يكون عندك user_id في session
$ownerid = $_SESSION['user_id'] ?? 0;

// لو مفيش user
if ($ownerid == 0) {
    die("Unauthorized access");
}

$balance = $controller->showEarnings($ownerid);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Financial Earnings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .side-drawer {
            height: 100vh;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #000;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 3rem;
            z-index: 2000;
        }

        .side-drawer a {
            padding: 1rem 2rem;
            text-decoration: none;
            font-size: 1rem;
            color: #fff;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #111;
        }

        .side-drawer a:hover {
            background: #fff;
            color: #000;
        }

        .page-wrapper {
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .navbar {
            padding: 1rem 3%;
            display: flex;
            justify-content: space-between;
        }

        .site-title {
            font-weight: bold;
            color: #4F5D95;
        }

        .main-content {
            flex: 1;
            padding: 2rem 5vw;
        }

        .balance-card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 1rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-top: 5px solid #28a745;
        }

        .withdraw-btn {
            background: #4F5D95;
            color: #fff;
            padding: 0.6rem 2rem;
            border-radius: 2rem;
            text-decoration: none;
        }

        .withdraw-btn:hover {
            background: #3b4675;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div id="sideDrawer" class="side-drawer">
    <a href="javascript:void(0)" onclick="toggleSidebar()">Close ×</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="spots.php">My Spots</a>
    <a href="earnings.php">Earnings</a>
</div>

<!-- Navbar -->
<div class="page-wrapper">
    <nav class="navbar">
        <div onclick="toggleSidebar()" style="cursor:pointer;">☰</div>
        <div class="site-title">CitySlot 🚘</div>
    </nav>
</div>

<!-- Content -->
<main class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Financial Earnings</h2>
        <a href="payout.html" class="withdraw-btn">Withdraw Money</a>
    </div>

    <!-- Balance -->
    <div class="balance-card">
        <small>Total Available Balance</small>

        <h1 class="text-success mt-3">
            $<?php echo number_format($balance, 2); ?>
        </h1>

        <p class="text-muted">
            You have <strong>$<?php echo number_format($balance, 2); ?></strong> pending clearance.
        </p>
    </div>

</main>

<script>
function toggleSidebar() {
    const drawer = document.getElementById("sideDrawer");
    drawer.style.width = drawer.style.width === "260px" ? "0" : "260px";
}
</script>

</body>
</html>