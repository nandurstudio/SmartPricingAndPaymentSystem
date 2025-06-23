<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="bi bi-building text-primary me-2"></i>
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
                                Total Tenants
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count($tenants) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-buildings fa-2x text-gray-300"></i>
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
                                Active Tenants
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($tenants, fn($tenant) => $tenant['bitActive'] == 1)) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-shop fa-2x text-gray-300"></i>
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
                                Inactive Tenants
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= count(array_filter($tenants, fn($tenant) => $tenant['bitActive'] == 0)) ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-shop-window fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Subscriptions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $activeSubscriptions = array_filter($tenants, function($tenant) {
                                    return $tenant['txtSubscriptionStatus'] === 'active';
                                });
                                echo count($activeSubscriptions) . ' / ' . count($tenants);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet2 fa-2x text-gray-300"></i>
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
                        <i class="bi bi-buildings me-1 text-primary"></i>
                        Manage Tenants
                    </h5>
                    <div class="small text-muted">View and manage all tenant accounts</div>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group" role="group" aria-label="View options">
                        <button type="button" class="btn btn-outline-primary btn-sm active" data-view="table" id="table-view">
                            <i class="bi bi-list-ul me-1"></i> List View
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-view="grid" id="grid-view">
                            <i class="bi bi-grid-3x3-gap me-1"></i> Grid View
                        </button>
                    </div>
                    <a href="<?= base_url('tenants/create') ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add New Tenant
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Table View -->
            <div id="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tenantsTable">
                        <thead class="table-light">
                            <tr>                                <th style="width: 120px">Actions</th>
                                <th>Business Details</th>
                                <th>Owner Contact</th>
                                <th>Description & Type</th>
                                <th style="width: 120px">Status</th>
                                <th style="width: 150px">Subscription</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tenants as $tenant): ?>
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('tenants/view/' . $tenant['intTenantID']) ?>" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="View Tenant Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('tenants/edit/' . $tenant['intTenantID']) ?>" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Edit Tenant">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-<?= $tenant['bitActive'] ? 'danger' : 'success' ?> toggle-status" 
                                                data-id="<?= $tenant['intTenantID'] ?>" 
                                                data-status="<?= $tenant['bitActive'] ?>"
                                                data-bs-toggle="tooltip"
                                                title="<?= $tenant['bitActive'] ? 'Deactivate' : 'Activate' ?> Tenant">
                                            <i class="bi <?= $tenant['bitActive'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>                                    <div class="d-flex align-items-center">
                                        <div class="tenant-avatar rounded-3 me-3" style="width: 48px; height: 48px;">
                                            <?php if (!empty($tenant['txtLogo'])): ?>
                                                <img src="<?= base_url('uploads/tenants/' . $tenant['txtLogo']) ?>" 
                                                     alt="<?= esc($tenant['txtTenantName']) ?>"
                                                     class="rounded-3"
                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="bi bi-building text-primary fs-4"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= esc($tenant['txtTenantName']) ?></div>
                                            <div class="small text-muted">ID: <?= esc($tenant['txtTenantCode']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>                                    <div class="small">
                                        <?php if (!empty($tenant['owner_email'])): ?>
                                            <div><i class="bi bi-envelope me-1"></i> <?= esc($tenant['owner_email']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($tenant['owner_name'])): ?>
                                            <div><i class="bi bi-person me-1"></i> <?= esc($tenant['owner_name']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>                                <td>
                                    <div class="small">
                                        <?php 
                                        $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
                                        if (!empty($settings['description'])): 
                                        ?>
                                            <div class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <?= nl2br(esc(mb_strimwidth($settings['description'], 0, 100, '...'))) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($tenant['service_type_name'])): ?>
                                            <div class="mt-1">
                                                <i class="bi bi-tag me-1"></i>
                                                <?= esc($tenant['service_type_name']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-<?= $tenant['bitActive'] ? 'success' : 'danger' ?> rounded-pill">
                                            <i class="bi bi-<?= $tenant['bitActive'] ? 'check-circle' : 'x-circle' ?> me-1"></i>
                                            <?= $tenant['bitActive'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </div>
                                </td>                                <td>
                                    <?php
                                    $paymentSettings = json_decode($tenant['jsonPaymentSettings'] ?? '{}', true);
                                    $subscription = [
                                        'plan' => $tenant['txtSubscriptionPlan'] ?? 'free',
                                        'status' => $tenant['txtSubscriptionStatus'] ?? 'inactive',
                                        'ends_at' => $tenant['dtmSubscriptionEndDate'] ?? null
                                    ];
                                    
                                    $badgeClass = match($subscription['status']) {
                                        'active' => 'bg-success',
                                        'pending_payment' => 'bg-warning',
                                        'payment_failed' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                    
                                    $icon = match($subscription['plan']) {
                                        'enterprise' => 'stars',
                                        'premium' => 'star-fill',
                                        'basic' => 'star-half',
                                        default => 'star'
                                    };
                                    ?>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge <?= $badgeClass ?> rounded-pill">
                                            <i class="bi bi-<?= $icon ?> me-1"></i>
                                            <?= ucfirst($subscription['plan']) ?>
                                        </span>
                                        <?php if ($subscription['ends_at']): ?>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                Until <?= date('d M Y', strtotime($subscription['ends_at'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($tenant['dtmUpdatedDate']): ?>
                                        <div class="small">
                                            <div>
                                                <i class="bi bi-person me-1"></i>
                                                <?= esc($tenant['txtUpdatedBy']) ?>
                                            </div>
                                            <div class="text-muted">
                                                <i class="bi bi-clock-history me-1"></i>
                                                <?= date('M d, Y H:i', strtotime($tenant['dtmUpdatedDate'])) ?>
                                            </div>
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

            <!-- Grid View -->
            <div id="grid-container" style="display: none;">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    <?php foreach ($tenants as $tenant): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="tenant-avatar bg-light rounded-3 p-2 me-3">
                                            <i class="bi bi-building text-primary fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= esc($tenant['txtTenantName']) ?></h6>
                                            <small class="text-muted"><?= esc($tenant['txtTenantCode']) ?></small>
                                        </div>
                                    </div>
                                    <span class="badge bg-<?= $tenant['bitActive'] ? 'success' : 'danger' ?> rounded-pill">
                                        <?= $tenant['bitActive'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                                  <div class="mb-3">
                                    <?php if (!empty($tenant['owner_email'])): ?>
                                        <div class="small mb-2">
                                            <i class="bi bi-envelope me-1"></i> <?= esc($tenant['owner_email']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($tenant['owner_name'])): ?>
                                        <div class="small mb-2">
                                            <i class="bi bi-person me-1"></i> <?= esc($tenant['owner_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php
                                    $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
                                    if (!empty($settings['description'])):
                                    ?>
                                        <div class="small text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            <?= nl2br(esc(mb_strimwidth($settings['description'], 0, 100, '...'))) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($tenant['service_type_name'])): ?>
                                        <div class="small mt-2">
                                            <i class="bi bi-tag me-1"></i>
                                            <?= esc($tenant['service_type_name']) ?>
                                        </div>
                                    <?php endif; ?>                                </div>

                                <?php
                                $paymentSettings = json_decode($tenant['jsonPaymentSettings'] ?? '{}', true);
                                $subscription = [
                                    'plan' => $tenant['txtSubscriptionPlan'] ?? 'free',
                                    'status' => $tenant['txtSubscriptionStatus'] ?? 'inactive',
                                    'ends_at' => $tenant['dtmSubscriptionEndDate'] ?? null
                                ];
                                
                                $badgeClass = match($subscription['status']) {
                                    'active' => 'bg-success',
                                    'pending_payment' => 'bg-warning',
                                    'payment_failed' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                
                                $icon = match($subscription['plan']) {
                                    'enterprise' => 'stars',
                                    'premium' => 'star-fill',
                                    'basic' => 'star-half',
                                    default => 'star'
                                };
                                ?>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <small class="text-muted">Subscription</small>
                                        <div>
                                            <span class="badge <?= $badgeClass ?> rounded-pill">
                                                <i class="bi bi-<?= $icon ?> me-1"></i>
                                                <?= ucfirst($subscription['plan']) ?>
                                            </span>
                                        </div>
                                        <?php if ($subscription['ends_at']): ?>
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-calendar-event me-1"></i>
                                                Until <?= date('d M Y', strtotime($subscription['ends_at'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($tenant['dtmUpdatedDate']): ?>
                                    <div class="text-end">
                                        <small class="text-muted d-block">Last updated</small>
                                        <small><?= date('M d, Y', strtotime($tenant['dtmUpdatedDate'])) ?></small>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('tenants/view/' . $tenant['intTenantID']) ?>" 
                                       class="btn btn-sm btn-outline-primary flex-grow-1">
                                        <i class="bi bi-eye me-1"></i> View Details
                                    </a>
                                    <div class="btn-group">
                                        <a href="<?= base_url('tenants/edit/' . $tenant['intTenantID']) ?>" 
                                           class="btn btn-sm btn-outline-primary"
                                           data-bs-toggle="tooltip"
                                           title="Edit Tenant">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-<?= $tenant['bitActive'] ? 'danger' : 'success' ?> toggle-status" 
                                                data-id="<?= $tenant['intTenantID'] ?>" 
                                                data-status="<?= $tenant['bitActive'] ?>"
                                                data-bs-toggle="tooltip"
                                                title="<?= $tenant['bitActive'] ? 'Deactivate' : 'Activate' ?> Tenant">
                                            <i class="bi <?= $tenant['bitActive'] ? 'bi-toggle-on' : 'bi-toggle-off' ?>"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
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

<!-- Tenant management script -->
<script src="<?= base_url('assets/js/pages/tenants.js') ?>"></script>
<?= $this->endSection() ?> <!-- end scripts section -->
