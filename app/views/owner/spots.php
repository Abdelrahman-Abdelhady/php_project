<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Spots</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { 
            background:#f4f7f6; 
            font-family: Segoe UI; 
        }

        .spots-grid { 
            display:grid; 
            grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); 
            gap:20px; 
            margin-top:20px; 
        }

        .spot-card { 
            background:#fff; 
            padding:20px; 
            border-radius:15px; 
            box-shadow:0 4px 15px rgba(0,0,0,0.08); 
            border-left:5px solid #4F5D95; 
        }

        .btn-back { 
            margin-bottom:20px; 
        }
    </style>
</head>

<body>

<?php require_once "../app/views/layout/header.php"; ?>

<div class="container mt-4">

    <a href="<?= BASE_URL ?>Owner/dashboard" class="btn btn-secondary btn-back">
        ← Back to Dashboard
    </a>

    <h2 style="color:#4F5D95; font-weight:bold;">My Spots</h2>

    <?php if (!empty($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="spots-grid">

        <?php if (!empty($spots)): ?>

            <?php foreach ($spots as $row): ?>

                <div class="spot-card" id="spot-card-<?= htmlspecialchars($row['spotID']) ?>">

                   <h4><?= htmlspecialchars($row['title'] ?? 'Untitled Spot') ?></h4>

                    <p><b>Area:</b> <?= htmlspecialchars($row['area'] ?? 'N/A') ?></p>
                    <p><b>Location:</b> <?= htmlspecialchars($row['location'] ?? 'N/A') ?></p>
                    <p><b>Address:</b> <?= htmlspecialchars($row['address'] ?? 'N/A') ?></p>
                    <p><b>Price:</b> <?= htmlspecialchars($row['price_per_hour'] ?? 0) ?> EGP</p>
                    <p><b>Capacity:</b> <?= htmlspecialchars($row['capacity'] ?? 0) ?></p>
                    <p><b>Status:</b> <?= htmlspecialchars($row['status'] ?? 'pending') ?></p>
                    <button 
                        type="button" 
                        data-id="<?= htmlspecialchars($row['spotID']) ?>" 
                        class="btn btn-danger btn-sm delete-spot-btn"
                    >
                        Delete
                    </button>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="alert alert-warning">
                No spots found. Click "Add New Spot" in dashboard.
            </div>

        <?php endif; ?>

    </div>
</div>

<footer class="mt-5">
    <?php require_once "../app/views/layout/footer.php"; ?>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.delete-spot-btn').click(function() {
        if (!confirm('Delete this spot?')) return;

        var spotId = $(this).data('id');
        var button = $(this);

        $.ajax({
            url: "<?= BASE_URL ?>Owner/deleteSpotAjax",
            type: "POST",
            data: { spotID: spotId },
            dataType: "json",

            success: function(response) {
                if (response.success) {
                    button.closest('.spot-card').fadeOut(500, function() {
                        $(this).remove();
                    });
                } else {
                    alert(response.message || 'Could not delete spot.');
                }
            },

            error: function() {
                alert('Something went wrong while deleting the spot.');
            }
        });
    });
});
</script>

</body>
</html>