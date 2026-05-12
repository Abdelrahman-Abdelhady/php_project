
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
        padding: 20px;
        color: #333;
    }

    h2 {
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .btn-create {
        display: inline-block;
        background-color: #3498db;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
        margin-bottom: 20px;
        transition: background 0.3s;
    }

    .btn-create:hover {
        background-color: #2980b9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    th {
        background-color: #2c3e50;
        color: white;
        text-align: left;
        padding: 15px;
        text-transform: uppercase;
        font-size: 14px;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        font-size: 15px;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    .action-links a {
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: bold;
    }

    .view-btn { color: #27ae60; border: 1px solid #27ae60; }
    .edit-btn { color: #f39c12; border: 1px solid #f39c12; margin: 0 5px; }
    .delete-btn { color: #e74c3c; border: 1px solid #e74c3c; }

    .view-btn:hover { background: #27ae60; color: white; }
    .edit-btn:hover { background: #f39c12; color: white; }
    .delete-btn:hover { background: #e74c3c; color: white; }
</style>

<h2>User Management</h2>

<a href="<?= BASE_URL ?>User/create" class="btn-create">+ Create New User</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><strong><?= htmlspecialchars($user['userID']) ?></strong></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['phone_num']) ?></td>
                <td>
                    <span style="background: #e1e8ed; padding: 3px 8px; border-radius: 12px; font-size: 12px;">
                        <?= htmlspecialchars($user['role']) ?>
                    </span>
                </td>
                <td class="action-links">
                    <a href="<?= BASE_URL ?>User/show/<?= $user['userID'] ?>" class="view-btn">View</a>
                    <a href="<?= BASE_URL ?>User/edit/<?= $user['userID'] ?>" class="edit-btn">Edit</a>
                    <a href="<?= BASE_URL ?>User/delete/<?= $user['userID'] ?>" 
                       class="delete-btn" 
                       onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>