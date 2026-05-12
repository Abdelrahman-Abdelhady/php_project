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
            max-width: 560px;
            margin: auto;
            background: white;
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .tx-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            margin-bottom: 10px;
            background: #f8f9ff;
            border-radius: 12px;
        }

        .tx-date {
            font-size: 0.78rem;
            color: #999;
            margin-top: 4px;
        }

        .tx-amount {
            font-weight: 700;
            font-size: 1rem;
            white-space: nowrap;
        }

        .credit { color: #2e7d32; }
        .debit  { color: #c62828; }
        .refund { color: #1565c0; }

        .badge-topup   { background: #e8f5e9; color: #2e7d32;  padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-payment { background: #ffebee; color: #c62828;  padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-refund  { background: #e3f2fd; color: #1565c0;  padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }

        .empty-state {
            text-align: center;
            color: #aaa;
            padding: 35px 0;
        }

        .btn-nav {
            display: inline-block;
            padding: 9px 22px;
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
    </style>
</head>
<body>

<div class="tx-card">

    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color:#4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted mb-0">Transaction History</p>
    </div>

    <?php if (!empty($transactions)): ?>
        <div style="max-height: 460px; overflow-y: auto; padding-right: 4px;">
            <?php foreach ($transactions as $tx): ?>
                <div class="tx-row">
                    <div>
                        <!-- Type badge -->
                        <?php if ($tx['type'] === 'topup'): ?>
                            <span class="badge-topup">🟢 Top-up</span>
                        <?php elseif ($tx['type'] === 'payment'): ?>
                            <span class="badge-payment">🔴 Payment</span>
                        <?php else: ?>
                            <span class="badge-refund">🔵 Refund</span>
                        <?php endif; ?>

                        <!-- Description -->
                        <?php if (!empty($tx['description'])): ?>
                            <div class="text-muted mt-1" style="font-size:0.83rem;">
                                <?= htmlspecialchars($tx['description']) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Date -->
                        <div class="tx-date"><?= htmlspecialchars($tx['created_at']) ?></div>
                    </div>

                    <!-- Amount -->
                    <div class="tx-amount <?= $tx['type'] === 'payment' ? 'debit' : ($tx['type'] === 'refund' ? 'refund' : 'credit') ?>">
                        <?= $tx['type'] === 'payment' ? '−' : '+' ?>
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

    <!-- Navigation -->
    <div style="border-top:1px solid #eee; margin-top:22px; padding-top:16px;" class="text-center">
        <a href="<?= BASE_URL ?>Wallet/index" class="btn-nav me-2">← My Wallet</a>
        <a href="<?= BASE_URL ?>Home/index"   class="btn-nav">🏠 Home</a>
    </div>

</div>

</body>
</html>