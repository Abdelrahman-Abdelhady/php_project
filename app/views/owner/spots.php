<?php
session_start();
require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../../app/controllers/OwnerController.php';

$db = Database::getInstance()->getConnection();
$controller = new OwnerController($db);
$spots = $controller->mySpots();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Spots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f7f6; font-family:Segoe UI; }
        .spots-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:20px; margin-top:20px; }
        .spot-card { background:#fff; padding:20px; border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.08); border-left:5px solid #4F5D95; }
        .btn-back { margin-bottom:20px; }
    </style>
</head>
<body>
<div class="container mt-4">
    <a href="dashboard.php" class="btn btn-secondary btn-back">← Back to Dashboard</a>
    <h2 style="color:#4F5D95; font-weight:bold;">My Spots</h2>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
        <div class="alert alert-success">Spot added successfully!</div>
    <?php endif; ?>

    <div class="spots-grid">
        <?php if (!empty($spots)): ?>
            <?php foreach ($spots as $row): ?>
                <div class="spot-card">
                    <h4><?= htmlspecialchars($row['location']) ?></h4>
                    <p><b>Area:</b> <?= htmlspecialchars($row['zone']) ?></p>
                    <p><b>Price:</b> <?= $row['price_per_hour'] ?> EGP</p>
                    <p><b>Status:</b> <?= $row['status'] ?></p>
                    <a href="delete_spot_handler.php?id=<?= $row['id'] ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Delete this spot?')">Delete</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning">No spots found. Click "Add New Spot" in dashboard.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>