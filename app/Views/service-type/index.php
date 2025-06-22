<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="bi bi-list-task text-primary me-2"></i>
        <?= $pageTitle ?>
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-primary border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Service Types
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count($serviceTypes) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-collection-fill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-success border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Types
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($serviceTypes, fn($type) => $type['bitActive'] == 1)) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-toggle-on fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-danger border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Inactive Types
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($serviceTypes, fn($type) => $type['bitActive'] == 0)) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-toggle-off fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Categories
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_unique(array_filter(array_column($serviceTypes, 'txtCategory')))) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-folder2-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="bi bi-table me-1 text-primary"></i>
                        Service Types List
                    </h5>
                    <div class="small text-muted">Manage and organize your service categories</div>
                </div>
                <a href="<?= base_url('service-types/create') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New Service Type
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="serviceTypeTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 100px">Actions</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th style="width: 100px">Status</th>
                            <th>Created By</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($serviceTypes as $type): ?>
                        <tr>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('service-types/edit/' . $type['intServiceTypeID']) ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit Service Type">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-<?= $type['bitActive'] ? 'danger' : 'success' ?> toggle-status" 
                                            data-id="<?= $type['intServiceTypeID'] ?>" 
                                            data-status="<?= $type['bitActive'] ?>"
                                            data-bs-toggle="tooltip" 
                                            title="<?= $type['bitActive'] ? 'Deactivate' : 'Activate' ?> Service Type">
                                        <i class="bi <?= $type['bitActive'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi <?= $type['txtIcon'] ?: 'bi-box' ?> text-primary me-2"></i>
                                    <div>
                                        <div class="fw-semibold"><?= esc($type['txtName']) ?></div>
                                        <?php if ($type['txtSlug']): ?>
                                            <div class="small text-muted"><?= esc($type['txtSlug']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($type['txtDescription']): ?>
                                    <?= esc($type['txtDescription']) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">No description</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($type['txtCategory']): ?>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-folder me-1"></i>
                                        <?= esc($type['txtCategory']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Uncategorized</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-<?= $type['bitActive'] ? 'success' : 'danger' ?> rounded-pill">
                                        <?= $type['bitActive'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-person me-1"></i>
                                    <?= esc($type['txtCreatedBy']) ?>
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    <?= date('M d, Y', strtotime($type['dtmCreatedDate'])) ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($type['dtmUpdatedDate']): ?>
                                    <div class="small">
                                        <i class="bi bi-person me-1"></i>
                                        <?= esc($type['txtUpdatedBy']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="bi bi-clock-history me-1"></i>
                                        <?= date('M d, Y H:i', strtotime($type['dtmUpdatedDate'])) ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">Never updated</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable with improved configuration
    $('#serviceTypeTable').DataTable({
        order: [[1, 'asc']], // Sort by Name column by default
        pageLength: 25,
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search service types...",
            lengthMenu: "_MENU_ types per page"
        },
        columnDefs: [
            { orderable: false, targets: 0 }, // Disable sorting on Actions column
            { 
                targets: 3, // Category column
                render: function(data, type, row) {
                    return data || '<span class="text-muted fst-italic">Uncategorized</span>';
                }
            }
        ],
        initComplete: function() {
            // Initialize all tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Handle status toggle with improved UX
    $('.toggle-status').on('click', function() {
        const button = $(this);
        const id = button.data('id');
        const currentStatus = button.data('status');
        const newStatus = currentStatus ? 0 : 1;
        
        Swal.fire({
            title: `${currentStatus ? 'Deactivate' : 'Activate'} Service Type?`,
            text: `Are you sure you want to ${currentStatus ? 'deactivate' : 'activate'} this service type?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: currentStatus ? '#dc3545' : '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${currentStatus ? 'deactivate' : 'activate'} it!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= base_url('service-types/toggle-status/') ?>/${id}`,
                    method: 'POST',
                    data: {
                        status: newStatus,
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'An error occurred'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to update status. Please try again.'
                        });
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
