<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">

</head>

<body class="bg-light">

<!-- ================= HEADER / NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <!-- Website Logo/Name -->
        <a class="navbar-brand">
            My PHP MVC Project
        </a>

        <!-- Menu -->
        <div>

            <!-- Right Side Buttons -->
            <div class="ms-auto d-flex gap-2">
                <?php if(!$data['user']): ?>
                    <a href="<?= BASE_URL ?>Auth/login" class="btn btn-outline-light">
                        Login
                    </a>

                    <a href="<?= BASE_URL ?>Auth/register" class="btn btn-warning">
                        Register
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>Auth/logout" class="btn btn-danger">
                        Logout
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>
<!-- =============== END HEADER =============== -->


<!-- ================= MAIN CONTENT ================= -->
<div class="container text-center mt-5">

    <div class="card shadow-lg p-5 mx-auto" style="max-width: 600px;">
        <h1 class="mb-4">Welcome to My PHP MVC Project</h1>

        <p class="lead mb-4">
            This is the home page.
        </p>
    </div>

</div>

</body>
</html>
