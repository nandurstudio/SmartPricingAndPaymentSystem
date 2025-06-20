<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mt-4 mb-0"><?= $pageTitle ?></h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active"><?= $pageTitle ?></li>
            </ol>
        </div>
        <div>
            <a href="<?= base_url('tenants/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Create New Tenant
            </a>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-building me-1"></i>
                    <?= $pageTitle ?>
                </h6>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-sm" data-view="grid" id="grid-view">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button type="button" class="btn btn-light btn-sm active" data-view="table" id="table-view">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($tenants)) : ?>
                <!-- Table View -->
                <div id="table-view-content">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 text-center">Actions</th>
                                    <th class="border-0">Tenant</th>
                                    <th class="border-0">Type</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Subscription</th>
                                    <th class="border-0">Owner</th>
                                    <th class="border-0">Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tenants as $tenant) : ?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="<?= base_url('tenants/view/' . $tenant['intTenantID']) ?>" 
                                                   class="btn btn-light btn-sm" 
                                                   data-bs-toggle="tooltip" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('tenants/edit/' . $tenant['intTenantID']) ?>" 
                                                   class="btn btn-light btn-sm"
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit Tenant">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($tenant['txtDomain']): ?>
                                                <a href="<?= generate_tenant_url($tenant['txtDomain']) ?>" 
                                                   target="_blank"
                                                   class="btn btn-light btn-sm"
                                                   data-bs-toggle="tooltip" 
                                                   title="Visit Website">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($tenant['txtLogo'])): ?>
                                                    <img src="<?= base_url('uploads/tenants/' . $tenant['txtLogo']) ?>" 
                                                         alt="<?= esc($tenant['txtTenantName']) ?>"
                                                         class="rounded-circle me-2"
                                                         width="40" height="40"
                                                         style="object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-building text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?= esc($tenant['txtTenantName']) ?></h6>
                                                    <small class="text-muted"><?= esc($tenant['txtTenantCode']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark px-3 py-2">
                                                <?= isset($tenant['service_type_name']) ? esc($tenant['service_type_name']) : 'N/A' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $statusClass = 'secondary';
                                            switch($tenant['txtStatus']) {
                                                case 'active': $statusClass = 'success'; break;
                                                case 'inactive': $statusClass = 'danger'; break;
                                                case 'suspended': $statusClass = 'warning'; break;
                                                case 'pending': $statusClass = 'info'; break;
                                                case 'pending_verification': $statusClass = 'primary'; break;
                                                case 'pending_payment': $statusClass = 'dark'; break;
                                                case 'payment_failed': $statusClass = 'danger'; break;
                                            }
                                            ?>
                                            <span class="badge bg-<?= $statusClass ?> px-3 py-2">
                                                <?= ucfirst(str_replace('_', ' ', $tenant['txtStatus'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas <?= $tenant['txtSubscriptionPlan'] === 'enterprise' ? 'fa-building' : 
                                                                  ($tenant['txtSubscriptionPlan'] === 'premium' ? 'fa-star' : 
                                                                  ($tenant['txtSubscriptionPlan'] === 'basic' ? 'fa-check' : 'fa-gift')) ?> 
                                                   text-primary me-2"></i>
                                                <div>
                                                    <h6 class="mb-0"><?= ucfirst(esc($tenant['txtSubscriptionPlan'])) ?></h6>
                                                    <small class="<?= $tenant['txtSubscriptionStatus'] == 'active' ? 'text-success' : 'text-warning' ?>">
                                                        <?= ucfirst($tenant['txtSubscriptionStatus'] ?? 'inactive') ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-secondary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">
                                                        <?= isset($tenant['owner_name']) ? esc($tenant['owner_name']) : session()->get('userFullName') ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <?= isset($tenant['owner_email']) ? esc($tenant['owner_email']) : session()->get('userEmail') ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">
                                                    <?= date('M d, Y', strtotime($tenant['dtmCreatedDate'])) ?>
                                                </h6>
                                                <small class="text-muted">
                                                    <?= date('h:i A', strtotime($tenant['dtmCreatedDate'])) ?>
                                                </small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Grid View -->
                <div id="grid-view-content" class="d-none">
                    <div class="row g-4">
                        <?php foreach ($tenants as $tenant) : ?>
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="position-relative">
                                        <?php if (!empty($tenant['txtLogo'])): ?>
                                            <img src="<?= base_url('uploads/tenants/' . $tenant['txtLogo']) ?>" 
                                                 class="card-img-top"
                                                 alt="<?= esc($tenant['txtTenantName']) ?>"
                                                 style="height: 160px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                 style="height: 160px;">
                                                <i class="fas fa-building text-secondary" style="font-size: 3rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        <?php 
                                        $statusClass = 'secondary';
                                        switch($tenant['txtStatus']) {
                                            case 'active': $statusClass = 'success'; break;
                                            case 'inactive': $statusClass = 'danger'; break;
                                            case 'suspended': $statusClass = 'warning'; break;
                                            case 'pending': $statusClass = 'info'; break;
                                            case 'pending_verification': $statusClass = 'primary'; break;
                                            case 'pending_payment': $statusClass = 'dark'; break;
                                            case 'payment_failed': $statusClass = 'danger'; break;
                                        }
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?> position-absolute top-0 end-0 m-2 px-2 py-1">
                                            <?= ucfirst(str_replace('_', ' ', $tenant['txtStatus'])) ?>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-1">
                                            <?= esc($tenant['txtTenantName']) ?>
                                        </h5>
                                        <p class="text-muted small mb-2">
                                            <?= isset($tenant['service_type_name']) ? esc($tenant['service_type_name']) : 'N/A' ?>
                                        </p>
                                        
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas <?= $tenant['txtSubscriptionPlan'] === 'enterprise' ? 'fa-building' : 
                                                              ($tenant['txtSubscriptionPlan'] === 'premium' ? 'fa-star' : 
                                                              ($tenant['txtSubscriptionPlan'] === 'basic' ? 'fa-check' : 'fa-gift')) ?> 
                                               text-primary me-2"></i>
                                            <span><?= ucfirst(esc($tenant['txtSubscriptionPlan'])) ?></span>
                                        </div>
                                        
                                        <div class="d-flex align-items-center small text-muted">
                                            <i class="fas fa-calendar me-2"></i>
                                            <?= date('M d, Y', strtotime($tenant['dtmCreatedDate'])) ?>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a href="<?= base_url('tenants/view/' . $tenant['intTenantID']) ?>" 
                                                   class="btn btn-light btn-sm" 
                                                   data-bs-toggle="tooltip" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('tenants/edit/' . $tenant['intTenantID']) ?>" 
                                                   class="btn btn-light btn-sm"
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit Tenant">
                                                    <i class="fas fa-edit"></i>
                                                </a>                                                <?php if ($tenant['txtDomain']): ?>
                                                <a href="<?= generate_tenant_url($tenant['txtDomain']) ?>" 
                                                   target="_blank"
                                                   class="btn btn-light btn-sm"
                                                   data-bs-toggle="tooltip" 
                                                   title="Visit Website">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($tenant['bitActive']): ?>
                                                <span class="badge bg-success px-2 py-1">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger px-2 py-1">Inactive</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php else : ?>
                <div class="text-center py-5">
                    <img src="<?= base_url('assets/images/empty-tenants.svg') ?>" 
                         alt="No tenants" 
                         class="mb-3"
                         style="width: 200px;">
                    <h4 class="text-muted mb-3">No tenants found</h4>
                    <p class="text-muted mb-4">Create your first tenant to get started</p>
                    <a href="<?= base_url('tenants/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Create New Tenant
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // View switcher
    const gridViewBtn = document.getElementById('grid-view');
    const tableViewBtn = document.getElementById('table-view');
    const gridContent = document.getElementById('grid-view-content');
    const tableContent = document.getElementById('table-view-content');

    gridViewBtn.addEventListener('click', function() {
        gridContent.classList.remove('d-none');
        tableContent.classList.add('d-none');
        gridViewBtn.classList.add('active');
        tableViewBtn.classList.remove('active');
        localStorage.setItem('tenantViewPreference', 'grid');
    });

    tableViewBtn.addEventListener('click', function() {
        tableContent.classList.remove('d-none');
        gridContent.classList.add('d-none');
        tableViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        localStorage.setItem('tenantViewPreference', 'table');
    });

    // Load user's view preference
    const viewPreference = localStorage.getItem('tenantViewPreference');
    if (viewPreference === 'grid') {
        gridViewBtn.click();
    }
});
</script>

<?= $this->endSection() ?>
