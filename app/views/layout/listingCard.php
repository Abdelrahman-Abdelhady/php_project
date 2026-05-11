<?php
// Safety defaults in case some values are missing
$spotID      = $listing['spotID'] ?? $listing['id'] ?? 0;
$title       = $listing['title'] ?? $listing['location'] ?? 'Parking Space';
$image       = $listing['image'] ?? $listing['photo'] ?? 'assets/images/default-spot.jpg';
$price       = $listing['price_per_hour'] ?? $listing['price'] ?? 0;
$rating      = $listing['rating'] ?? 'New';
$distance    = $listing['distance'] ?? 'Nearby';
$status      = $listing['status'] ?? 'available';
$features    = $listing['features'] ?? ['SUV Fit', 'CCTV'];

// Handle image path
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
            $<?= htmlspecialchars(number_format((float)$price, 2)) ?>/hr
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
            📍 <?= htmlspecialchars($distance) ?>
        </span>

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