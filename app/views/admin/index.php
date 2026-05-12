<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Driver Portal</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .driver-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s;
        }
        .driver-card:hover {
            transform: translateY(-5px);
        }
        .icon-box {
            font-size: 2rem;
            color: #4F5D95;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>Home/index">CitySlot 🚘</a>
        <div class="ms-auto">
            <span class="text-light me-3">Welcome, <?= htmlspecialchars(Auth::user()['name']) ?></span>
            <a href="<?= BASE_URL ?>Auth/logout" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Driver Control Panel</h2>
        <p class="text-muted">Manage your wallet and parking requests</p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="card driver-card shadow-sm h-100 text-center p-4">
                <div class="icon-box">💰</div>
                <h4>My Wallet</h4>
                <p class="text-muted">Check your balance and top up funds.</p>
                <a href="<?= BASE_URL ?>Wallet/index" class="btn btn-primary mt-auto">View Wallet</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card driver-card shadow-sm h-100 text-center p-4">
                <div class="icon-box">📩</div>
                <h4>My Requests</h4>
                <p class="text-muted">Track your parking and service requests.</p>
                <a href="<?= BASE_URL ?>Request/index" class="btn btn-primary mt-auto">View Requests</a>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <?php if (Auth::role('municipal_admin')): ?>
            <a href="<?= BASE_URL ?>Admin/dashboard" class="btn btn-outline-secondary">
                ← Back to Dashboard
            </a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>Home/index" class="btn btn-outline-secondary">
                ← Back to Home
            </a>
        <?php endif; ?>
    </div>
</div>

<script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>