<div class="page-card" style="max-width: 500px; margin: auto;">
    <h3 class="fw-bold" style="color: #4F5D95;">New Request 📝</h3>
    <form action="<?= BASE_URL ?>Request/store" method="POST">
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-select">
                <option value="support">Support</option>
                <option value="refund">Refund</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="What do you need?"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100" style="background:#4F5D95;">Send Request</button>
    </form>
</div>