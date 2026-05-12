<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Verification</title>

    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #eef1f5; 
            display: flex; 
            margin: 0; 
        }

        .sidebar { 
            width: 260px; 
            min-height: 100vh; 
            background: #111827; 
            color: white; 
            padding: 25px 20px; 
            position: fixed; 
        }

        .sidebar h2 { 
            text-align: center; 
            margin-bottom: 30px; 
        }

        .sidebar a { 
            display: block; 
            color: #d1d5db; 
            text-decoration: none; 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 8px; 
        }

        .sidebar a:hover { 
            background: #1f2937; 
            color: white; 
            border-left: 4px solid #4F46E5; 
        }

        .main { 
            margin-left: 260px; 
            padding: 30px; 
            width: calc(100% - 260px); 
        }

        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }

        th { 
            background: #111827; 
            color: white; 
            padding: 12px; 
            text-align: left; 
        }

        td { 
            padding: 12px; 
            border-bottom: 1px solid #eee; 
            vertical-align: middle; 
        }

        .spot-img {
            width: 90px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
            background: #eee;
        }

        .btn-approve {
            background: #16a34a;
            color: white;
            padding: 7px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-reject {
            background: #dc2626;
            color: white;
            padding: 7px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .message {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .empty {
            background: #fef3c7;
            color: #92400e;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<?php require_once "../app/views/admin/sidebar.php"; ?>

<div class="main">
    <div class="card">
        <h1>Owner Verification</h1>

        <?php if (!empty($_SESSION['message'])): ?>
            <div class="message">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (empty($pendingSpots)): ?>

            <div class="empty">
                No pending spots.
            </div>

        <?php else: ?>

            <table>
                <tr>
                    <th>Image</th>
                    <th>Spot</th>
                    <th>Owner</th>
                    <th>Area</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($pendingSpots as $spot): ?>
                    <?php
                        $image = $spot['image'] ?? 'uploads/img/default_spot.png';

                        $imageSrc = str_starts_with($image, 'http')
                            ? $image
                            : BASE_URL . $image;
                    ?>

                    <tr>
                        <td>
                            <img 
                                src="<?= htmlspecialchars($imageSrc) ?>" 
                                class="spot-img" 
                                alt="Spot image"
                            >
                        </td>

                        <td>
                            <strong>
                                <?= htmlspecialchars($spot['title'] ?? 'Untitled Spot') ?>
                            </strong>
                            <br>
                            <small>
                                <?= htmlspecialchars($spot['address'] ?? '') ?>
                            </small>
                        </td>

                        <td>
                            <?= htmlspecialchars($spot['owner_name'] ?? '') ?>
                            <br>
                            <small>
                                <?= htmlspecialchars($spot['email'] ?? '') ?>
                            </small>
                        </td>

                        <td>
                            <?= htmlspecialchars($spot['area'] ?? '') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($spot['location'] ?? '') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($spot['price_per_hour'] ?? 0) ?> EGP/hr
                        </td>

                        <td>
                            <?= htmlspecialchars($spot['capacity'] ?? 0) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($spot['status'] ?? '') ?>
                        </td>

                        <td>
                            <form method="POST" action="<?= BASE_URL ?>Admin/verifyOwner" style="display:inline;">
                                <input 
                                    type="hidden" 
                                    name="spotID" 
                                    value="<?= htmlspecialchars($spot['spotID'] ?? '') ?>"
                                >

                                <input 
                                    type="hidden" 
                                    name="action" 
                                    value="approve"
                                >

                                <button type="submit" class="btn-approve">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="<?= BASE_URL ?>Admin/verifyOwner" style="display:inline;">
                                <input 
                                    type="hidden" 
                                    name="spotID" 
                                    value="<?= htmlspecialchars($spot['spotID'] ?? '') ?>"
                                >

                                <input 
                                    type="hidden" 
                                    name="action" 
                                    value="reject"
                                >

                                <button type="submit" class="btn-reject">
                                    Reject
                                </button>
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