<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">

</head>

<body class="bg-light">
    <div class="container mt-5">

        <div class="card shadow-lg" style="max-width: 500px; margin: auto;">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">User Details</h3>
            </div>

            <div class="card-body">

                <p><strong>ID:</strong> <?= $data['user']['id'] ?></p>
                <p><strong>Name:</strong> <?= $data['user']['name'] ?></p>
                <p><strong>Email:</strong> <?= $data['user']['email'] ?></p>
                <p><strong>Age:</strong> <?= $data['user']['age'] ?></p>
                <p><strong>Role:</strong> <?= $data['user']['role'] ?></p>
                <p><strong>Profile Picture:</strong></p>
                <?php if ($data['user']['profile_pic']): ?>
                    <img src="<?= BASE_URL ?><?= $data['user']['profile_pic'] ?>" alt="User Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                <?php else: ?>
                    <img src="<?= BASE_URL ?>uploads/img/default.png" alt="Default Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                <?php endif; ?>


            </div>

            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>Admin/index" class="btn btn-secondary">
                    Back
                </a>
            </div>

        </div>

    </div>
</body>