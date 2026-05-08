<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../../simple_login.php");
    exit;
}

require_once "../../Models/SpotModel.php";

$spotModel = new SpotModel();
$zones = $spotModel->getZones();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zone = $_POST['zone'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];
    
    $spotModel->lockZone($zone);
    $_SESSION['message'] = "🔒 Zone '{$zone}' locked for event on {$date} for {$duration} hours";
    header('Location: event_zone.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Zone Lock - CitySlot Admin</title>
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .content { padding: 30px; }
        .section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .section h2 {
            margin-bottom: 20px;
            border-bottom: 2px solid #eef1f5;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }
        .form-group select, .form-group input {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }
        .btn {
            padding: 10px 20px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn:hover { background: #dc2626; }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .zone-list {
            margin-top: 20px;
            padding: 15px;
            background: #f3f4f6;
            border-radius: 8px;
        }
        .zone-tag {
            display: inline-block;
            background: #4F46E5;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            margin: 5px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🚘 CitySlot</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="verify_owner.php">Owner Verification</a>
        <a href="dispatch.php">Enforcement Dispatch</a>
        <a href="event_zone.php" class="active">Event Zone Lock</a>
        <a href="emergency.php">Emergency Override</a>
        <a href="fines.php">Fines & Appeals</a>
        <a href="blacklist.php">Blacklist</a>
        <a href="system_health.php">System Health</a>
        <a href="settings.php">Settings</a>
        <a href="../../simple_logout.php" style="margin-top: 50px;">🚪 Logout</a>
    </div>
    <div class="main">
        <div class="navbar">
            <h1>🔒 Event-Zone Locking</h1>
        </div>
        <div class="content">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            
            <div class="section">
                <h2>📍 Available Zones</h2>
                <div class="zone-list">
                    <?php foreach($zones as $zone): ?>
                        <span class="zone-tag">📍 <?php echo htmlspecialchars($zone['zone']); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="section">
                <h2>🔐 Lock Zone for Event</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Select Zone</label>
                        <select name="zone" required>
                            <option value="">-- Choose zone --</option>
                            <?php foreach($zones as $zone): ?>
                            <option value="<?php echo $zone['zone']; ?>"><?php echo htmlspecialchars($zone['zone']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Event Date</label>
                        <input type="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label>Duration (hours)</label>
                        <input type="number" name="duration" value="4" min="1" max="24" required>
                    </div>
                    <button type="submit" class="btn">🔒 Initiate Lockdown</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>