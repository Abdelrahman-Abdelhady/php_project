<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../../simple_login.php");
    exit;
}

require_once "../../Models/UserModel.php";

$userModel = new UserModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID'])) {
    $userID = $_POST['userID'];
    $action = $_POST['action'];
    
    if ($action === 'blacklist') {
        $userModel->blacklistUser($userID);
        $_SESSION['message'] = "⛔ User #{$userID} has been blacklisted";
    } elseif ($action === 'remove') {
        $userModel->removeFromBlacklist($userID);
        $_SESSION['message'] = "✅ User #{$userID} removed from blacklist";
    }
    header('Location: blacklist.php');
    exit();
}

$usersWithViolations = $userModel->getUsersWithViolations();
$blacklistedUsers = $userModel->getBlacklistedUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blacklist Management - CitySlot Admin</title>
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
            margin-bottom: 25px;
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
        .btn-blacklist { background: #ef4444; }
        .btn-remove { background: #10b981; }
        .btn-blacklist:hover { background: #dc2626; }
        .btn-remove:hover { background: #059669; }
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
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-success { background: #dcfce7; color: #166534; }
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
        <a href="blacklist.php" class="active">Blacklist</a>
        <a href="system_health.php">System Health</a>
        <a href="settings.php">Settings</a>
        <a href="../../simple_logout.php" style="margin-top: 50px;">🚪 Logout</a>
    </div>
    <div class="main">
        <div class="navbar">
            <h1>⛔ Blacklist / Suspension Manager</h1>
        </div>
        <div class="content">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            
            <div class="section">
                <h2>⚠️ Users with Multiple Violations (>3 unpaid fines)</h2>
                <?php if(count($usersWithViolations) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Driver Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Violation Count</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($usersWithViolations as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['name']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['phone_num']); ?></td>
                                <td><span class="badge badge-danger"><?php echo $u['violation_count']; ?> violations</span></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="userID" value="<?php echo $u['userID']; ?>">
                                        <input type="hidden" name="action" value="blacklist">
                                        <button type="submit" class="btn btn-blacklist" onclick="return confirm('Blacklist this user?')">⛔ Blacklist</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p style="padding: 20px; text-align: center; color: #6b7280;">✅ No users with multiple violations.</p>
                <?php endif; ?>
            </div>
            
            <div class="section">
                <h2>🚫 Currently Blacklisted Users</h2>
                <?php if(count($blacklistedUsers) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Driver Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($blacklistedUsers as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['name']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo htmlspecialchars($u['phone_num']); ?></td>
                                <td><span class="badge badge-danger">Blacklisted</span></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="userID" value="<?php echo $u['userID']; ?>">
                                        <input type="hidden" name="action" value="remove">
                                        <button type="submit" class="btn btn-remove" onclick="return confirm('Remove this user from blacklist?')">✓ Remove</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p style="padding: 20px; text-align: center; color: #6b7280;">✅ No blacklisted users.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>