<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fines & Appeals</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #eef1f5; display: flex; margin: 0; }
        .sidebar { width: 260px; min-height: 100vh; background: #111827; color: white; padding: 25px 20px; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; }
        .sidebar a { display: block; color: #d1d5db; text-decoration: none; padding: 12px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar a:hover { background: #1f2937; color: white; border-left: 4px solid #4F46E5; }
        .main { margin-left: 260px; padding: 30px; width: calc(100% - 260px); }
        .card { background: white; padding: 25px; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #111827; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>

<?php require_once "../app/views/admin/sidebar.php"; ?>

<div class="main">
    <div class="card">
        <h1>Fines & Appeals</h1>

        <?php if (empty($driversWithFines)): ?>
            <p>No unpaid fines.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Unpaid Fines</th>
                </tr>

                <?php foreach ($driversWithFines as $driver): ?>
                    <tr>
                        <td><?= htmlspecialchars($driver['userID'] ?? '') ?></td>
                        <td><?= htmlspecialchars($driver['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($driver['email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($driver['unpaid_fines'] ?? 0) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>