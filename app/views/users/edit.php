<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Edit User</h2>

        <form action="<?= BASE_URL ?>User/update/<?= $data['user']['id'] ?>" method="POST">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?= $data['user']['name'] ?>" class="form-control">
            </div>

            <div class="form-group">
                <label>Phone number:</label>
                <input type="number" name="phone_num" value="<?= $data['user']['phone_num'] ?>" class="form-control">
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?= $data['user']['email'] ?>" class="form-control">
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" value="<?= $data['user']['password'] ?>" class="form-control">
            </div>
            
            <div class="form-group">
                <label>Role:</label>
                <select name="role" class="form-control">
                    <option value="driver" <?= $data['user']['role'] === 'driver' ? 'selected' : '' ?>>Driver</option>
                    <option value="space_owner" <?= $data['user']['role'] === 'space_owner' ? 'selected' : '' ?>>Owner</option>
                    <option value="admin" <?= $data['user']['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Update</button>
            <a href="<?= BASE_URL ?>User/index" class="btn btn-danger mt-2">Back</a>
        </form>
    </div>
</body>