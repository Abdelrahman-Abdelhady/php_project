<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Verification</title>

    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #eef1f5; display: flex; margin: 0; }
        .sidebar { width: 260px; min-height: 100vh; background: #111827; color: white; padding: 25px 20px; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; }
        .sidebar a { display: block; color: #d1d5db; text-decoration: none; padding: 12px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar a:hover { background: #1f2937; color: white; border-left: 4px solid #4F46E5; }
        .main { margin-left: 260px; padding: 30px; width: calc(100% - 260px); }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #111827; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        button { padding: 7px 12px; border: none; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>

<?php require_once "../app/views/admin/sidebar.php"; ?>

<div class="main">
    <div class="card">
        <h1>Owner Verification</h1>

        <?php if (!empty($_SESSION['message'])): ?>
            <p style="color: green;"><?= htmlspecialchars($_SESSION['message']) ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (empty($pendingSpots)): ?>
            <p>No pending spots.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Spot ID</th>
                    <th>Owner</th>
                    <th>Email</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($pendingSpots as $spot): ?>
                    <tr>
                        <td><?= htmlspecialchars($spot['spotID'] ?? '') ?></td>
                        <td><?= htmlspecialchars($spot['owner_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($spot['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($spot['location'] ?? '') ?></td>
                        <td><?= htmlspecialchars($spot['status'] ?? '') ?></td>
                        <td>
                            <form method="POST" action="<?= BASE_URL ?>Admin/verifyOwner" style="display:inline;">
                                <input type="hidden" name="spotID" value="<?= htmlspecialchars($spot['spotID'] ?? '') ?>">
                                <input type="hidden" name="status" value="available">
                                <button type="submit">Approve</button>
                            </form>

                            <form method="POST" action="<?= BASE_URL ?>Admin/verifyOwner" style="display:inline;">
                                <input type="hidden" name="spotID" value="<?= htmlspecialchars($spot['spotID'] ?? '') ?>">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>