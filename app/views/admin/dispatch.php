<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../../simple_login.php");
    exit;
}

require_once "../../Models/ReservationModel.php";

$reservationModel = new ReservationModel();
$violations = $reservationModel->getOverstayViolations();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dispatch'])) {
    $_SESSION['message'] = "🚔 Officer dispatched successfully";
    header('Location: dispatch.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enforcement Dispatch - CitySlot Admin</title>
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
        table { width: 100%; border-collapse: collapse; }
        th { background: #111827; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .badge-danger { background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: #4F46E5;
            color: white;
            font-weight: bold;
        }
        .btn:hover { background: #4338ca; }
        .alert-success { background: #dcfce7; color: #166534; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🚘 CitySlot</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="verify_owner.php">Owner Verification</a>
        <a href="dispatch.php" class="active">Enforcement Dispatch</a>
        <a href="event_zone.php">Event Zone Lock</a>
        <a href="emergency.php">Emergency Override</a>
        <a href="fines.php">Fines & Appeals</a>
        <a href="blacklist.php">Blacklist</a>
        <a href="system_health.php">System Health</a>
        <a href="settings.php">Settings</a>
        <a href="../../simple_logout.php" style="margin-top: 50px;">🚪 Logout</a>
    </div>
    <div class="main">
        <div class="navbar">
            <h1>🚔 Enforcement Officer Dispatch</h1>
        </div>
        <div class="content">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            
            <div class="section">
                <h2>⚠️ Overstay Violations (>30 mins)</h2>
                <?php if(count($violations) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Driver Name</th>
                                <th>Location</th>
                                <th>Zone</th>
                                <th>End Time</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($violations as $v): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($v['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($v['location']); ?></td>
                                <td><?php echo htmlspecialchars($v['zone']); ?></td>
                                <td><?php echo $v['endTime']; ?></td>
                                <td><?php echo htmlspecialchars($v['phone_num']); ?></td>
                                <td>
                                    <form method="POST">
                                        <button type="submit" name="dispatch" class="btn">🚔 Dispatch Officer</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p style="padding: 20px; text-align: center; color: #6b7280;"> No overstay violations at this time.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>