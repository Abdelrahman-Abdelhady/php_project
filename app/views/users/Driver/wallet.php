<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - My Wallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 30px 0;
        }

        .wallet-card {
            width: 100%;
            max-width: 480px;
            margin: auto;
            background: white;
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .balance-box {
            background: #4F5D95;
            border-radius: 14px;
            padding: 28px 20px;
            text-align: center;
            margin-bottom: 28px;
        }

        .balance-label {
            color: rgba(255,255,255,0.75);
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .balance-amount {
            color: white;
            font-size: 2.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .section-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 0.95rem;
            border: 1.5px solid #ddd;
        }

        .form-control:focus {
            border-color: #4F5D95;
            box-shadow: 0 0 0 3px rgba(79,93,149,0.15);
        }

        .btn-add {
            background: #4F5D95;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            margin-top: 12px;
            transition: background 0.2s;
        }

        .btn-add:hover {
            background: #3d4a7a;
        }

        .nav-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 22px;
        }

        .btn-nav {
            display: block;
            width: 100%;
            text-align: center;
            padding: 11px;
            border-radius: 25px;
            font-size: 0.9rem;
            border: 1.5px solid #ccc;
            color: #444;
            text-decoration: none;
            transition: all 0.2s;
            background: white;
        }

        .btn-nav:hover {
            border-color: #4F5D95;
            color: #4F5D95;
        }

        .alert {
            border-radius: 12px;
            font-size: 0.88rem;
            padding: 10px 16px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>

<div class="wallet-card">

    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color:#4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted mb-0">My Wallet</p>
    </div>

    <!-- Success / Error alerts -->
    <?php if (!empty($_SESSION['wallet_success'])): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($_SESSION['wallet_success']) ?></div>
        <?php unset($_SESSION['wallet_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['wallet_error'])): ?>
        <div class="alert alert-danger">⚠️ <?= htmlspecialchars($_SESSION['wallet_error']) ?></div>
        <?php unset($_SESSION['wallet_error']); ?>
    <?php endif; ?>

    <!-- Balance -->
    <div class="balance-box">
        <div class="balance-label">Current Balance</div>
        <div class="balance-amount">EGP <?= number_format($balance, 2) ?></div>
    </div>

    <!-- Add Funds Form -->
    <div class="section-label">Add Funds</div>
    <form action="<?= BASE_URL ?>Wallet/addFunds" method="POST">
        <input
            type="number"
            name="amount"
            class="form-control"
            placeholder="Enter amount"
            min="1"
            step="0.01"
            required
        >
        <button type="submit" class="btn-add">+ Add Funds</button>
    </form>

    <!-- Navigation -->
    <div class="nav-buttons">
        <a href="<?= BASE_URL ?>Transaction/index" class="btn-nav">📋 View Transactions</a>
        <a href="<?= BASE_URL ?>Request/index"     class="btn-nav">📥 My Requests</a>
        <a href="<?= BASE_URL ?>Home/index"        class="btn-nav">← Back to Home</a>
    </div>

</div>

</body>
</html>