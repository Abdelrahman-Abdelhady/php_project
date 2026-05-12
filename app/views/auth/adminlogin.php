<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .btn-primary {
            background: #4F5D95;
            border: none;
            border-radius: 25px;
            padding: 10px;
            width: 100%;
        }

        .btn-primary:hover {
            background: #3b4675;
        }

        .admin-badge {
            background: #4F5D95;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .error-text {
            color: #d93025;
            font-size: 0.85rem;
            margin-top: 4px;
            display: block;
        }
    </style>
</head>

<body>

<div class="login-card">
    <div class="text-center mb-4">
        <span class="admin-badge">🔐 Admin Portal</span>
        <h2 class="fw-bold" style="color: #4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted">Welcome back Admin! Please login</p>
    </div>

    <?php if (!empty($errors['login'])): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($errors['login']) ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>Auth/doAdminLogin" method="POST">
        <div class="mb-3">
            <label class="form-label">Email Address</label>

            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="admin@cityslot.com"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            >

            <?php if (!empty($errors['email'])): ?>
                <small class="error-text"><?= htmlspecialchars($errors['email']) ?></small>
            <?php endif; ?>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Enter password"
            >

            <?php if (!empty($errors['password'])): ?>
                <small class="error-text"><?= htmlspecialchars($errors['password']) ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary shadow">Admin Login</button>
    </form>

    <div class="mt-4 text-center">
        <a href="<?= BASE_URL ?>Auth/login" class="text-decoration-none fw-bold" style="color: #4F5D95;">
            User Login
        </a>
    </div>
</div>

</body>
</html>