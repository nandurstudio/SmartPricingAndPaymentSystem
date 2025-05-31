<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Role</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap/bootstrap.min.css'); ?>">
</head>

<body>
    <?= $this->extend('layouts/main') ?>

    <?= $this->section('content') ?>
    <div class="container mt-4">
        <h2>Create Role</h2>
        <form action="<?= base_url('role/store') ?>" method="post">
            <div class="mb-3">
                <label for="role_name" class="form-label">Role Name:</label>
                <input type="text" class="form-control" id="role_name" name="txtRoleName" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <input type="text" class="form-control" id="description" name="txtDesc">
            </div>
            <div class="mb-3">
                <label for="active" class="form-label">Status:</label>
                <select class="form-select" id="active" name="bitActive">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Role</button>
            <a href="<?= base_url('role') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <?= $this->endSection() ?>

    <script src="<?php echo base_url('assets/js/bootstrap/bootstrap.bundle.min.js'); ?>"></script>
</body>

</html>