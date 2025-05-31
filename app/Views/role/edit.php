<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Edit Role</h2>
    <form action="<?= base_url('role/update/' . $role['intRoleID']) ?>" method="post">
        <div class="mb-3">
            <label for="role_name" class="form-label">Role Name:</label>
            <input type="text" class="form-control" id="role_name" name="txtRoleName" value="<?= esc($role['txtRoleName']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <input type="text" class="form-control" id="description" name="txtDesc" value="<?= esc($role['txtDesc']) ?>">
        </div>
        <div class="mb-3">
            <label for="active" class="form-label">Status:</label>
            <select class="form-select" id="active" name="bitActive">
                <option value="1" <?= $role['bitActive'] ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= !$role['bitActive'] ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="<?= base_url('role') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>