<!-- app/Views/role_menu_access/index.php -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Role Menu Access</li>
    </ol>

    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Role Menu Access List
                </div>
                <div>
                    <a href="<?= base_url('role_menu_access/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Access
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="roleMenuAccessTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Role</th>
                            <th>Menu</th>
                            <th>View</th>
                            <th>Add</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roleMenuAccess as $access): ?>
                            <tr>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('role_menu_access/edit/' . $access['intRoleMenuAccessID']) ?>" 
                                           class="btn btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger delete-access" 
                                                data-id="<?= $access['intRoleMenuAccessID'] ?>"
                                                data-role="<?php
                                                    $role = array_filter($roles, fn($r) => $r['intRoleID'] == $access['intRoleID']);
                                                    echo !empty($role) ? reset($role)['txtRoleName'] : 'Unknown';
                                                ?>"
                                                data-menu="<?php
                                                    $menu = array_filter($menus, fn($m) => $m['intMenuID'] == $access['intMenuID']);
                                                    echo !empty($menu) ? reset($menu)['txtMenuName'] : 'Unknown';
                                                ?>"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $role = array_filter($roles, fn($r) => $r['intRoleID'] == $access['intRoleID']);
                                    echo !empty($role) ? reset($role)['txtRoleName'] : 'Unknown';
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $menu = array_filter($menus, fn($m) => $m['intMenuID'] == $access['intMenuID']);
                                    echo !empty($menu) ? reset($menu)['txtMenuName'] : 'Unknown';
                                    ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $access['bitCanView'] ? 'success' : 'danger' ?>">
                                        <?= $access['bitCanView'] ? 'Yes' : 'No' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $access['bitCanAdd'] ? 'success' : 'danger' ?>">
                                        <?= $access['bitCanAdd'] ? 'Yes' : 'No' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $access['bitCanEdit'] ? 'success' : 'danger' ?>">
                                        <?= $access['bitCanEdit'] ? 'Yes' : 'No' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $access['bitCanDelete'] ? 'success' : 'danger' ?>">
                                        <?= $access['bitCanDelete'] ? 'Yes' : 'No' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete access for role "<span id="roleName"></span>" to menu "<span id="menuName"></span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables
    const table = new DataTable('#roleMenuAccessTable', {
        order: [[1, 'asc']], // Sort by role name by default
        columnDefs: [
            { orderable: false, targets: [0] } // Disable sorting for actions column
        ]
    });

    // Delete confirmation
    document.querySelectorAll('.delete-access').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const role = this.dataset.role;
            const menu = this.dataset.menu;
            
            document.getElementById('roleName').textContent = role;
            document.getElementById('menuName').textContent = menu;
            document.getElementById('deleteForm').action = `<?= base_url('role_menu_access/delete/') ?>/${id}`;
            
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>