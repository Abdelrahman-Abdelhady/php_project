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

        .balance-display .label {
            font-size: 0.85rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .balance-display .amount {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .balance-display .last-updated {
            font-size: 0.78rem;
            opacity: 0.7;
            margin-top: 6px;
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
        }

        .error-text {
            color: #d93025;
            font-size: 0.85rem;
            margin-top: 4px;
            display: block;
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

    <!-- Balance -->
    <div class="balance-display">
        <div class="label">Current Balance</div>
        <div class="amount">
            <?= htmlspecialchars($wallet['currency'] ?? 'EGP') ?>
            <?= number_format($wallet['balance'] ?? 0, 2) ?>
        </div>
        <?php if (!empty($wallet['lastUpdated'])): ?>
            <div class="last-updated">
                Last updated: <?= htmlspecialchars($wallet['lastUpdated']) ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Error -->
    <?php if (!empty($errors['topup'])): ?>
        <div class="alert alert-danger py-2 text-center" style="border-radius: 10px; font-size: 0.9rem;">
            <?= htmlspecialchars($errors['topup']) ?>
        </div>
    <?php endif; ?>

    <!-- Top-Up Form — always visible -->
    <form action="<?= BASE_URL ?>Wallet/doTopUp" method="POST">

        <div class="mb-3">
            <label class="form-label fw-semibold">Add Funds (<?= htmlspecialchars($wallet['currency'] ?? 'EGP') ?>)</label>
            <input
                type="number"
                name="amount"
                class="form-control"
                placeholder="Enter amount e.g. 100"
                min="1"
                step="0.01"
                value="<?= htmlspecialchars($old['amount'] ?? '') ?>"
            >
            <?php if (!empty($errors['amount'])): ?>
                <small class="error-text"><?= htmlspecialchars($errors['amount']) ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary shadow">+ Add Funds</button>

    </form>

    <div class="divider"></div>

    <div class="text-center">
        <a href="<?= BASE_URL ?>Home/index" class="btn btn-outline-secondary btn-sm">← Back to Home</a>
    </div>

</div>

</body>
</html>