<?php
$spotID   = $listing['spotID'] ?? 0;
$title    = $listing['title'] ?? 'Parking Space';
$area     = $listing['area'] ?? '';
$location = $listing['location'] ?? '';
$address  = $listing['address'] ?? '';
$image    = $listing['image'] ?? 'uploads/img/default_spot.png';
$price    = $listing['price_per_hour'] ?? 0;
$rating   = $listing['rating'] ?? 'New';
$status   = $listing['status'] ?? 'available';
$features = $listing['features'] ?? ['Standard Parking'];

$imageSrc = str_starts_with($image, 'http')
    ? $image
    : BASE_URL . $image;
?>

<div class="listing-card">
    <div class="listing-image-wrapper">
        <img 
            src="<?= htmlspecialchars($imageSrc) ?>" 
            alt="<?= htmlspecialchars($title) ?>"
        >

        <div class="price-tag">
            <?= htmlspecialchars(number_format((float)$price, 2)) ?> EGP/hr
        </div>
    </div>

    <div class="listing-content">
        <div class="listing-header">
            <h4><?= htmlspecialchars($title) ?></h4>

            <span class="rating-badge">
                ★ <?= htmlspecialchars($rating) ?>
            </span>
        </div>

        <span class="distance-text">
            📍 <?= htmlspecialchars($area) ?>
        </span>

        <p style="margin: 8px 0; color: #555; font-size: 0.9rem;">
            <?= htmlspecialchars($location) ?>
        </p>

        <?php if (!empty($address)): ?>
            <p style="margin: 4px 0; color: #777; font-size: 0.85rem;">
                <?= htmlspecialchars($address) ?>
            </p>
        <?php endif; ?>

        <div class="specs-container">
            <?php foreach ($features as $feature): ?>
                <span class="spec-chip">
                    <?= htmlspecialchars($feature) ?>
                </span>
            <?php endforeach; ?>
        </div>

        <div class="status-indicator">
            <div class="pulse-dot"></div>
            <?= $status === 'available' ? 'Available Now' : htmlspecialchars(ucfirst($status)) ?>
        </div>
    </div>

    <div class="card-actions">
        <a 
            href="<?= BASE_URL ?>Reservation/create/<?= htmlspecialchars($spotID) ?>" 
            class="btn-book"
        >
            Reserve Spot
        </a>

        <a 
            href="<?= BASE_URL ?>Favorite/add/<?= htmlspecialchars($spotID) ?>" 
            class="btn-fav"
        >
            ❤️
        </a>
    </div>
</div>