<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Zone Lock</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #eef1f5; display: flex; margin: 0; }
        .sidebar { width: 260px; min-height: 100vh; background: #111827; color: white; padding: 25px 20px; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; }
        .sidebar a { display: block; color: #d1d5db; text-decoration: none; padding: 12px; border-radius: 8px; margin-bottom: 8px; }
        .sidebar a:hover { background: #1f2937; color: white; border-left: 4px solid #4F46E5; }
        .main { margin-left: 260px; padding: 30px; width: calc(100% - 260px); }
        .card { background: white; padding: 25px; border-radius: 12px; }
    </style>
</head>
<body>

<?php require_once "../app/views/admin/sidebar.php"; ?>

<div class="main">
    <div class="card">
        <h1>Event Zone Lock</h1>
        <p>Manage locked areas during events.</p>
    </div>
</div>

</body>
</html>