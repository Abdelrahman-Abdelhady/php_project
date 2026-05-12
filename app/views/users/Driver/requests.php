<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - My Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; padding: 30px; }

        .page-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .badge-pending  { background: #fff3e0; color: #e65100; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; }
        .badge-accepted { background: #e8f5e9; color: #2e7d32; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; }
        .badge-rejected { background: #ffebee; color: #c62828; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; }

        .type-label {
            font-size: 0.78rem;
            color: #4F5D95;
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-add {
            border-radius: 25px;
            padding: 8px 20px;
            border: none;
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            background: #4F5D95;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-add:hover {
            background: #3b4675;
            color: white;
        }

        .btn-home {
            border-radius: 25px;
            padding: 8px 20px;
            border: 1.5px solid #ccc;
            color: #444;
            text-decoration: none;
            font-size: 0.9rem;
            background: white;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-home:hover {
            border-color: #4F5D95;
            color: #4F5D95;
        }
    </style>
</head>
<body>

<div class="page-card">

    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted">My Requests</p>
    </div>

    <?php if (!empty($requests)): ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead style="background: #f0f2ff;">
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($requests as $req): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><span class="type-label"><?= htmlspecialchars(str_replace('_', ' ', $req['type'])) ?></span></td>
                            <td><?= htmlspecialchars($req['description']) ?></td>
                            <td>
                                <?php
                                    $status = strtolower($req['status']);
                                    $badge  = match($status) {
                                        'accepted' => 'badge-accepted',
                                        'rejected' => 'badge-rejected',
                                        default    => 'badge-pending',
                                    };
                                ?>
                                <span class="<?= $badge ?>"><?= htmlspecialchars($req['status']) ?></span>
                            </td>
                            <td style="font-size: 0.82rem;"><?= htmlspecialchars($req['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">📋 No requests yet.</p>
    <?php endif; ?>

    <div class="text-center mt-3 d-flex justify-content-center gap-2">
        <a href="<?= BASE_URL ?>Request/create" class="btn-add">+ New Request</a>
        <a href="<?= BASE_URL ?>Wallet/index"   class="btn-home">← Back to Wallet</a>
    </div>

</div>

</body>
</html>