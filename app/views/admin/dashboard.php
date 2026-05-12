<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CitySlot - Admin Dashboard</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #eef1f5;
            display: flex;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #111827;
            color: white;
            padding: 25px 20px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar a:hover {
            background: #1f2937;
            color: white;
            border-left: 4px solid #4F46E5;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
        }

        .navbar {
            background: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .content {
            padding: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #4F46E5;
        }

        .stat-card h3 {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #111827;
        }

        .admin-badge {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 5px 12px;
            border-radius: 20px;
        }
    </style>
</head>

<body>

<?php require_once "../app/views/admin/sidebar.php"; ?>

<div class="main">
    <div class="navbar">
        <h1>Municipal Admin Dashboard</h1>

        <span class="admin-badge">
            👤 <?= htmlspecialchars(Auth::user()['name'] ?? 'Admin') ?>
        </span>
    </div>

    <div class="content">
        <div class="stats-grid">

            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?= htmlspecialchars($stats['total_users'] ?? 0) ?></div>
            </div>

            <div class="stat-card">
                <h3>Total Drivers</h3>
                <div class="number"><?= htmlspecialchars($stats['total_drivers'] ?? 0) ?></div>
            </div>

            <div class="stat-card">
                <h3>Total Owners</h3>
                <div class="number"><?= htmlspecialchars($stats['total_owners'] ?? 0) ?></div>
            </div>

            <div class="stat-card">
                <h3>Total Parking Spots</h3>
                <div class="number"><?= htmlspecialchars($stats['total_spots'] ?? 0) ?></div>
            </div>

            <div class="stat-card">
                <h3>Occupied Spots</h3>
                <div class="number"><?= htmlspecialchars($stats['occupied_spots'] ?? 0) ?></div>
            </div>

            <div class="stat-card">
                <h3>Available Spots</h3>
                <div class="number"><?= htmlspecialchars($stats['available_spots'] ?? 0) ?></div>
            </div>

            <div class="stat-card">
                <h3>Active Violations</h3>
                <div class="number"><?= htmlspecialchars($stats['active_violations'] ?? 0) ?></div>
            </div>

            <div class="stat-card">
                <h3>Unpaid Fines</h3>
                <div class="number"><?= htmlspecialchars($stats['unpaid_fines'] ?? 0) ?></div>
            </div>

        </div>
    </div>
</div>

</body>
</html>