<!-- app/Views/role_menu_access/view.php -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('role_menu_access') ?>">Role Menu Access</a></li>
        <li class="breadcrumb-item active">View</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-eye me-1"></i>
            Role Menu Access Details
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px">Role</th>
                        <td>
                            <?php
                            $role = array_filter($roles, fn($r) => $r['intRoleID'] == $roleMenuAccess['intRoleID']);
                            echo !empty($role) ? esc(reset($role)['txtRoleName']) : 'Unknown';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Menu</th>
                        <td>
                            <?php
                            $menu = array_filter($menus, fn($m) => $m['intMenuID'] == $roleMenuAccess['intMenuID']);
                            echo !empty($menu) ? esc(reset($menu)['txtMenuName']) : 'Unknown';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>View Permission</th>
                        <td>
                            <span class="badge bg-<?= $roleMenuAccess['bitCanView'] ? 'success' : 'danger' ?>">
                                <?= $roleMenuAccess['bitCanView'] ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Add Permission</th>
                        <td>
                            <span class="badge bg-<?= $roleMenuAccess['bitCanAdd'] ? 'success' : 'danger' ?>">
                                <?= $roleMenuAccess['bitCanAdd'] ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Edit Permission</th>
                        <td>
                            <span class="badge bg-<?= $roleMenuAccess['bitCanEdit'] ? 'success' : 'danger' ?>">
                                <?= $roleMenuAccess['bitCanEdit'] ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Delete Permission</th>
                        <td>
                            <span class="badge bg-<?= $roleMenuAccess['bitCanDelete'] ? 'success' : 'danger' ?>">
                                <?= $roleMenuAccess['bitCanDelete'] ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="mt-4">
                <a href="<?= base_url('role_menu_access/edit/' . $roleMenuAccess['intRoleMenuAccessID']) ?>" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Access
                </a>
                <a href="<?= base_url('role_menu_access') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>