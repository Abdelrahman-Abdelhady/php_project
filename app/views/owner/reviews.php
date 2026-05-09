<?php
session_start();

require_once __DIR__ . '/../../../core/Database.php';

$database = Database::getInstance();
$conn = $database->getConnection();

$owner_id = $_SESSION['user_id'] ?? 1;

// Get reviews for this owner
$query = "SELECT 
            r.rating, 
            r.comment, 
            r.date, 
            u.name AS user_name, 
            s.location AS spot_display_name
          FROM review r
          JOIN users u ON r.userID = u.userID
          JOIN spot s ON r.spotID = s.spotID
          WHERE s.ownerID = ?
          ORDER BY r.date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $owner_id);
$stmt->execute();

$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Driver Reviews</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            z-index: 2000;
            top: 0;
            left: 0;
            background-color: #000;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 3rem;
        }

        .side-drawer a {
            padding: 1rem 2rem;
            text-decoration: none;
            font-size: 1rem;
            color: #fff;
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
            background-color: #fff;
            color: #000;
        }

        .side-drawer a:hover i {
            color: #000;
        }

        .close-btn {
            color: #666 !important;
            text-align: left !important;
            font-size: 0.8rem !important;
            cursor: pointer;
        }

        /* Navbar */
        .page-wrapper {
            background-color: white;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
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
            font-size: 1.2rem;
        }

        .menu-icon {
            font-size: 1.5rem;
            cursor: pointer;
            color: #4F5D95;
        }

        /* Main Content */
        .main-content {
            padding: 2rem 5vw;
            flex-grow: 1;
        }

        /* Review Cards */
        .review-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
            height: 100%;
            border-top: 5px solid #4F5D95;
        }

        .review-card:hover {
            transform: translateY(-5px);
        }

        .review-user {
            font-size: 1.1rem;
            font-weight: bold;
            color: #4F5D95;
        }

        .stars {
            color: #ffc107;
            margin: 10px 0;
            font-size: 1.1rem;
        }

        .review-comment {
            color: #555;
            line-height: 1.6;
            min-height: 70px;
        }

        .spot-tag {
            display: inline-block;
            background: #eef0f7;
            color: #4F5D95;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .review-date {
            font-size: 0.8rem;
            color: #999;
            margin-top: 12px;
        }

        .empty-box {
            background: white;
            padding: 4rem;
            border-radius: 1rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

    </style>
</head>

<body>

<!-- Sidebar -->
<div id="sideDrawer" class="side-drawer">

    <a href="javascript:void(0)"
       class="close-btn"
       onclick="toggleSidebar()">

       Close Menu ×

    </a>

    <a href="dashboard.php">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <a href="spots.php">
        <i class="fas fa-parking"></i> My Spots
    </a>

    <a href="earnings.php">
        <i class="fas fa-wallet"></i> Earnings
    </a>

    <a href="reviews.php">
        <i class="fas fa-star"></i> Reviews
    </a>

    <a href="settings.php">
        <i class="fas fa-cog"></i> Settings
    </a>

</div>

<!-- Navbar -->
<div class="page-wrapper">

    <nav class="navbar">

        <div class="menu-icon"
             onclick="toggleSidebar()">☰</div>

        <div class="d-flex align-items-center">

            <span class="site-title">CitySlot</span>

            <span style="font-size: 1.5rem; margin-left: 10px;">
                🚘
            </span>

        </div>

    </nav>

</div>

<!-- Main Content -->
<main class="main-content">

    <div class="container-fluid">

        <h2 class="fw-bold mb-4">
            Driver Reviews
        </h2>

        <?php if (empty($reviews)): ?>

            <div class="empty-box">

                <i class="fas fa-comments fa-3x text-secondary mb-3"></i>

                <h5 class="text-muted">
                    No reviews from drivers yet.
                </h5>

            </div>

        <?php else: ?>

            <div class="row g-4">

                <?php foreach ($reviews as $r): ?>

                    <div class="col-md-6 col-lg-4">

                        <div class="review-card">

                            <div class="review-user">
                                <?= htmlspecialchars($r['user_name']) ?>
                            </div>

                            <div class="stars">

                                <?php for ($i = 0; $i < (int)$r['rating']; $i++): ?>
                                    ★
                                <?php endfor; ?>

                            </div>

                            <div class="review-comment">

                                <?= htmlspecialchars($r['comment']) ?>

                            </div>

                            <div class="mt-3">

                                <span class="spot-tag">

                                    <?= htmlspecialchars($r['spot_display_name']) ?>

                                </span>

                            </div>

                            <div class="review-date">

                                <?= $r['date'] ?>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</main>

<script>

function toggleSidebar() {

    const drawer = document.getElementById("sideDrawer");

    drawer.style.width =
        (drawer.style.width === "260px") ? "0" : "260px";
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>