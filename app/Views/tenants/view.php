<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mt-4 mb-0"><?= esc($tenant['txtTenantName']) ?></h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('tenants') ?>">Tenants</a></li>
                <li class="breadcrumb-item active">View Tenant</li>
            </ol>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('tenants/edit/' . $tenant['intTenantID']) ?>" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit Tenant
            </a>
            <a href="<?= base_url('tenants') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Basic Information Card -->
        <div class="col-xl-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-1"></i> Basic Information
                        </h6>
                        <?php if ($tenant['bitActive']): ?>
                            <span class="badge bg-success px-3 py-2">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger px-3 py-2">Inactive</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Logo and Quick Info -->
                        <div class="col-md-4 text-center">
                            <?php if (!empty($tenant['txtLogo'])): ?>
                                <img src="<?= base_url('uploads/tenants/' . $tenant['txtLogo']) ?>" 
                                     alt="<?= esc($tenant['txtTenantName']) ?>" 
                                     class="img-fluid rounded-circle mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-building text-secondary" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <h5 class="mb-2"><?= esc($tenant['txtTenantName']) ?></h5>
                            <p class="text-muted mb-0">Code: <?= esc($tenant['txtTenantCode']) ?></p>
                        </div>

                        <!-- Detailed Info -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Type</label>
                                    <p class="mb-2 fw-bold"><?= esc($tenant['service_type_name'] ?? 'N/A') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Status</label>
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
                                    <p class="mb-2">
                                        <span class="badge bg-<?= $statusClass ?> px-3 py-2">
                                            <?= ucfirst(str_replace('_', ' ', $tenant['txtStatus'])) ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">                                    <label class="text-muted small mb-1">Domain</label>
                                    <p class="mb-2">
                                        <?php if (!empty($tenant['txtDomain'])): ?>
                                            <?php $tenantUrl = generate_tenant_url($tenant['txtDomain']); ?>
                                            <a href="<?= $tenantUrl ?>" target="_blank" class="text-decoration-none">
                                                <i class="fas fa-external-link-alt me-1"></i>
                                                <?= $tenant['txtDomain'] ?>.<?= rtrim(preg_replace('#^https?://#', '', env('app.baseURL') ?: 'smartpricingandpaymentsystem.localhost.com'), '/') ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Not set</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Theme</label>
                                    <p class="mb-2">
                                        <i class="fas fa-palette me-1"></i>
                                        <?= ucfirst(esc($tenant['txtTheme'] ?? 'default')) ?>
                                    </p>
                                </div>
                            </div>

                            <?php
                            $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
                            $description = $settings['description'] ?? '';
                            if (!empty($description)): ?>
                            <div class="mt-3">
                                <label class="text-muted small mb-1">Description</label>
                                <p class="mb-0"><?= esc($description) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Info Card -->
        <div class="col-xl-4">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-crown me-1"></i> Subscription Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-4">
                        <div class="text-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width: 80px; height: 80px;">
                                <i class="fas <?= $tenant['txtSubscriptionPlan'] === 'enterprise' ? 'fa-building' : 
                                              ($tenant['txtSubscriptionPlan'] === 'premium' ? 'fa-star' : 
                                              ($tenant['txtSubscriptionPlan'] === 'basic' ? 'fa-check' : 'fa-gift')) ?> 
                                   text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="mb-1"><?= ucfirst($tenant['txtSubscriptionPlan']) ?> Plan</h5>
                            <span class="badge bg-<?= $tenant['txtSubscriptionStatus'] == 'active' ? 'success' : 'warning' ?>">
                                <?= ucfirst($tenant['txtSubscriptionStatus'] ?? 'inactive') ?>
                            </span>
                            <?php if ($tenant['txtSubscriptionStatus'] != 'active'): ?>
                                <div class="mt-3">
                                    <a href="<?= base_url('tenants/activate-subscription/' . $tenant['intTenantID']) ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-credit-card me-1"></i> 
                                        Activate Subscription
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="text-muted small mb-1">Trial Period Ends</div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-warning me-2"></i>
                                <strong><?= isset($tenant['dtmTrialEndsAt']) ? date('F d, Y', strtotime($tenant['dtmTrialEndsAt'])) : 'N/A' ?></strong>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="text-muted small mb-1">Created Date</div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-plus text-success me-2"></i>
                                <strong><?= date('F d, Y', strtotime($tenant['dtmCreatedDate'])) ?></strong>
                            </div>
                        </div>
                        <?php if (isset($tenant['dtmUpdatedDate'])): ?>
                        <div class="list-group-item px-0">
                            <div class="text-muted small mb-1">Last Updated</div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-check text-info me-2"></i>
                                <strong><?= date('F d, Y', strtotime($tenant['dtmUpdatedDate'])) ?></strong>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-1"></i> Quick Stats
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center h-100">
                                <div class="text-muted small mb-1">Total Services</div>
                                <h3 class="mb-0"><?= isset($services) ? count($services) : '0' ?></h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center h-100">
                                <div class="text-muted small mb-1">Active Bookings</div>
                                <h3 class="mb-0"><?= isset($activeBookings) ? $activeBookings : '0' ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services and Bookings Section -->
    <div class="row">
        <!-- Services Table -->
        <div class="col-xl-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-concierge-bell me-1"></i> Services
                        </h6>
                        <a href="<?= base_url('services/create?tenant_id=' . $tenant['intTenantID']) ?>" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle me-1"></i> Add Service
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (isset($services) && !empty($services)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Name</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Price</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($service['txtImage'])): ?>
                                                        <img src="<?= base_url('uploads/services/' . $service['txtImage']) ?>" 
                                                             class="rounded-circle me-2" 
                                                             width="40" height="40"
                                                             alt="<?= esc($service['txtName']) ?>">
                                                    <?php else: ?>
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-spa text-secondary"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?= esc($service['txtName']) ?></h6>
                                                        <small class="text-muted"><?= $service['intDuration'] ?> minutes</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?= esc($service['type_name'] ?? 'N/A') ?>
                                            </td>
                                            <td>
                                                <h6 class="mb-0">Rp <?= number_format($service['decPrice'], 0, ',', '.') ?></h6>
                                            </td>
                                            <td>
                                                <?php if ($service['bitActive'] == 1) : ?>
                                                    <span class="badge bg-success px-2 py-1">Active</span>
                                                <?php else : ?>
                                                    <span class="badge bg-danger px-2 py-1">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="<?= base_url('services/view/' . $service['intServiceID']) ?>" 
                                                       class="btn btn-light btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('services/edit/' . $service['intServiceID']) ?>" 
                                                       class="btn btn-light btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-5">
                            <img src="<?= base_url('assets/images/empty-services.svg') ?>" 
                                 alt="No services" 
                                 class="mb-3"
                                 style="width: 200px;">
                            <h6 class="text-muted mb-0">No services found</h6>
                            <p class="text-muted">Add your first service to get started</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="col-xl-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-alt me-1"></i> Recent Bookings
                        </h6>
                        <a href="<?= base_url('bookings?tenant_id=' . $tenant['intTenantID']) ?>" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-list me-1"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (isset($bookings) && !empty($bookings)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Booking Info</th>
                                        <th class="border-0">Service</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking) : ?>
                                        <tr>
                                            <td>
                                                <h6 class="mb-0">#<?= esc($booking['booking_code']) ?></h6>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($booking['booking_date'])) ?>
                                                </small>
                                            </td>
                                            <td><?= esc($booking['service_name']) ?></td>
                                            <td>
                                                <?php
                                                $bookingStatusClass = '';
                                                switch($booking['status']) {
                                                    case 'confirmed': $bookingStatusClass = 'success'; break;
                                                    case 'pending': $bookingStatusClass = 'warning'; break;
                                                    case 'cancelled': $bookingStatusClass = 'danger'; break;
                                                    case 'completed': $bookingStatusClass = 'info'; break;
                                                    default: $bookingStatusClass = 'secondary';
                                                }
                                                ?>
                                                <span class="badge bg-<?= $bookingStatusClass ?> px-2 py-1">
                                                    <?= ucfirst($booking['status']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= base_url('booking/view/' . $booking['id']) ?>" 
                                                   class="btn btn-light btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-5">
                            <img src="<?= base_url('assets/images/empty-bookings.svg') ?>" 
                                 alt="No bookings" 
                                 class="mb-3"
                                 style="width: 200px;">
                            <h6 class="text-muted mb-0">No bookings yet</h6>
                            <p class="text-muted">Bookings will appear here once created</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
