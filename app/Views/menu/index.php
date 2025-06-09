<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Menu Management</li>
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
                    Menu List
                </div>
                <div>
                    <a href="<?= base_url('menu/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Menu
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="menuTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Menu Name</th>
                            <th>Menu Link</th>
                            <th>Icon</th>
                            <th>Parent ID</th>
                            <th>Sort Order</th>
                            <th>Active</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Updated By</th>
                            <th>Updated Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menus as $menu) : ?>
                            <tr>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('menu/edit/' . $menu['intMenuID']) ?>" 
                                           class="btn btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger delete-menu" 
                                                data-id="<?= $menu['intMenuID'] ?>" 
                                                data-name="<?= esc($menu['txtMenuName']) ?>"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><?= esc($menu['txtMenuName']) ?></td>
                                <td><?= esc($menu['txtMenuLink']) ?></td>
                                <td>
                                    <?php if (!empty($menu['txtIcon'])) : ?>
                                        <i data-feather="<?= esc($menu['txtIcon']) ?>"></i>
                                        <span class="ms-1"><?= esc($menu['txtIcon']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $menu['intParentID'] ? esc($menu['intParentID']) : 'None' ?></td>
                                <td><?= $menu['intSortOrder'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $menu['bitActive'] ? 'success' : 'danger' ?>">
                                        <?= $menu['bitActive'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td><?= esc($menu['txtCreatedBy']) ?></td>
                                <td><?= date('Y-m-d H:i:s', strtotime($menu['dtmCreatedDate'])) ?></td>
                                <td><?= esc($menu['txtUpdatedBy']) ?></td>
                                <td><?= $menu['dtmUpdatedDate'] ? date('Y-m-d H:i:s', strtotime($menu['dtmUpdatedDate'])) : '-' ?></td>
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
                Are you sure you want to delete menu "<span id="menuNameToDelete"></span>"?
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
    const table = new DataTable('#menuTable', {
        order: [[5, 'asc']], // Sort by sort order column by default
        columnDefs: [
            { orderable: false, targets: [0, 3] } // Disable sorting for actions and icon columns
        ]
    });

    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Delete confirmation
    document.querySelectorAll('.delete-menu').forEach(button => {
        button.addEventListener('click', function() {
            const menuId = this.dataset.id;
            const menuName = this.dataset.name;
            
            document.getElementById('menuNameToDelete').textContent = menuName;
            document.getElementById('deleteForm').action = `<?= base_url('menu/delete/') ?>/${menuId}`;
            
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>