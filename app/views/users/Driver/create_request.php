<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - New Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 30px 0;
        }

        .form-card {
            width: 100%;
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 35px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 11px 16px;
            font-size: 0.9rem;
            border: 1.5px solid #ddd;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4F5D95;
            box-shadow: 0 0 0 3px rgba(79,93,149,0.15);
        }

        .invalid-feedback { font-size: 0.82rem; }

        .btn-submit {
            background: #4F5D95;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            margin-top: 6px;
            transition: background 0.2s;
        }

        .btn-submit:hover { background: #3b4675; }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 12px;
            padding: 9px;
            border-radius: 25px;
            font-size: 0.9rem;
            border: 1.5px solid #ccc;
            color: #444;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-back:hover {
            border-color: #4F5D95;
            color: #4F5D95;
        }
    </style>
</head>
<body>

<div class="form-card">

    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color:#4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted mb-0">New Request</p>
    </div>

    <form action="<?= BASE_URL ?>Request/store" method="POST" novalidate>

        <!-- Type -->
        <div class="mb-3">
            <label class="form-label">Request Type</label>
            <select name="type" class="form-select <?= isset($errors['type']) ? 'is-invalid' : '' ?>">
                <option value="" disabled <?= empty($old['type']) ? 'selected' : '' ?>>Select a type...</option>
                <option value="owner_verification" <?= ($old['type'] ?? '') === 'owner_verification' ? 'selected' : '' ?>>Owner Verification</option>
                <option value="refund"             <?= ($old['type'] ?? '') === 'refund'             ? 'selected' : '' ?>>Refund</option>
                <option value="fine_appeal"        <?= ($old['type'] ?? '') === 'fine_appeal'        ? 'selected' : '' ?>>Fine Appeal</option>
                <option value="maintenance"        <?= ($old['type'] ?? '') === 'maintenance'        ? 'selected' : '' ?>>Maintenance</option>
                <option value="support"            <?= ($old['type'] ?? '') === 'support'            ? 'selected' : '' ?>>Support</option>
            </select>
            <?php if (isset($errors['type'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['type']) ?></div>
            <?php endif; ?>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="form-label">Description</label>
            <textarea
                name="description"
                rows="4"
                class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"
                placeholder="Describe your request..."
            ><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            <?php if (isset($errors['description'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Submit Request</button>
    </form>

    <a href="<?= BASE_URL ?>Request/index" class="btn-back">← Back to My Requests</a>

</div>

</body>
</html>