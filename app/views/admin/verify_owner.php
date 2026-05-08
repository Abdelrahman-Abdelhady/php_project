<?php
session_start();

// تأكد من أن المستخدم Admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../../simple_login.php");
    exit;
}

require_once "../../Models/SpotModel.php";

$spotModel = new SpotModel();
$pendingSpots = $spotModel->getPendingSpots();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $spotID = $_POST['spotID'];
    $status = $_POST['status'];
    
    if ($spotModel->updateSpotStatus($spotID, $status)) {
        $_SESSION['message'] = "Owner verification updated successfully";
    }
    header('Location: verify_owner.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Verification - CitySlot Admin</title>
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
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            color: white;
            margin: 0 3px;
        }
        .btn-approve { background: #10b981; }
        .btn-reject { background: #ef4444; }
        .btn-approve:hover { background: #059669; }
        .btn-reject:hover { background: #dc2626; }
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
        .badge-warning { background: #fef9c3; color: #854d0e; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🚘 CitySlot</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="verify_owner.php" class="active">Owner Verification</a>
        <a href="dispatch.php">Enforcement Dispatch</a>
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
            <h1>Owner Verification Workflow</h1>
        </div>
        <div class="content">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            
            <div class="section">
                <h2>📋 Pending Owner Verification Requests</h2>
                <?php if(count($pendingSpots) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Owner Name</th>
                                <th>Location</th>
                                <th>Zone</th>
                                <th>Price/Hour</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pendingSpots as $spot): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($spot['owner_name']); ?></td>
                                <td><?php echo htmlspecialchars($spot['location']); ?></td>
                                <td><?php echo htmlspecialchars($spot['zone']); ?></td>
                                <td>$<?php echo number_format($spot['price_per_hour'], 2); ?></td>
                                <td><?php echo htmlspecialchars($spot['email']); ?><br><?php echo htmlspecialchars($spot['phone_num']); ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="spotID" value="<?php echo $spot['spotID']; ?>">
                                        <input type="hidden" name="status" value="available">
                                        <button type="submit" class="btn btn-approve">✓ Approve</button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="spotID" value="<?php echo $spot['spotID']; ?>">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-reject">✗ Reject</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p style="padding: 20px; text-align: center; color: #6b7280;">No pending verification requests.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>