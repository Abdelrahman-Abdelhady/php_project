<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Owner Reviews</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/home.css">

    <style>
        .reviews-wrapper {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .summary {
            background: #f4f7f9;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .review-card {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .rating {
            color: #f59e0b;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php require_once "../app/views/layout/header.php"; ?>

<main class="reviews-wrapper">
    <h1>Reviews</h1>

    <div class="summary">
        <p><strong>Average Rating:</strong> <?= htmlspecialchars(number_format((float)$averageRating, 1)) ?>/5</p>
        <p><strong>Total Reviews:</strong> <?= htmlspecialchars($reviewCount) ?></p>
    </div>

    <?php if (empty($reviews)): ?>
        <p>No reviews yet.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <p class="rating">Rating: <?= htmlspecialchars($review['rating']) ?>/5</p>
                <p><strong>Driver:</strong> <?= htmlspecialchars($review['user_name'] ?? 'Unknown') ?></p>
                <p><strong>Spot:</strong> <?= htmlspecialchars($review['spot_name'] ?? '') ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($review['spot_location'] ?? '') ?></p>
                <p><?= htmlspecialchars($review['comment'] ?? '') ?></p>
                <small><?= htmlspecialchars($review['date'] ?? '') ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer>
    <?php require_once "../app/views/layout/footer.php"; ?>
</footer>

</body>
</html>