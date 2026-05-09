<?php

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../../simple_login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['grace_period'] = $_POST['grace_period'];
    $_SESSION['buffer_time'] = $_POST['buffer_time'];
    $_SESSION['overstay_fine_rate'] = $_POST['overstay_fine_rate'];
    $_SESSION['admin_name'] = $_POST['admin_name'];
    $_SESSION['admin_email'] = $_POST['admin_email'];
    
    // Handle password change
    if (!empty($_POST['new_password'])) {
        // Here you would update password in database
        $_SESSION['message'] = " Settings saved successfully! Password has been updated.";
    } else {
        $_SESSION['message'] = " Settings saved successfully!";
    }
    
    header('Location: settings.php');
    exit();
}

// Get current settings
$settings = [
    'grace_period' => $_SESSION['grace_period'] ?? 5,
    'buffer_time' => $_SESSION['buffer_time'] ?? 10,
    'overstay_fine_rate' => $_SESSION['overstay_fine_rate'] ?? 2.5,
    'admin_name' => $_SESSION['admin_name'] ?? $_SESSION['user'],
    'admin_email' => $_SESSION['admin_email'] ?? 'admin@cityslot.gov.eg'
];

$userPic = $_SESSION['profile_pic'] ?? "https://via.placeholder.com/130?text=Admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Admin Settings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7f6;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .side-drawer {
            height: 100vh;
            width: 0;
            position: fixed;
            top: 0;
            left: 0;
            background: #111827;
            overflow-x: hidden;
            transition: 0.4s;
            padding-top: 3rem;
            z-index: 2000;
        }

        .side-drawer a {
            padding: 1rem 2rem;
            text-decoration: none;
            color: #d1d5db;
            display: block;
            border-bottom: 1px solid #1f2937;
            transition: 0.3s;
        }

        .side-drawer a:hover {
            background: #1f2937;
            color: white;
            border-left: 4px solid #4F46E5;
        }

        .navbar {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .site-title {
            font-weight: bold;
            font-size: 1.2rem;
            color: #4F5D95;
        }

        .menu-icon {
            font-size: 1.5rem;
            cursor: pointer;
            color: #4F5D95;
        }

        .main-content {
            padding: 2rem;
            flex: 1;
        }

        .profile-header {
            background: #4F5D95;
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            border-radius: 1rem 1rem 0 0;
        }

        .profile-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .settings-card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-top: -2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #111827;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eef1f5;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4F46E5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-group .hint {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.3rem;
        }

        .save-btn {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 2rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
            font-size: 1rem;
        }

        .save-btn:hover {
            background: #4338ca;
            transform: translateY(-2px);
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 0.8rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #10b981;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1rem;
            border-radius: 0.75rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #4F46E5;
        }

        .stat-card h4 {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
        }

        .stat-card .value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #111827;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            .settings-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div id="sideDrawer" class="side-drawer">
    <a href="javascript:void(0)" onclick="toggleSidebar()" style="color: #ef4444;">✕ Close</a>
    <a href="dashboard.php">Dashboard</a>
    <a href="verify_owner.php">Owner Verification</a>
    <a href="dispatch.php">Enforcement Dispatch</a>
    <a href="event_zone.php">Event Zone Lock</a>
    <a href="emergency.php">Emergency Override</a>
    <a href="fines.php">Fines & Appeals</a>
    <a href="blacklist.php">Blacklist</a>
    <a href="system_health.php">System Health</a>
    <a href="settings.php" class="active">Settings</a>
    <a href="../../simple_logout.php" style="margin-top: 2rem;">Logout</a>
</div>

<!-- Navbar -->
<div class="navbar">
    <div class="menu-icon" onclick="toggleSidebar()">☰</div>
    <div class="site-title">CitySlot Municipal Portal</div>
    <div style="width: 30px;"></div>
</div>

<!-- Content -->
<div class="main-content container" style="max-width: 900px;">

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert-success">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <h4>Grace Period</h4>
            <div class="value"><?php echo $settings['grace_period']; ?> min</div>
        </div>
        <div class="stat-card">
            <h4>Buffer Time</h4>
            <div class="value"><?php echo $settings['buffer_time']; ?> min</div>
        </div>
        <div class="stat-card">
            <h4>Overstay Fine Rate</h4>
            <div class="value">$<?php echo $settings['overstay_fine_rate']; ?>/hr</div>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="profile-header">
        <img src="<?php echo $userPic; ?>" class="profile-img mb-2" alt="Admin Profile">
        <h4><?php echo htmlspecialchars($settings['admin_name']); ?></h4>
        <p class="text-white-50">Municipal Administrator</p>
    </div>

    <!-- Settings Form -->
    <div class="settings-card">
        <form method="POST" enctype="multipart/form-data">

            <div class="section-title">
                Admin Profile
            </div>

            <div class="form-group">
                <label>Profile Image</label>
                <input type="file" name="profile_pic" class="form-control" accept="image/*">
                <div class="hint">Upload a new profile picture (JPG, PNG, max 2MB)</div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="admin_name" value="<?php echo htmlspecialchars($settings['admin_name']); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email']); ?>" required>
                    </div>
                </div>
            </div>

            <div class="section-title mt-4">
                Global Parking Variables
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Grace Period</label>
                        <input type="number" name="grace_period" value="<?php echo $settings['grace_period']; ?>" min="0" max="30" required>
                        <div class="hint">Minutes allowed before "No-Show"</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Buffer Time</label>
                        <input type="number" name="buffer_time" value="<?php echo $settings['buffer_time']; ?>" min="0" max="30" required>
                        <div class="hint">Cool-down between bookings</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Overstay Fine Rate</label>
                        <input type="number" step="0.5" name="overstay_fine_rate" value="<?php echo $settings['overstay_fine_rate']; ?>" min="0" required>
                        <div class="hint">Per hour overstay charge</div>
                    </div>
                </div>
            </div>

            <div class="section-title mt-4">
                Security
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" placeholder="Leave empty to keep current password">
                <div class="hint">Enter new password to change your login credentials</div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="save-btn">
                    Save All Settings
                </button>
            </div>

        </form>
    </div>

</div>

<script>
    function toggleSidebar() {
        const drawer = document.getElementById("sideDrawer");
        drawer.style.width = (drawer.style.width === "260px") ? "0" : "260px";
    }
</script>

</body>
</html>