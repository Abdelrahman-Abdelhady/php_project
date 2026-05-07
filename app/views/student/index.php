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

        <div class="card shadow-lg" style="max-width: 600px; margin: auto;">
            
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Welcome, <?= $data['user']['name'] ?? 'Student' ?> 👋</h3>
            </div>

            <div class="card-body">

                <!-- USER IMAGE -->
                <img 
                    src="<?= BASE_URL ?><?= $data['user']['profile_pic'] ?>" 
                    alt="Profile picture"
                    class="img-fluid rounded-circle mb-3"
                    style="width: 150px; height: 150px; object-fit: cover;"
                >

                <p class="lead">
                    You are logged in as a <strong>Student</strong>.
                </p>

                <hr>

                <h5>Your Info</h5>
                <br>
                <p><strong>Age:</strong> <?= $data['user']['age'] ?></p>
                <p><strong>Email:</strong> <?= $data['user']['email'] ?></p>


                <hr>

                <h5>Quick Actions</h5>

                <a href="#" class="btn btn-primary w-100 mb-2">View Profile</a>
                <a href="#" class="btn btn-outline-primary w-100 mb-2">View Courses</a>
                <a href="#" class="btn btn-outline-secondary w-100 mb-2">View Grades</a>

            </div>

            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>Auth/logout" class="btn btn-danger">Logout</a>
            </div>

        </div>

    </div>
</body>