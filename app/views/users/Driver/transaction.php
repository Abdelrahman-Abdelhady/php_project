<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 30px 0;
        }

        .tx-card {
            width: 100%;
            max-width: 550px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .tx-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 10px;
            background: #f8f9ff;
            border-radius: 10px;
            font-size: 0.88rem;
        }

        .tx-type {
            font-weight: 600;
            text-transform: capitalize;
        }

        .tx-date {
            font-size: 0.78rem;
            color: #999;
            margin-top: 3px;
        }

        .tx-amount {
            font-weight: 700;
            font-size: 0.95rem;
        }

        .credit  { color: #2e7d32; }
        .debit   { color: #c62828; }

        .badge-topup   { background: #e8f5e9; color: #2e7d32; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; }
        .badge-payment { background: #ffebee; color: #c62828; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; }
        .badge-refund  { background: #e3f2fd; color: #1565c0; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; }

        .btn-outline-secondary {
            border-radius: 25px;
            padding: 8px 20px;
            font-size: 0.9rem;
        }

        .empty-state {
            text-align: center;
            color: #aaa;
            padding: 30px 0;
        }
    </style>
</head>
<body>

<div class="tx-card">

    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted">Transaction History</p>
    </div>

    <?php if (!empty($transactions)): ?>
        <div style="max-height: 450px; overflow-y: auto;">
            <?php foreach ($transactions as $tx): ?>
                <div class="tx-row">
                    <div>
                        <div>
                            <?php if ($tx['type'] === 'topup'): ?>
                                <span class="badge-topup">🟢 Top-up</span>
                            <?php elseif ($tx['type'] === 'payment'): ?>
                                <span class="badge-payment">🔴 Payment</span>
                            <?php else: ?>
                                <span class="badge-refund">🔵 Refund</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($tx['description'])): ?>
                            <div class="text-muted mt-1"><?= htmlspecialchars($tx['description']) ?></div>
                        <?php endif; ?>
                        <div class="tx-date"><?= htmlspecialchars($tx['created_at']) ?></div>
                    </div>
                    <div class="tx-amount <?= $tx['type'] === 'payment' ? 'debit' : 'credit' ?>">
                        <?= $tx['type'] === 'payment' ? '-' : '+' ?>
                        EGP <?= number_format($tx['amount'], 2) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>💳 No transactions yet.</p>
        </div>
    <?php endif; ?>

    <div style="border-top: 1px solid #eee; margin-top: 20px; padding-top: 15px;" class="text-center">
        <a href="<?= BASE_URL ?>Wallet/index" class="btn btn-outline-secondary me-2">← My Wallet</a>
        <a href="<?= BASE_URL ?>Home/index" class="btn btn-outline-secondary">🏠 Home</a>
    </div>

</div>

</body>
</html>