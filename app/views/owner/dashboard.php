<?php
// session_start();  <-- إحذف هذا السطر أو علّقه
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../../app/controllers/OwnerController.php';

$db = Database::getInstance()->getConnection();
$controller = new OwnerController($db);
$spots_count = $controller->countSpots();
$total_earnings = $controller->totalEarnings();
$avg_rating = $controller->getAverageRating();
?>
<!DOCTYPE html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CitySlot - Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body{font-family:'Segoe UI',sans-serif;background:#f4f7f6;margin:0;}
        .side-drawer{height:100vh;width:0;position:fixed;z-index:2000;top:0;left:0;background:#000;overflow-x:hidden;transition:0.5s;padding-top:3rem;}
        .side-drawer a{padding:1rem 2rem;text-decoration:none;font-size:1rem;color:#fff;display:flex;align-items:center;border-bottom:1px solid #111;text-transform:uppercase;}
        .side-drawer a i{margin-right:15px;color:#888;}
        .side-drawer a:hover{background:#fff;color:#000;}
        .side-drawer a:hover i{color:#000;}
        .close-btn{color:#666!important;cursor:pointer;}
        .page-wrapper{background:#fff;border-bottom:1px solid #eee;box-shadow:0 2px 5px rgba(0,0,0,0.05);}
        .navbar{padding:1rem 3%;display:flex;justify-content:space-between;}
        .site-title{font-weight:bold;color:#4F5D95;font-size:1.2rem;}
        .menu-icon{font-size:1.5rem;cursor:pointer;color:#4F5D95;}
        .main-content{padding:2rem 5vw;}
        .stat-card{background:#fff;padding:2rem;border-radius:0.75rem;box-shadow:0 4px 15px rgba(0,0,0,0.1);text-align:center;border-top:0.3rem solid #4F5D95;margin-bottom:1.5rem;}
        .stat-card h2{color:#4F5D95;font-weight:bold;}
        .btn-add-custom{background:#4F5D95;color:#fff;border:none;border-radius:2rem;padding:0.6rem 2rem;font-weight:600;}
        .btn-add-custom:hover{background:#3b4675;}
        .modal-content{border-radius:1rem;border-top:0.5rem solid #4F5D95;}
        .form-label{color:#4F5D95;font-weight:bold;}
    </style>
</head>
<body>
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
        <div class="alert alert-success m-3">Spot added successfully!</div>
    <?php endif; ?>
    <div id="sideDrawer" class="side-drawer">
        <a href="javascript:void(0)" class="close-btn" onclick="toggleSidebar()">Close Menu ×</a>
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="spots.php"><i class="fas fa-parking"></i> My Spots</a>
        <a href="earnings.php"><i class="fas fa-wallet"></i> Earnings</a>
        <a href="reviews.php"><i class="fas fa-star"></i> Reviews</a>
    </div>
    <div class="page-wrapper">
        <nav class="navbar"><div class="menu-icon" onclick="toggleSidebar()">☰</div><div class="site-title">CitySlot 🚘</div></nav>
    </div>
    <main class="main-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Owner Dashboard</h2>
                <button class="btn-add-custom" data-bs-toggle="modal" data-bs-target="#addSpotModal">+ Add New Spot</button>
            </div>
            <div class="row">
                <div class="col-md-4"><div class="stat-card"><small>TOTAL REVENUE</small><h2>$<?= number_format($total_earnings,2) ?></h2></div></div>
                <div class="col-md-4"><div class="stat-card"><small>REGISTERED SPOTS</small><h2><?= $spots_count ?></h2></div></div>
                <div class="col-md-4"><div class="stat-card"><small>AVG. RATING</small><h2><?= number_format($avg_rating,1) ?> ★</h2></div></div>
            </div>
        </div>
    </main>
    <!-- Modal -->
    <div class="modal fade" id="addSpotModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Add New Parking Spot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="add_spot_handler.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">Spot Name </label><input type="text" name="spot_name" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Area</label><select name="area" class="form-select" required><option value="maadi">Maadi</option><option value="nasr_city">Nasr City</option><option value="tagamoa">New Cairo</option><option value="zamalek">Zamalek</option><option value="dokki">Dokki</option><option value="sheikh_zayed">Sheikh Zayed</option><option value="october">6th October</option></select></div>
                        <div class="mb-3"><label class="form-label">Detailed Address</label><textarea name="address" class="form-control" rows="2" placeholder="Street, building, landmark" required></textarea></div>
                        <div class="row">
                            <div class="col-6"><label class="form-label">Price ($/Hr)</label><input type="number" name="price" step="0.01" class="form-control" required></div>
                            <div class="col-6"><label class="form-label">Capacity</label><input type="number" name="capacity" class="form-control" placeholder="Number of cars" required></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-add-custom px-4">Save Spot</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>function toggleSidebar(){const d=document.getElementById("sideDrawer");d.style.width=d.style.width==="260px"?"0":"260px";}</script>
</body>
</html>