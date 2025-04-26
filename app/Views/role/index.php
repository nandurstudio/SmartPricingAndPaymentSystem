<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role List</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>Role List</h2>

        <a href="<?= base_url('role/create') ?>" class="btn btn-success mb-3">Add New Role</a>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Description</th>
                    <th>Note</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($roles) && is_array($roles)) : ?>
                    <?php foreach ($roles as $index => $role) : ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($role['txtRoleName']) ?></td>
                            <td><?= esc($role['txtRoleDesc']) ?></td>
                            <td><?= esc($role['txtRoleNote']) ?></td>
                            <td><?= $role['bitActive'] ? 'Yes' : 'No' ?></td>
                            <td>
                                <a href="<?= base_url('role/edit/' . $role['intRoleID']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= base_url('role/view/' . $role['intRoleID']) ?>" class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center">No roles found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>