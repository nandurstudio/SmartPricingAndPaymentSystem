<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= $pageTitle ?? 'Role Details' ?></h5>
                <div>
                    <a href="<?= base_url('roles/edit/' . $role['intRoleID']) ?>" class="btn btn-primary btn-sm">
                        <i data-feather="edit"></i> Edit
                    </a>
                    <a href="<?= base_url('roles') ?>" class="btn btn-secondary btn-sm ms-2">
                        <i data-feather="arrow-left"></i> Back
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
                        <span class="badge bg-<?= isset($role['bitStatus']) && $role['bitStatus'] ? 'success' : 'danger' ?>">
                            <i data-feather="<?= isset($role['bitStatus']) && $role['bitStatus'] ? 'check-circle' : 'x-circle' ?>" class="me-1"></i>
                            <?= isset($role['bitStatus']) && $role['bitStatus'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Created By</th>
                    <td><?= esc($role['txtCreatedBy'] ?? '-') ?></td>
                </tr>
                <tr>
                    <th>Created Date</th>
                    <td><?= isset($role['dtmCreatedDate']) ? date('d M Y H:i', strtotime($role['dtmCreatedDate'])) : '-' ?></td>
                </tr>
                <tr>
                    <th>Updated By</th>
                    <td><?= esc($role['txtLastUpdatedBy'] ?? '-') ?></td>
                </tr>
                <tr>
                    <th>Updated Date</th>
                    <td><?= isset($role['dtmLastUpdatedDate']) ? date('d M Y H:i', strtotime($role['dtmLastUpdatedDate'])) : '-' ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>