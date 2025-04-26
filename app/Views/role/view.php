<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Role</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <div class="container mt-5">
        <h2>View Role</h2>

        <div class="mb-3">
            <strong>Role Name:</strong> <?= esc($role['txtRoleName']) ?>
        </div>
        <div class="mb-3">
            <strong>Description:</strong> <?= esc($role['txtRoleDesc']) ?>
        </div>
        <div class="mb-3">
            <strong>Note:</strong> <?= esc($role['txtRoleNote']) ?>
        </div>
        <div class="mb-3">
            <strong>Active:</strong> <?= $role['bitActive'] ? 'Yes' : 'No' ?>
        </div>

        <a href="<?= base_url('role') ?>" class="btn btn-secondary">Back to List</a>
    </div>
    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>