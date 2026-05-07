<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">

</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center mt-5">

        <div class="card shadow-lg p-4" style="width: 500px;">
            
            <h3 class="text-center mb-4">Create User</h3>

            <form action="<?= BASE_URL ?>Admin/store" method="POST" enctype="multipart/form-data">

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control <?= isset($data['errors']['name']) ? 'is-invalid' : '' ?>"
                        value="<?= $data['old']['name'] ?? '' ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['name'] ?? '' ?>
                    </div>
                </div>

                <!-- Age -->
                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input 
                        type="number" 
                        name="age" 
                        class="form-control <?= isset($data['errors']['age']) ? 'is-invalid' : '' ?>"
                        value="<?= $data['old']['age'] ?? '' ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['age'] ?? '' ?>
                    </div>
                </div>

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
                        value="<?= $data['old']['password'] ?? '' ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['password'] ?? '' ?>
                    </div>
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control">
                        <option value="student" <?= ( ($data['old']['role'] ?? '') === 'student' ? 'selected' : '' ) ?>>
                            Student
                        </option>
                        <option value="professor" <?= ( ($data['old']['role'] ?? '') === 'professor' ? 'selected' : '' ) ?>>
                            Professor
                        </option>
                    </select>
                    <div class="text-danger small">
                        <?= $data['errors']['role'] ?? '' ?>
                    </div>
                </div>

                <!-- Profile Picture -->
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input 
                        type="file" 
                        name="profile_pic" 
                        class="form-control <?= isset($data['errors']['profile_pic']) ? 'is-invalid' : '' ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['profile_pic'] ?? '' ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success w-100">
                    Save
                </button>

            </form>

        </div>

    </div>
</body>