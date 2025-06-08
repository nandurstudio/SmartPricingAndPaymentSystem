<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Service Types</h5>
                <a href="<?= base_url('service-types/create') ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add New Service Type
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-<?= session()->getFlashdata('message_type') ?? 'info' ?> alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="serviceTypeTable">                    <thead class="table-light">
                        <tr>
                            <th>Actions</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th>Updated By</th>
                            <th>Updated Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($serviceTypes as $type): ?>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('service-types/edit/' . $type['intServiceTypeID']) ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-<?= $type['bitActive'] ? 'danger' : 'success' ?> toggle-status" 
                                            data-id="<?= $type['intServiceTypeID'] ?>" 
                                            data-status="<?= $type['bitActive'] ?>" 
                                            title="<?= $type['bitActive'] ? 'Deactivate' : 'Activate' ?>">
                                        <i class="fa <?= $type['bitActive'] ? 'fa-ban' : 'fa-check' ?>"></i>
                                    </button>
                                </div>
                            </td>                            <td>
                                <?= esc($type['txtName']) ?>
                                <?php if (!empty($type['txtCategory'])): ?>
                                    <br><small class="text-muted"><?= esc($type['txtCategory']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($type['txtDescription']) ?></td>
                            <td>
                                <span class="badge bg-<?= $type['bitActive'] ? 'success' : 'danger' ?>">
                                    <?= $type['bitActive'] ? 'Active' : 'Inactive' ?>
                                </span>
                                <?php if ($type['bitIsSystem']): ?>
                                    <span class="badge bg-info ms-1">System</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($type['txtCreatedBy']) ?></td>
                            <td><?= date('d M Y H:i', strtotime($type['dtmCreatedDate'])) ?></td>
                            <td><?= esc($type['txtUpdatedBy']) ?></td>
                            <td><?= $type['dtmUpdatedDate'] ? date('d M Y H:i', strtotime($type['dtmUpdatedDate'])) : '-' ?></td>
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
                Are you sure you want to <span id="statusAction"></span> this service type?
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
    new DataTable('#serviceTypeTable', {
        order: [[5, 'desc']], // Sort by created date by default
        language: {
            search: "Search service types:",
            lengthMenu: "Show _MENU_ service types per page",
        }
    });

    // Status Toggle Handling
    let selectedTypeId = null;
    let selectedStatus = null;

    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            selectedTypeId = this.dataset.id;
            selectedStatus = this.dataset.status === "1";
            const action = selectedStatus ? 'deactivate' : 'activate';
            document.getElementById('statusAction').textContent = action;
            new bootstrap.Modal(document.getElementById('statusToggleModal')).show();
        });
    });

    document.getElementById('confirmStatusChange').addEventListener('click', function() {
        if (selectedTypeId) {
            fetch(`<?= base_url('service-types/toggle-status/') ?>/${selectedTypeId}`, {
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
                    alert('Failed to update service type status. Please try again.');
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
