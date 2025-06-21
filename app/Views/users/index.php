<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Main Content -->
<div class="container-xl px-4 mt-4">
    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-<?= session()->getFlashdata('message_type') ?> alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-500">Users List</div>
                <a href="<?= base_url('users/create') ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-person-plus"></i>
                    Add User
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="userTable">
                    <thead class="table-light">
                        <tr>
                            <th>Actions</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Join Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('users/view/' . $user['intUserID']) ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url('users/edit/' . $user['intUserID']) ?>" 
                                       class="btn btn-sm btn-outline-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-<?= $user['bitActive'] ? 'danger' : 'success' ?> toggle-status" 
                                            data-id="<?= $user['intUserID'] ?>" 
                                            data-status="<?= $user['bitActive'] ?>" 
                                            title="<?= $user['bitActive'] ? 'Deactivate' : 'Activate' ?>">
                                        <i class="bi <?= $user['bitActive'] ? 'bi-toggle2-on' : 'bi-toggle2-off' ?>"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <?php 
                                        $photoUrl = !empty($user['txtPhoto']) ? 
                                            (filter_var($user['txtPhoto'], FILTER_VALIDATE_URL) ? 
                                                $user['txtPhoto'] : 
                                                base_url('uploads/photos/' . $user['txtPhoto'])) : 
                                            base_url('assets/img/illustrations/profiles/default.png');
                                        ?>
                                        <img class="avatar-img img-fluid" src="<?= esc($photoUrl) ?>" alt="User photo"/>
                                    </div>
                                    <div>
                                        <div class="text-lg fw-500"><?= esc($user['txtFullName']) ?></div>
                                        <div class="small text-muted"><?= esc($user['txtUserName']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($user['txtEmail']) ?></td>
                            <td><div class="badge bg-primary text-white"><?= esc($user['txtRoleName']) ?></div></td>
                            <td><?= date('d M Y', strtotime($user['dtmJoinDate'])) ?></td>
                            <td>
                                <div class="badge <?= $user['bitActive'] ? 'bg-success' : 'bg-danger' ?> text-white">
                                    <i class="bi <?= $user['bitActive'] ? 'bi-check' : 'bi-x' ?> me-1"></i>
                                    <?= $user['bitActive'] ? 'Active' : 'Inactive' ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Status Toggle Confirmation Modal -->
<div class="modal fade" id="statusToggleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to <span id="statusAction"></span> this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmStatusChange">Confirm</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables
    new DataTable('#userTable', {
        order: [[3, 'desc']], // Sort by join date by default
        language: {
            search: "Search users:",
            lengthMenu: "Show _MENU_ users per page",
        }
    });

    // Status Toggle Handling
    let selectedUserId = null;
    let selectedStatus = null;

    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            selectedUserId = this.dataset.id;
            selectedStatus = this.dataset.status === "1";
            const action = selectedStatus ? 'deactivate' : 'activate';
            document.getElementById('statusAction').textContent = action;
            new bootstrap.Modal(document.getElementById('statusToggleModal')).show();
        });
    });

    document.getElementById('confirmStatusChange').addEventListener('click', function() {
        if (selectedUserId) {
            fetch(`<?= base_url('users/toggle-status/') ?>/${selectedUserId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update user status. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
