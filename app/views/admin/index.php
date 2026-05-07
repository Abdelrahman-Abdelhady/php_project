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

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Users</h1>

            <a href="<?= BASE_URL ?>Admin/create" class="btn btn-primary">
                + Create New User
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body p-0">

                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Age</th>
                            <th>Role</th>
                            <th>Image</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($data['users'] as $user): ?>
                            <?php if ($user['id'] == $data['user']['id']) continue; // Skip current logged-in user ?>
                            <tr id="user-row-<?= $user['id'] ?>">
                                <td><?= $user['name'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= $user['age'] ?></td>
                                <td><?= $user['role'] ?></td>

                                <td>
                                    <?php if ($user['profile_pic']): ?>
                                        <img src="<?= BASE_URL ?><?= $user['profile_pic'] ?>" alt="User Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                    <?php else: ?>
                                        <img src="<?= BASE_URL ?>uploads/img/default.png" alt="Default Image" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>Admin/show/<?= $user['id'] ?>" class="btn btn-sm btn-info text-white">
                                        View
                                    </a>

                                    <a href="<?= BASE_URL ?>Admin/edit/<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user['id'] ?>)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.min.js"></script>
    
    <script>
        function deleteUser(userId) {

            if (!confirm("Are you sure you want to delete this user?")) {
                return;
            }

            const rowId = "#user-row-" + userId;

            $.ajax({
                url: "<?= BASE_URL ?>Admin/delete/" + userId,
                type: "POST",
                dataType: "json",

                success: function(response) {
                    if (response.success) {

                        $(rowId)
                            .find("td")
                            .css("background-color", "#f8d7da")
                            .animate({ opacity: 0 }, 600, function() {
                                $(rowId).remove();
                            });

                    } else {
                        alert("Failed to delete user.");
                    }
                },

                error: function() { 
                    alert("Server error. Could not delete user.");
                }
            });


            // For non-jquery users, you can use Fetch API like this:
            /*
            fetch("<?= BASE_URL ?>Admin/delete/" + userId, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the user row from the table
                    const row = document.getElementById("user-row-" + userId);
                    row.style.backgroundColor = "#f8d7da"; // Red background
                    setTimeout(() => row.remove(), 600); // Remove after animation
                } else {
                    alert("Failed to delete user.");
                }
            })
            .catch(() => {
                alert("Server error. Could not delete user.");
            });
             */
        }
    </script>

</body>