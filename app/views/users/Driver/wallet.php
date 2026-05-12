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
            max-width: 450px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .balance-display {
            background: #4F5D95;
            color: white;
            border-radius: 12px;
            padding: 25px 20px;
            text-align: center;
            margin-bottom: 25px;
        }

        .balance-display .amount {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .btn-primary {
            background: #4F5D95;
            border: none;
            border-radius: 25px;
            padding: 10px;
            width: 100%;
        }

        .btn-primary:hover { background: #3b4675; }

        .btn-outline-secondary {
            border-radius: 25px;
            padding: 8px;
            width: 100%;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .divider { border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>

<div class="wallet-card">

    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted">My Wallet</p>
    </div>

    <div class="balance-display">
        <div class="label text-uppercase small opacity-75">Current Balance</div>
        <div class="amount">
            <?= htmlspecialchars($wallet['currency'] ?? 'EGP') ?>
            <?= number_format($wallet['balance'] ?? 0, 2) ?>
        </div>
    </div>

    <form action="<?= BASE_URL ?>Wallet/doTopUp" method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold">Add Funds</label>
            <input
                type="number"
                name="amount"
                class="form-control"
                placeholder="Enter amount"
                min="1"
                step="0.01"
                required
            >
        </div>
        <button type="submit" class="btn btn-primary shadow">+ Add Funds</button>
    </form>

    <div class="divider"></div>

    <div class="text-center d-flex flex-column gap-2">
        <a href="<?= BASE_URL ?>Transaction/index" class="btn btn-outline-secondary btn-sm">📋 View Transactions</a>
        <a href="<?= BASE_URL ?>Request/index" class="btn btn-outline-secondary btn-sm">📩 My Requests</a>
        
        <?php if (Auth::role('municipal_admin')): ?>
            <a href="<?= BASE_URL ?>Admin/dashboard" class="btn btn-outline-dark btn-sm">← Back to Dashboard</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>Home/index" class="btn btn-outline-secondary btn-sm">← Back to Home</a>
        <?php endif; ?>
    </div>

</div>

</body>
</html>