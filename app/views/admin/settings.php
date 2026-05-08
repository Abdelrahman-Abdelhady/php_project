<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../../simple_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['grace_period'] = $_POST['grace_period'];
    $_SESSION['buffer_time'] = $_POST['buffer_time'];
    $_SESSION['overstay_fine_rate'] = $_POST['overstay_fine_rate'];
    $_SESSION['admin_name'] = $_POST['admin_name'];
    $_SESSION['admin_email'] = $_POST['admin_email'];

    $_SESSION['message'] = "💾 Settings saved successfully";
    header('Location: settings.php');
    exit();
}

$settings = [
    'grace_period' => $_SESSION['grace_period'] ?? 5,
    'buffer_time' => $_SESSION['buffer_time'] ?? 10,
    'overstay_fine_rate' => $_SESSION['overstay_fine_rate'] ?? 2.5,
    'admin_name' => $_SESSION['admin_name'] ?? $_SESSION['user'],
    'admin_email' => $_SESSION['admin_email'] ?? 'admin@cityslot.gov.eg'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Settings - CitySlot Admin</title>
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
        .content {
            padding: 30px;
            max-width: 800px;
        }
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #4F46E5;
        }
        .form-group .hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
        .btn-save {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
        }
        .btn-save:hover {
            background: #4338ca;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
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
        <a href="system_health.php">System Health</a>
        <a href="settings.php" class="active">Settings</a>
        <a href="../../simple_logout.php" style="margin-top: 50px;">🚪 Logout</a>
    </div>
    <div class="main">
        <div class="navbar">
            <h1>⚙️ System Settings</h1>
        </div>
        <div class="content">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="section">
                    <h2>👤 Admin Profile</h2>
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="admin_name" value="<?php echo htmlspecialchars($settings['admin_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email']); ?>" required>
                    </div>
                </div>

                <div class="section">
                    <h2>🅿️ Global Parking Variables</h2>
                    <div class="form-group">
                        <label>Grace Period (Minutes)</label>
                        <input type="number" name="grace_period" value="<?php echo $settings['grace_period']; ?>" min="0" max="30" required>
                        <div class="hint">⏰ Time allowed before marking as "No-Show" or calculating overstay fine.</div>
                    </div>

                    <div class="form-group">
                        <label>Buffer-Time Management (Minutes)</label>
                        <input type="number" name="buffer_time" value="<?php echo $settings['buffer_time']; ?>" min="0" max="30" required>
                        <div class="hint">🔄 Cool-down time between consecutive bookings to prevent overlaps.</div>
                    </div>

                    <div class="form-group">
                        <label>Overstay Fine Rate ($ per hour)</label>
                        <input type="number" step="0.5" name="overstay_fine_rate" value="<?php echo $settings['overstay_fine_rate']; ?>" min="0" required>
                        <div class="hint">💰 Hourly rate charged for overstay violations.</div>
                    </div>
                </div>

                <div class="section">
                    <h2>🔐 Security</h2>
                    <div class="form-group">
                        <label>Change Password</label>
                        <input type="password" name="new_password" placeholder="Leave empty to keep current password">
                        <div class="hint">🔒 Enter new password to change your login credentials.</div>
                    </div>
                </div>

                <button type="submit" class="btn-save">💾 Save All Settings</button>
            </form>
        </div>
    </div>
</body>
</html>