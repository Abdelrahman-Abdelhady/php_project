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

        <div class="card shadow-lg p-4" style="width: 450px;">

            <h3 class="text-center mb-4">Register</h3>

            <form action="<?= BASE_URL ?>Auth/storeRegister" method="POST" enctype="multipart/form-data">

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control"
                        value="<?= $data['old']['name'] ?? '' ?>"
                        required
                    >
                    <div class="text-danger small">
                        <?= $data['errors']['name'] ?? '' ?>
                    </div>
                </div>

                <!-- Age -->
                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input 
                        type="number" 
                        name="age" 
                        class="form-control"
                        value="<?= $data['old']['age'] ?? '' ?>"
                    >
                    <div class="text-danger small">
                        <?= $data['errors']['age'] ?? '' ?>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control"
                        value="<?= $data['old']['email'] ?? '' ?>"
                    >
                    <div class="text-danger small">
                        <?= $data['errors']['email'] ?? '' ?>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control"
                    >
                    <div class="text-danger small">
                        <?= $data['errors']['password'] ?? '' ?>
                    </div>
                    <!--License Plate-->
                    <div class="mb-3">
    
                    <label class="form-label">License Plate</label>
   
                    <input type="text" name="plate_number" class="form-control">
                   </div>
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control">
                        <option value="Owner" <?= ( ($data['old']['role'] ?? '') === 'Owner' ? 'selected' : '' ) ?>>
                            Owner
                        </option>
                        <option value="Driver" <?= ( ($data['old']['role'] ?? '') === 'Driver' ? 'selected' : '' ) ?>>
                            Driver
                        </option>
                    </select>
                    <div class="text-danger small">
                        <?= $data['errors']['role'] ?? '' ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="profile_pic" class="form-label">Profile Picture</label>
                    <input type="file" name="profile_pic" class="form-control">
                    <div class="text-danger small">
                        <?= $data['errors']['profile_pic'] ?? '' ?>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn btn-success w-100">
                    Register
                </button>

            </form>

        </div>

    </div>
</body>