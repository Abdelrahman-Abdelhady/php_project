<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CitySlot - Sign Up</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 30px 0;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid #4F5D95;
        }

        .btn-primary {
            background: #4F5D95;
            border: none;
            border-radius: 25px;
            padding: 10px;
            width: 100%;
        }

        .btn-primary:hover {
            background: #3b4675;
        }

        .role-selection {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .role-selection input {
            display: none;
        }

        .role-selection label {
            flex: 1;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .role-selection input:checked + label {
            background: #4F5D95;
            color: white;
            border-color: #4F5D95;
        }

        .preview-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
            border: 2px solid #4F5D95;
        }

        .error-text {
            color: #d93025;
            font-size: 0.85rem;
            margin-top: 4px;
            display: block;
        }
    </style>
</head>

<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h2 class="fw-bold" style="color: #4F5D95;">CitySlot 🚘</h2>
        <p class="text-muted">Create your account</p>
    </div>

    <form action="<?= BASE_URL ?>Auth/storeRegister" method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Full Name</label>

            <input 
                type="text" 
                name="name" 
                class="form-control"
                value="<?= htmlspecialchars($old['name'] ?? '') ?>"
            >

            <?php if (!empty($errors['name'])): ?>
                <small class="error-text"><?= htmlspecialchars($errors['name']) ?></small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone Number</label>

            <input 
                type="tel" 
                name="phone_num" 
                class="form-control"
                value="<?= htmlspecialchars($old['phone_num'] ?? '') ?>"
            >

            <?php if (!empty($errors['phone_num'])): ?>
                <small class="error-text"><?= htmlspecialchars($errors['phone_num']) ?></small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>

            <input 
                type="email" 
                name="email" 
                class="form-control"
                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            >

            <?php if (!empty($errors['email'])): ?>
                <small class="error-text"><?= htmlspecialchars($errors['email']) ?></small>
            <?php endif; ?>
        </div>

        <label class="form-label">I am a:</label>

        <div class="role-selection">
            <input 
                type="radio" 
                name="role" 
                id="driver" 
                value="driver"
                <?= (($old['role'] ?? 'driver') === 'driver') ? 'checked' : '' ?>
            >
            <label for="driver">🚗 Driver</label>

            <input 
                type="radio" 
                name="role" 
                id="owner" 
                value="space_owner"
                <?= (($old['role'] ?? '') === 'space_owner') ? 'checked' : '' ?>
            >
            <label for="owner">🏠 Space Owner</label>
        </div>

        <?php if (!empty($errors['role'])): ?>
            <small class="error-text"><?= htmlspecialchars($errors['role']) ?></small>
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Password</label>

            <input 
                type="password" 
                name="password" 
                class="form-control"
            >

            <?php if (!empty($errors['password'])): ?>
                <small class="error-text"><?= htmlspecialchars($errors['password']) ?></small>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Profile Picture Optional</label>

            <input 
                type="file" 
                name="profile_pic" 
                class="form-control" 
                accept="image/*" 
                onchange="previewImage(event)"
            >

            <?php if (!empty($errors['profile_pic'])): ?>
                <small class="error-text"><?= htmlspecialchars($errors['profile_pic']) ?></small>
            <?php endif; ?>

            <img id="preview" class="preview-img" style="display: none;">
        </div>

        <button type="submit" class="btn btn-primary shadow">Sign Up</button>
    </form>

    <div class="mt-4 text-center">
        <p class="small text-muted">
            Already have an account?
            <a href="<?= BASE_URL ?>Auth/login" class="text-decoration-none fw-bold" style="color: #4F5D95;">
                Login
            </a>
        </p>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.margin = '10px auto 0';
            };

            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>