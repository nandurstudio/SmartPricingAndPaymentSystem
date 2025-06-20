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
                    <a href="<?= base_url('role-menu-access/create') ?>" class="btn btn-primary btn-sm">
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
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($roleMenuAccess as $access): ?>
                            <?php                            // Safely get role name
                            $roleName = 'Unknown';
                            $roleId = $access['intRoleID'] ?? null;
                            if ($roleId !== null) {
                                foreach ($roles as $r) {
                                    if ($r['intRoleID'] == $roleId) {
                                        $roleName = $r['txtRoleName'];
                                        break;
                                    }
                                }
                            }

                            // Safely get menu name
                            $menuName = 'Unknown';
                            $menuId = $access['intMenuID'] ?? null;
                            if ($menuId !== null) {
                                foreach ($menus as $m) {
                                    if ($m['intMenuID'] == $menuId) {
                                        $menuName = $m['txtMenuName'];
                                        break;
                                    }
                                }
                            }
                            ?>
                            <tr>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('role-menu-access/edit/' . ($access['intRoleMenuAccessID'] ?? '0')) ?>" 
                                           class="btn btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger delete-access" 
                                                data-id="<?= $access['intRoleMenuAccessID'] ?? '' ?>"
                                                data-role="<?= esc($roleName) ?>"
                                                data-menu="<?= esc($menuName) ?>"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><?= esc($roleName) ?></td>
                                <td><?= esc($menuName) ?></td>
                                <td>
                                    <span class="badge bg-<?= ($access['bitActive'] ?? 0) ? 'success' : 'danger' ?>">
                                        <?= ($access['bitActive'] ?? 0) ? 'Active' : 'Inactive' ?>
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
                Are you sure you want to delete access for <strong><span id="deleteRoleName"></span></strong> 
                to menu <strong><span id="deleteMenuName"></span></strong>?
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="deleteForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#roleMenuAccessTable').DataTable({
        responsive: true,
        order: [[1, 'asc']], // Sort by role name
        pageLength: 25
    });

    // Delete modal functionality
    const deleteModal = document.getElementById('deleteModal');
    const deleteRoleName = document.getElementById('deleteRoleName');
    const deleteMenuName = document.getElementById('deleteMenuName');
    const deleteForm = document.getElementById('deleteForm');

    document.querySelectorAll('.delete-access').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const role = this.dataset.role;
            const menu = this.dataset.menu;

            deleteRoleName.textContent = role;
            deleteMenuName.textContent = menu;
            deleteForm.action = `<?= base_url('role-menu-access/delete') ?>/${id}`;

            new bootstrap.Modal(deleteModal).show();
        });
    });
});
</script>
<?= $this->endSection() ?>