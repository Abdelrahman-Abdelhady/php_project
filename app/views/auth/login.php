<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">

</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">

        <div class="card shadow-lg p-4" style="width: 400px;">
            
            <h3 class="text-center mb-4">Login</h3>

            <form action="<?= BASE_URL ?>Auth/doLogin" method="POST">

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control <?= isset($data['errors']['email']) ? 'is-invalid' : '' ?>"
                        value="<?= $data['old']['email'] ?? '' ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['email'] ?? '' ?>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control <?= isset($data['errors']['password']) ? 'is-invalid' : '' ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['password'] ?? '' ?>
                    </div>
                </div>

                <!-- Login Error -->
                <?php if (!empty($data['errors']['login'][0])): ?>
                    <div class="alert alert-danger text-center">
                        <?= $data['errors']['login'] ?>
                    </div>
                <?php endif; ?>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>

            </form>

        </div>

    </div>
</body>