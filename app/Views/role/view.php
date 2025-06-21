<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('roles') ?>">Role Management</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">                <div>
                    <i class="bi bi-shield-check me-1"></i>
                    Role Details
                </div>
                <div>
                    <a href="<?= base_url('roles/edit/' . $role['intRoleID']) ?>" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="<?= base_url('roles') ?>" class="btn btn-secondary ms-2">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px">Role ID</th>
                    <td><?= $role['intRoleID'] ?></td>
                </tr>
                <tr>
                    <th>Role Name</th>
                    <td><?= esc($role['txtRoleName']) ?></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><?= nl2br(esc($role['txtRoleDesc'] ?? '-')) ?></td>
                </tr>
                <tr>
                    <th>Additional Notes</th>
                    <td><?= nl2br(esc($role['txtRoleNote'] ?? '-')) ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-<?= isset($role['bitActive']) && $role['bitActive'] ? 'success' : 'danger' ?>">
                            <i class="bi bi-<?= isset($role['bitActive']) && $role['bitActive'] ? 'check-circle' : 'x-circle' ?>"></i>
                            <?= isset($role['bitActive']) && $role['bitActive'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td><?= esc($role['txtCreatedBy'] ?? '-') ?></td>
                </tr>
                <tr>
                    <th>Created Date</th>
                    <td><?= $role['dtmCreatedDate'] ? date('Y-m-d H:i:s', strtotime($role['dtmCreatedDate'])) : '-' ?></td>
                </tr>
                <tr>
                    <th>Updated By</th>
                    <td><?= esc($role['txtUpdatedBy'] ?? '-') ?></td>
                </tr>
                <tr>
                    <th>Updated Date</th>
                    <td><?= $role['dtmUpdatedDate'] ? date('Y-m-d H:i:s', strtotime($role['dtmUpdatedDate'])) : '-' ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>