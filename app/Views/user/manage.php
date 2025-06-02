<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">User Management</h5>
            </div>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-<?= session()->getFlashdata('message_type') ?> alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="userTable">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Join Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2">
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
                                        <div class="fw-bold"><?= esc($user['txtFullName']) ?></div>
                                        <div class="small text-muted"><?= esc($user['txtUserName']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($user['txtEmail']) ?></td>
                            <td><span class="badge bg-primary"><?= esc($user['txtRoleName']) ?></span></td>
                            <td><?= date('d M Y', strtotime($user['dtmJoinDate'])) ?></td>
                            <td>
                                <?php if ($user['bitActive']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('users/view/' . $user['intUserID']) ?>" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('users/edit/' . $user['intUserID']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-<?= $user['bitActive'] ? 'danger' : 'success' ?> toggle-status" 
                                            data-id="<?= $user['intUserID'] ?>" 
                                            data-status="<?= $user['bitActive'] ?>" 
                                            title="<?= $user['bitActive'] ? 'Deactivate' : 'Activate' ?>">
                                        <i class="fa <?= $user['bitActive'] ? 'fa-ban' : 'fa-check' ?>"></i>
                                    </button>
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
