<?php 
// DEBUG: Remove this line once you see your data
// var_dump($data['requests']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e0f2f7; } /* Matches your blue background */
        .card { border-radius: 10px; overflow: hidden; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0">Available Requests</h4>
            <a href="<?php echo URLROOT; ?>/pages/index" class="btn btn-sm btn-outline-light">Back</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>User ID</th>
                        <th>Description</th>
                        <th class="pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['requests'])) : ?>
                        <?php foreach($data['requests'] as $req) : ?>
                        <tr class="align-middle">
                            <td class="ps-4 fw-bold">#<?php echo $req->id; ?></td>
                            <td>User #<?php echo $req->userID; ?></td>
                            <td><?php echo $req->description; ?></td>
                            <td class="pe-4">
                                <span class="badge <?php echo ($req->status == 'Pending') ? 'bg-warning text-dark' : 'bg-success'; ?>">
                                    <?php echo $req->status; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <p class="mb-0">No requests found in the database.</p>
                                <small>Add rows to the 'requests' table in phpMyAdmin to see them here.</small>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>