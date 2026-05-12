<?php



// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../auth/adminlogin.php");
    exit;
}

require_once "../../models/SpotModel.php";
require_once "../../models/ReservationModel.php";
require_once "../../models/SensorModel.php";
$spotModel = new SpotModel();
$reservationModel = new ReservationModel();
$sensorModel = new SensorModel();

$totalSpots = $spotModel->getTotalSpots();
$occupiedSpots = $spotModel->getOccupiedSpots();
$violations = $reservationModel->getOverstayViolations();
$recentReservations = $reservationModel->getRecentReservations(10);
$sensors = $sensorModel->getAllSensors();
$stats = $sensorModel->getSensorStats();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CitySlot - Admin Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #eef1f5; display: flex; }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #111827;
            color: white;
            padding: 25px 20px;
            position: fixed;
        }
        .sidebar h2 { text-align: center; margin-bottom: 30px; }
        .sidebar a {
            display: block;
            color: #d1d5db;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #1f2937;
            color: white;
            border-left: 4px solid #4F46E5;
        }
        .main { margin-left: 260px; width: calc(100% - 260px); }
        .navbar {
            background: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .content { padding: 30px; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #4F46E5;
        }
        .stat-card h3 { color: #6b7280; font-size: 14px; margin-bottom: 10px; }
        .stat-card .number { font-size: 32px; font-weight: bold; color: #111827; }
        .section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .section h2 {
            margin-bottom: 20px;
            font-size: 18px;
            border-bottom: 2px solid #eef1f5;
            padding-bottom: 10px;
        }
        table { width: 100%; border-collapse: collapse; }
        th { background: #111827; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .btn {
            padding: 6px 12px;
            background: #4F46E5;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }
        .btn:hover { background: #4338ca; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🚘 CitySlot</h2>
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="verify_owner.php">Owner Verification</a>
        <a href="dispatch.php">Enforcement Dispatch</a>
        <a href="event_zone.php">Event Zone Lock</a>
        <a href="emergency.php">Emergency Override</a>
        <a href="fines.php">Fines & Appeals</a>
        <a href="blacklist.php">Blacklist</a>
        <a href="system_health.php">System Health</a>
        <a href="settings.php">Settings</a>
        <a href="../../auth/logout.php" style="margin-top: 50px;">🚪 Logout</a>
    </div>
    <div class="main">
        <div class="navbar">
            <h1>Municipal Admin Dashboard</h1>
            <span style="background:#dbeafe; color:#1d4ed8; padding:5px 12px; border-radius:20px;">👤 <?php echo $_SESSION['user']; ?></span>
        </div>
        <div class="content">
            <div class="stats-grid">
                <div class="stat-card"><h3>Total Parking Spots</h3><div class="number"><?php echo $totalSpots; ?></div></div>
                <div class="stat-card"><h3>Occupied Spots</h3><div class="number"><?php echo $occupiedSpots; ?></div></div>
                <div class="stat-card"><h3>Active Violations</h3><div class="number"><?php echo count($violations); ?></div></div>
                <div class="stat-card"><h3>Active Sensors</h3><div class="number"><?php echo $stats['active']; ?></div></div>
            </div>
            
            <div class="section">
                <h2>⚠️ Active Violations (Overstay > 30 min)</h2>
                <?php if(count($violations) > 0): ?>
                <table>
                    <thead><tr><th>User</th><th>Spot</th><th>End Time</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php foreach($violations as $v): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($v['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($v['location']); ?></td>
                        <td><?php echo $v['endTime']; ?></td>
                        <td><a href="dispatch.php" class="btn">🚔 Dispatch</a></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No active violations.</p>
                <?php endif; ?>
            </div>
            
            <div class="section">
                <h2>📋 Recent Reservations</h2>
                <table>
                    <thead><tr><th>ID</th><th>User</th><th>Spot</th><th>Start</th><th>End</th><th>Status</th></table></thead>
                    <tbody>
                    <?php foreach($recentReservations as $r): ?>
                    <tr>
                        <td>#<?php echo $r['reservationID']; ?></td>
                        <td><?php echo htmlspecialchars($r['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($r['spot_location']); ?></td>
                        <td><?php echo $r['startTime']; ?></td>
                        <td><?php echo $r['endTime']; ?></td>
                        <td><span class="badge <?php echo $r['status'] == 'active' ? 'badge-success' : 'badge-warning'; ?>"><?php echo $r['status']; ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>