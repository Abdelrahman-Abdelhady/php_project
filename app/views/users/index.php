<h1>Users</h1>

<a href="<?= BASE_URL ?>User/create">Create New User</a>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th>
    </tr>
    <?php if (isset($users) && is_array($users) && count($users) > 0): ?>
        <?php foreach ($users as $user): ?>
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
    <?php else: ?>
        <tr><td colspan="5">No users found</td></tr>
    <?php endif; ?>
</table>
