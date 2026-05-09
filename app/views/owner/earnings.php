<?php
session_start();

require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../controllers/EarningsController.php';

// DB
$database = Database::getInstance();
$conn = $database->getConnection();

// Controller
$controller = new EarningsController($conn);

$ownerid = $_SESSION['user_id'] ?? 1;

$balance=0;
/*

$balance = $controller->getBalance($ownerid);
*/

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

        /* Sidebar */
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

        .side-drawer a i {
            margin-right: 10px;
        }

        .side-drawer a:hover {
            background: #fff;
            color: #000;
        }

        /* Navbar */
        .page-wrapper {
            background: #fff;
            border-bottom: 1px solid #eee;
        }

        .navbar {
            padding: 1rem 3%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .site-title {
            font-weight: bold;
            color: #4F5D95;
        }

        /* Content */
        .main-content {
            flex: 1;
            padding: 2rem 5vw;
        }

        /* Balance Card */
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

    <a href="javascript:void(0)" onclick="toggleSidebar()">
        <i class="fas fa-times"></i> Close
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

        <div onclick="toggleSidebar()" style="cursor:pointer; font-size:20px;">
            ☰
        </div>

        <div class="site-title">
            CitySlot 🚘
        </div>

    </nav>
</div>

<!-- Content -->
<main class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>Financial Earnings</h2>

        <a href="payout.php" class="withdraw-btn">
            Withdraw Money
        </a>

    </div>

    <!-- Balance -->
    <div class="balance-card">

        <small>Total Available Balance</small>

        <h1 class="text-success mt-3">
            $<?php echo number_format((float)$balance, 2); ?>
        </h1>

        <p class="text-muted">
            You have <strong>$<?php echo number_format((float)$balance, 2); ?></strong> available.
        </p>

    </div>

</main>

<script>

function toggleSidebar() {

    const drawer = document.getElementById("sideDrawer");

    drawer.style.width =
        (drawer.style.width === "260px") ? "0" : "260px";
}

</script>

</body>
</html>