<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="bi bi-list-check text-primary me-2"></i>
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
                                Total Services
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count($services) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-list-check fa-2x text-gray-300"></i>
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
                                Active Services
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($services, fn($service) => $service['bitActive'] == 1)) ?>
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
                                Inactive Services
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($services, fn($service) => $service['bitActive'] == 0)) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-toggle-off fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($roleID == 1): ?>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Tenants
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count($tenants ?? []) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>    </div>
    
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
                        Service List
                        <?php if (isset($tenant)): ?>
                            <span class="badge bg-info ms-2">
                                <i class="bi bi-building me-1"></i>
                                <?= esc($tenant['txtTenantName']) ?>
                            </span>
                        <?php endif; ?>
                    </h5>
                    <div class="small text-muted">Manage and organize your service offerings</div>
                </div>
                <?php if ($canManageServices): ?>
                    <a href="<?= base_url('services/create' . (isset($tenant) ? '/' . $tenant['intTenantID'] : '')) ?>" 
                       class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add New Service
                    </a>
                <?php endif; ?>
            </div>        </div>
        <?php if ($roleID == 1 && isset($tenants) && count($tenants) > 0): ?>
            <div class="p-3 border-bottom">
                <label for="tenant-filter" class="form-label fw-bold">Filter by Tenant:</label>
                <select class="form-select" id="tenant-filter">
                    <option value="">All Tenants</option>
                    <?php foreach ($tenants as $tenant): ?>
                        <option value="<?= $tenant['intTenantID'] ?>" <?= (isset($selected_tenant) && $selected_tenant == $tenant['intTenantID']) ? 'selected' : '' ?>>
                            <?= esc($tenant['txtTenantName']) ?> (<?= $tenant['txtTenantCode'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="card-body">
            <?php if (empty($services)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-clipboard-x text-muted" style="font-size: 2.5rem;"></i>
                    </div>
                    <h6 class="fw-bold">No Services Available</h6>
                    <p class="text-muted small mb-0">
                        <?php if ($canManageServices): ?>
                            Click the "Add New Service" button to add your first service.
                        <?php else: ?>
                            No services have been added yet.
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="servicesTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px">Actions</th>
                                <?php if ($roleID == 1): ?>
                                    <th>Tenant</th>
                                <?php endif; ?>
                                <th>Service</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Capacity</th>
                                <th style="width: 100px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <?php if ($canManageServices): ?>                                                <a href="<?= base_url('services/edit/' . $service['intServiceID']) ?>"
                                                   class="btn btn-outline-primary"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit Service">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-<?= $service['bitActive'] ? 'danger' : 'success' ?> toggle-service"
                                                        data-id="<?= $service['intServiceID'] ?>"
                                                        data-status="<?= $service['bitActive'] ?>"
                                                        data-bs-toggle="tooltip"
                                                        title="<?= $service['bitActive'] ? 'Deactivate' : 'Activate' ?> Service">
                                                    <i class="bi bi-toggle-<?= $service['bitActive'] ? 'on' : 'off' ?>"></i>
                                                </button>
                                            <?php endif; ?>
                                            <a href="<?= base_url('schedules?service_id=' . $service['intServiceID']) ?>"
                                               class="btn btn-outline-info"
                                               data-bs-toggle="tooltip"
                                               title="Manage Schedules">
                                                <i class="bi bi-calendar2-week"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <?php if ($roleID == 1): ?>
                                        <td>
                                            <div class="small">
                                                <div class="fw-bold"><?= esc($service['tenant_name']) ?></div>
                                                <div class="text-muted"><?= esc($service['tenant_code']) ?></div>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($service['txtImage'])): ?>
                                                <img src="<?= base_url('uploads/services/' . $service['txtImage']) ?>"
                                                     alt="<?= esc($service['txtName']) ?>"
                                                     class="rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-2"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-box text-secondary"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="fw-semibold"><?= esc($service['txtName']) ?></div>
                                                <div class="small text-muted">
                                                    <?= substr(esc($service['txtDescription']), 0, 50) . (strlen($service['txtDescription']) > 50 ? '...' : '') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-tag me-1"></i>
                                            <?= esc($service['service_type_name']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= format_currency($service['decPrice']) ?></div>
                                        <?php if ($service['intDuration']): ?>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                <?= $service['intDuration'] ?> min
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold">
                                            <?php if ($service['intCapacity']): ?>
                                                <i class="bi bi-people me-1"></i>
                                                <?= $service['intCapacity'] ?> pax
                                            <?php else: ?>
                                                <span class="text-muted">No limit</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $service['bitActive'] ? 'success' : 'danger' ?> rounded-pill">
                                            <?= $service['bitActive'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?> <!-- end content section -->

<?= $this->section('scripts') ?>
<!-- Global variables for AJAX requests -->
<script>
    window.baseUrl = '<?= base_url() ?>';
    window.csrfName = '<?= csrf_token() ?>';
    window.csrfToken = '<?= csrf_hash() ?>';
</script>

<!-- Service management script -->
<script src="<?= base_url('assets/js/pages/tenant-services.js') ?>"></script>
<?= $this->endSection() ?> <!-- end scripts section -->
