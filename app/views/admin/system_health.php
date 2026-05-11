<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../../simple_login.php");
    exit;
}

require_once "../../Models/SensorModel.php";

$sensorModel = new SensorModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_sensor'])) {
    $sensorID = $_POST['sensorID'];
    $sensorModel->resetSensor($sensorID);
    $_SESSION['message'] = "🔄 Sensor #{$sensorID} has been reset successfully";
    header('Location: system_health.php');
    exit();
}

$sensors = $sensorModel->getAllSensors();
$stats = $sensorModel->getSensorStats();
$inactiveSensors = $sensorModel->getInactiveSensors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Health - CitySlot Admin</title>
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
            transition: 0.3s;
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .navbar h1 { color: #111827; }
        .content { padding: 30px; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
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
            border-bottom: 2px solid #eef1f5;
            padding-bottom: 10px;
            color: #111827;
        }
        table { width: 100%; border-collapse: collapse; }
        th { background: #111827; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            color: white;
            background: #4F46E5;
        }
        .btn:hover { background: #4338ca; }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .status-online {
            color: #10b981;
            font-weight: bold;
        }
        .status-offline {
            color: #ef4444;
            font-weight: bold;
        }
        .pulse {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #10b981;
            margin-right: 5px;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(2); opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🚘 CitySlot</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="verify_owner.php">Owner Verification</a>
        <a href="dispatch.php">Enforcement Dispatch</a>
        <a href="event_zone.php">Event Zone Lock</a>
        <a href="emergency.php">Emergency Override</a>
        <a href="fines.php">Fines & Appeals</a>
        <a href="blacklist.php">Blacklist</a>
        <a href="system_health.php" class="active">System Health</a>
        <a href="settings.php">Settings</a>
        <a href="../../simple_logout.php" style="margin-top: 50px;">🚪 Logout</a>
    </div>
    <div class="main">
        <div class="navbar">
            <h1> IoT System Health Monitor</h1>
        </div>
        <div class="content">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3> Total Sensors</h3>
                    <div class="number"><?php echo $stats['total']; ?></div>
                </div>
                <div class="stat-card">
                    <h3> Active Sensors</h3>
                    <div class="number"><?php echo $stats['active']; ?></div>
                </div>
                <div class="stat-card">
                    <h3> Online Now</h3>
                    <div class="number"><?php echo $stats['online']; ?></div>
                </div>
                <div class="stat-card">
                    <h3> Inactive</h3>
                    <div class="number"><?php echo $stats['inactive']; ?></div>
                </div>
            </div>
            
            <div class="section">
                <h2>🔧 Sensor Status Details</h2>
                <?php if(count($sensors) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Sensor ID</th>
                                <th>Location</th>
                                <th>Zone</th>
                                <th>Last Heartbeat</th>
                                <th>Status</th>
                                <th>Occupied</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($sensors as $s): ?>
                            <?php
                                $isOnline = $s['last_Heartbeat'] && strtotime($s['last_Heartbeat']) > strtotime('-5 minutes');
                                $statusClass = $isOnline ? 'status-online' : 'status-offline';
                                $statusText = $isOnline ? '🟢 Online' : '🔴 Offline';
                                $lastHeartbeat = $s['last_Heartbeat'] ?? 'Never';
                            ?>
                            <tr>
                                <td><strong>#<?php echo $s['sensorID']; ?></strong></td>
                                <td><?php echo htmlspecialchars($s['location'] ?? 'Not assigned'); ?></td>
                                <td><?php echo htmlspecialchars($s['zone'] ?? 'N/A'); ?></td>
                                <td><?php echo $lastHeartbeat; ?></td>
                                <td><span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                                <td><?php echo $s['isOccupied'] ? ' Occupied' : 'Free'; ?></td>
                                <td>
                                    <?php if(!$isOnline): ?>
                                    <form method="POST">
                                        <input type="hidden" name="sensorID" value="<?php echo $s['sensorID']; ?>">
                                        <button type="submit" name="reset_sensor" class="btn">🔄 Reset</button>
                                    </form>
                                    <?php else: ?>
                                    <span style="color: #10b981;">✓ Operational</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p style="padding: 20px; text-align: center; color: #6b7280;"> No sensors found in the system.</p>
                <?php endif; ?>
            </div>

            <?php if(count($inactiveSensors) > 0): ?>
            <div class="section">
                <h2>Offline Sensors Alert</h2>
                <div class="alert" style="background: #fee2e2; padding: 15px; border-radius: 8px;">
                    <p><strong><?php echo count($inactiveSensors); ?> sensor(s) are offline:</strong></p>
                    <ul>
                        <?php foreach($inactiveSensors as $s): ?>
                        <li>Sensor #<?php echo $s['sensorID']; ?> - Last heartbeat: <?php echo $s['last_Heartbeat'] ?? 'Never'; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>