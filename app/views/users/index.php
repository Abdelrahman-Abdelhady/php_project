<h1>Users</h1>

<a href="<?= BASE_URL ?>User/create">Create New User</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Age</th><th>Actions</th>
    </tr>

        <?php foreach ($data['users'] as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['phone_num'] ?></td>

            <td>
                <a href="<?= BASE_URL ?>User/show/<?= $user['id'] ?>">View</a> | 
                <a href="<?= BASE_URL ?>User/edit/<?= $user['id'] ?>">Edit</a> | 
                <a href="<?= BASE_URL ?>User/delete/<?= $user['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
