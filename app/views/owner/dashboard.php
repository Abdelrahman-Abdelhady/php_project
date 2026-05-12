<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CitySlot - Owner Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --main: #4F5D95;
            --main-dark: #3b4675;
            --bg: #f4f7f6;
            --dark: #111827;
            --muted: #6b7280;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg);
            margin: 0;
            color: #1f2937;
        }

        .side-drawer {
            height: 100vh;
            width: 0;
            position: fixed;
            z-index: 2000;
            top: 0;
            left: 0;
            background: var(--dark);
            overflow-x: hidden;
            transition: 0.35s;
            padding-top: 3rem;
            box-shadow: 8px 0 25px rgba(0,0,0,0.25);
        }

        .side-drawer a {
            padding: 1rem 2rem;
            text-decoration: none;
            font-size: 0.95rem;
            color: #d1d5db;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .side-drawer a:hover {
            background: #1f2937;
            color: #fff;
        }

        .side-drawer a i {
            width: 18px;
            color: #9ca3af;
        }

        .close-btn {
            color: #fca5a5 !important;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 1rem 3%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-icon {
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--main);
        }

        .site-title {
            font-weight: 800;
            color: var(--main);
            font-size: 1.25rem;
        }

        .owner-badge {
            background: #eef2ff;
            color: var(--main);
            padding: 8px 14px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .main-content {
            padding: 2rem 5vw;
        }

        .hero-card {
            background: linear-gradient(135deg, #4F5D95, #6d78b7);
            color: white;
            padding: 28px;
            border-radius: 22px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(79,93,149,0.25);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .hero-card h2 {
            font-weight: 800;
            margin-bottom: 6px;
        }

        .hero-card p {
            margin: 0;
            opacity: 0.9;
        }

        .btn-add-custom {
            background: #fff;
            color: var(--main);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.4rem;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(0,0,0,0.14);
        }

        .btn-add-custom:hover {
            background: #f3f4f6;
            color: var(--main-dark);
        }

        .stat-card {
            background: #fff;
            padding: 1.7rem;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.07);
            border: 1px solid #f0f0f0;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--main);
        }

        .stat-card small {
            color: var(--muted);
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .stat-card h2 {
            color: var(--main);
            font-weight: 800;
            margin-top: 10px;
            margin-bottom: 0;
        }

        .modal-content {
            border: none;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(0,0,0,0.2);
        }

        .modal-header {
            background: var(--main);
            color: white;
            padding: 18px 24px;
        }

        .modal-title {
            font-weight: 800;
        }

        .btn-close {
            filter: invert(1);
        }

        .form-label {
            color: #374151;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 11px 13px;
            border: 1px solid #d1d5db;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--main);
            box-shadow: 0 0 0 0.2rem rgba(79,93,149,0.15);
        }

        .section-title {
            font-weight: 800;
            color: var(--main);
            margin: 12px 0 14px;
            font-size: 1rem;
        }

        .upload-box {
            border: 2px dashed #c7cce4;
            background: #f8f9ff;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
        }

        .upload-box i {
            font-size: 1.8rem;
            color: var(--main);
            margin-bottom: 8px;
        }

        .error-text {
            color: #d93025;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        .btn-save {
            background: var(--main);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 22px;
            font-weight: 700;
        }

        .btn-save:hover {
            background: var(--main-dark);
            color: white;
        }

        .quick-links {
            background: white;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.07);
            margin-top: 10px;
        }

        .quick-links a {
            display: inline-block;
            margin: 6px;
            padding: 10px 14px;
            border-radius: 50px;
            background: #eef2ff;
            color: var(--main);
            text-decoration: none;
            font-weight: 700;
        }

        .quick-links a:hover {
            background: var(--main);
            color: white;
        }

        @media (max-width: 768px) {
            .hero-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .owner-badge {
                display: none;
            }
        }
    </style>
</head>

<body>

<div id="sideDrawer" class="side-drawer">
    <a href="javascript:void(0)" class="close-btn" onclick="toggleSidebar()">
        <i class="fas fa-times"></i> Close Menu
    </a>

    <a href="<?= BASE_URL ?>Owner/dashboard">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <a href="<?= BASE_URL ?>Owner/spots">
        <i class="fas fa-parking"></i> My Spots
    </a>

    <a href="<?= BASE_URL ?>Owner/earnings">
        <i class="fas fa-wallet"></i> Earnings
    </a>

    <a href="<?= BASE_URL ?>Owner/reviews">
        <i class="fas fa-star"></i> Reviews
    </a>

    <a href="<?= BASE_URL ?>Owner/settings">
        <i class="fas fa-cog"></i> Settings
    </a>

    <a href="<?= BASE_URL ?>Auth/logout">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<div class="topbar">
    <div class="brand">
        <div class="menu-icon" onclick="toggleSidebar()">☰</div>
        <div class="site-title">CitySlot 🚘</div>
    </div>

    <div class="owner-badge">
        Owner: <?= htmlspecialchars($user['name'] ?? 'Space Owner') ?>
    </div>
</div>

<main class="main-content">
    <div class="container-fluid">

        <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['message']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (!empty($errors['database'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errors['database']) ?>
            </div>
        <?php endif; ?>

        <div class="hero-card">
            <div>
                <h2>Owner Dashboard</h2>
                <p>Manage your parking spots, reviews, and earnings from one place.</p>
            </div>

            <button class="btn-add-custom" data-bs-toggle="modal" data-bs-target="#addSpotModal">
                <i class="fas fa-plus"></i> Add New Spot
            </button>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="stat-card">
                    <small>TOTAL REVENUE</small>
                    <h2><?= htmlspecialchars(number_format((float)($totalEarnings ?? 0), 2)) ?> EGP</h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <small>REGISTERED SPOTS</small>
                    <h2><?= htmlspecialchars($spotCount ?? 0) ?></h2>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card">
                    <small>AVG. RATING</small>
                    <h2><?= htmlspecialchars(number_format((float)($averageRating ?? 0), 1)) ?> ★</h2>
                </div>
            </div>
        </div>

        
    </div>
</main>

<div class="modal fade" id="addSpotModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add New Parking Spot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?= BASE_URL ?>Owner/storeSpot" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="section-title">Basic Information</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Spot Title</label>
                            <input 
                                type="text" 
                                name="title" 
                                class="form-control"
                                placeholder="Example: Secure Maadi Parking"
                                value="<?= htmlspecialchars($old['title'] ?? '') ?>"
                                required
                            >

                            <?php if (!empty($errors['title'])): ?>
                                <small class="error-text"><?= htmlspecialchars($errors['title']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Area</label>
                            <select name="area" class="form-select" required>
                                <option value="">Select Area</option>
                                <option value="maadi" <?= (($old['area'] ?? '') === 'maadi') ? 'selected' : '' ?>>Maadi</option>
                                <option value="nasr_city" <?= (($old['area'] ?? '') === 'nasr_city') ? 'selected' : '' ?>>Nasr City</option>
                                <option value="tagamoa" <?= (($old['area'] ?? '') === 'tagamoa') ? 'selected' : '' ?>>New Cairo</option>
                                <option value="zamalek" <?= (($old['area'] ?? '') === 'zamalek') ? 'selected' : '' ?>>Zamalek</option>
                                <option value="dokki" <?= (($old['area'] ?? '') === 'dokki') ? 'selected' : '' ?>>Dokki</option>
                                <option value="sheikh_zayed" <?= (($old['area'] ?? '') === 'sheikh_zayed') ? 'selected' : '' ?>>Sheikh Zayed</option>
                                <option value="october" <?= (($old['area'] ?? '') === 'october') ? 'selected' : '' ?>>6th October</option>
                            </select>

                            <?php if (!empty($errors['area'])): ?>
                                <small class="error-text"><?= htmlspecialchars($errors['area']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input 
                            type="text" 
                            name="location" 
                            class="form-control"
                            placeholder="Example: Maadi Corniche"
                            value="<?= htmlspecialchars($old['location'] ?? '') ?>"
                            required
                        >

                        <?php if (!empty($errors['location'])): ?>
                            <small class="error-text"><?= htmlspecialchars($errors['location']) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detailed Address</label>
                        <textarea 
                            name="address" 
                            class="form-control" 
                            rows="2"
                            placeholder="Street, building, nearby landmark"
                            required
                        ><?= htmlspecialchars($old['address'] ?? '') ?></textarea>

                        <?php if (!empty($errors['address'])): ?>
                            <small class="error-text"><?= htmlspecialchars($errors['address']) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="section-title">Pricing and Capacity</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price Per Hour</label>
                            <input 
                                type="number" 
                                name="price_per_hour" 
                                step="0.01" 
                                class="form-control"
                                value="<?= htmlspecialchars($old['price_per_hour'] ?? '') ?>"
                                required
                            >

                            <?php if (!empty($errors['price_per_hour'])): ?>
                                <small class="error-text"><?= htmlspecialchars($errors['price_per_hour']) ?></small>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacity</label>
                            <input 
                                type="number" 
                                name="capacity" 
                                class="form-control"
                                value="<?= htmlspecialchars($old['capacity'] ?? '') ?>"
                                required
                            >

                            <?php if (!empty($errors['capacity'])): ?>
                                <small class="error-text"><?= htmlspecialchars($errors['capacity']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="section-title">Vehicle Limits and Amenities</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Height Limit</label>
                            <input 
                                type="number" 
                                name="height_limit" 
                                step="0.01" 
                                class="form-control"
                                value="<?= htmlspecialchars($old['height_limit'] ?? '0') ?>"
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Width Limit</label>
                            <input 
                                type="number" 
                                name="width_limit" 
                                step="0.01" 
                                class="form-control"
                                value="<?= htmlspecialchars($old['width_limit'] ?? '0') ?>"
                            >
                        </div>
                    </div>

                    <div class="d-flex gap-4 mb-3">
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="ev_charging" 
                                value="1"
                                <?= !empty($old['ev_charging']) ? 'checked' : '' ?>
                            >
                            <label class="form-check-label">EV Charging</label>
                        </div>

                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="cctv" 
                                value="1"
                                <?= !empty($old['cctv']) ? 'checked' : '' ?>
                            >
                            <label class="form-check-label">CCTV</label>
                        </div>
                    </div>

                    <div class="section-title">Spot Image</div>

                    <div class="upload-box">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p class="mb-2 fw-bold">Upload a photo of your parking spot</p>
                        <input 
                            type="file" 
                            name="image" 
                            class="form-control"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                        >

                        <?php if (!empty($errors['image'])): ?>
                            <small class="error-text"><?= htmlspecialchars($errors['image']) ?></small>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-save px-4">
                        Save Spot
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleSidebar() {
    const drawer = document.getElementById("sideDrawer");

    if (drawer) {
        drawer.style.width = drawer.style.width === "260px" ? "0" : "260px";
    }
}
</script>

<?php if (!empty($errors)): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalElement = document.getElementById("addSpotModal");
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
});
</script>
<?php endif; ?>

<script>
window.addEventListener("pageshow", function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});
</script>

</body>
</html>