<?php

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'municipal_admin') {
    header("Location: ../../simple_login.php");
    exit;
}

require_once "../../Models/ReservationModel.php";

$reservationModel = new ReservationModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['violation_id'])) {
    $violationID = $_POST['violation_id'];
    $action = $_POST['action'];
    $amount = $_POST['amount'] ?? 0;
    
    if ($action === 'approve_fine') {
        $reservationModel->generateFine($violationID, $amount, "Overstay violation");
        $_SESSION['message'] = "💰 Fine of \${$amount} approved for violation #{$violationID}";
    } elseif ($action === 'reject_appeal') {
        $_SESSION['message'] = "❌ Appeal rejected for violation #{$violationID}";
    }
    header('Location: fines.php');
    exit();
}

$violations = $reservationModel->getViolationsForFines();
$allFines = $reservationModel->getAllFines();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fines & Appeals - CitySlot Admin</title>
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
        .btn-approve { background: #10b981; }
        .btn-reject { background: #ef4444; }
        .btn-pay { background: #4F46E5; }
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
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
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
        <a href="fines.php" class="active">Fines & Appeals</a>
        <a href="blacklist.php">Blacklist</a>
        <a href="system_health.php">System Health</a>
        <a href="settings.php">Settings</a>
        <a href="../../simple_logout.php" style="margin-top: 50px;">🚪 Logout</a>
    </div>
    <div class="main">
        <div class="navbar">
            <h1>💰 Automated Fines & Appeals Workflow</h1>
        </div>
        <div class="content">
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>
            
            <div class="section">
                <h2>⚠️ New Violations Requiring Action</h2>
                <?php if(count($violations) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Driver</th>
                                <th>Spot</th>
                                <th>Zone</th>
                                <th>End Time</th>
                                <th>Overstay</th>
                                <th>Fine Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($violations as $v): ?>
                            <?php 
                                $fineAmount = round($v['overstay_minutes'] / 60 * 10, 2);
                                if($fineAmount < 5) $fineAmount = 5;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($v['user_name']); ?><br><small><?php echo $v['email']; ?></small></td>
                                <td><?php echo htmlspecialchars($v['location']); ?></td>
                                <td><?php echo htmlspecialchars($v['zone']); ?></td>
                                <td><?php echo $v['endTime']; ?></td>
                                <td><span class="badge badge-danger"><?php echo $v['overstay_minutes']; ?> mins</span></td>
                                <td><strong>$$<?php echo number_format($fineAmount, 2); ?></strong></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="violation_id" value="<?php echo $v['reservationID']; ?>">
                                        <input type="hidden" name="action" value="approve_fine">
                                        <input type="hidden" name="amount" value="<?php echo $fineAmount; ?>">
                                        <button type="submit" class="btn btn-approve">✓ Approve Fine</button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="violation_id" value="<?php echo $v['reservationID']; ?>">
                                        <input type="hidden" name="action" value="reject_appeal">
                                        <button type="submit" class="btn btn-reject">✗ Reject Appeal</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p style="padding: 20px; text-align: center; color: #6b7280;">✅ No new violations requiring action.</p>
                <?php endif; ?>
            </div>

            <div class="section">
                <h2>📋 All Fines History</h2>
                <?php if(count($allFines) > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Fine ID</th>
                                <th>Driver</th>
                                <th>Location</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($allFines as $f): ?>
                            <tr>
                                <td>#<?php echo $f['fineID']; ?></td>
                                <td><?php echo htmlspecialchars($f['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($f['location']); ?></td>
                                <td>$$<?php echo number_format($f['amount'], 2); ?></td>
                                <td><span class="badge <?php echo $f['status'] == 'paid' ? 'badge-success' : 'badge-warning'; ?>"><?php echo $f['status']; ?></span></td>
                                <td><?php echo $f['generated_at']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p>No fines recorded yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>