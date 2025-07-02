<!-- app/Views/role_menu_access/index.php -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="bi bi-shield-lock me-2"></i>
        <?= $title ?>
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Role Menu Access</li>
    </ol>

    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-table me-2"></i>
                    Role Menu Access List
                </div>
                <div>
                    <a href="<?= base_url('role-menu-access/create') ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add New Access
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="roleMenuAccessTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="120">Actions</th>
                            <th>Role</th>
                            <th>Menu</th>
                            <th width="100">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($roleMenuAccess)): ?>
                            <?php foreach ($roleMenuAccess as $access): ?>
                                <tr>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('role-menu-access/edit/' . ($access['intRoleMenuID'] ?? '0')) ?>" 
                                               class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger delete-access" 
                                                    data-id="<?= $access['intRoleMenuID'] ?? '0' ?>"
                                                    data-role="<?= esc($access['txtRoleName'] ?? 'Unknown') ?>"
                                                    data-menu="<?= esc($access['txtMenuName'] ?? 'Unknown') ?>"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td><?= esc($access['txtRoleName'] ?? 'Unknown Role') ?></td>
                                    <td><?= esc($access['txtMenuName'] ?? 'Unknown Menu') ?></td>
                                    <td class="text-center">
                                        <?php if ($access['bitActive'] ?? false): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No role menu access found</td>
                            </tr>
                        <?php endif; ?>
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
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                    Confirm Delete
                </h5>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
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
        pageLength: 25,
        language: {
            emptyTable: "No role menu access found"
        }
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