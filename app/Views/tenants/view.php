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
                <i class="bi bi-pencil-square me-1"></i> Edit Tenant
            </a>
            <a href="<?= base_url('tenants') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Basic Information Card -->
        <div class="col-xl-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="bi bi-info-circle me-1"></i> Basic Information
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <?php if ($tenant['txtStatus'] !== ($tenant['bitActive'] ? 'active' : 'inactive')): ?>
                                <!-- Account Status Badge -->
                                <span class="badge bg-<?= get_status_color($tenant['txtStatus']) ?>">
                                    <i class="bi bi-<?= get_status_icon($tenant['txtStatus']) ?> me-1"></i>
                                    <?= ucfirst($tenant['txtStatus']) ?>
                                </span>
                            <?php endif; ?>
                            <!-- Active/Inactive Badge -->
                            <span class="badge bg-<?= $tenant['bitActive'] ? 'success' : 'danger' ?> rounded-pill">
                                <i class="bi bi-<?= $tenant['bitActive'] ? 'check-circle' : 'x-circle' ?> me-1"></i>
                                <?= $tenant['bitActive'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Logo and Quick Info -->
                        <div class="col-md-4 text-center">
                            <div class="logo-wrapper mb-3">
                                <?php if (!empty($tenant['txtLogo']) && file_exists(FCPATH . 'uploads/tenants/' . $tenant['txtLogo'])): ?>
                                    <img src="<?= base_url('uploads/tenants/' . $tenant['txtLogo']) ?>"
                                        alt="<?= esc($tenant['txtTenantName']) ?>"
                                        class="img-thumbnail rounded-circle"
                                        style="width: 150px; height: 150px; object-fit: cover;"
                                        onerror="this.onerror=null; this.src='<?= base_url('assets/img/default-tenant.png') ?>'; this.classList.add('bg-light');">
                                <?php else: ?>
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 150px; height: 150px; border: 2px dashed #dee2e6;">
                                        <i class="bi bi-building text-secondary" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h5 class="fw-bold mb-1"><?= esc($tenant['txtTenantName']) ?></h5>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-key me-1"></i>
                                Code: <?= esc($tenant['txtTenantCode']) ?>
                            </p>
                            <?php if (!empty($tenant['txtDomain'])): ?>
                                <a href="<?= generate_tenant_url($tenant['txtDomain']) ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>
                                    Visit Tenant Portal
                                </a>
                            <?php endif; ?>
                        </div>

                        <!-- Detailed Info -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Business Type</label>
                                    <p class="mb-2 fw-bold">
                                        <i class="bi bi-shop me-1"></i>
                                        <?= esc($tenant['service_type_name'] ?? 'N/A') ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Service Plan</label>
                                    <p class="mb-2">
                                        <span class="badge bg-primary">
                                            <i class="bi bi-star<?= $tenant['txtSubscriptionPlan'] === 'premium' ? '-fill' : '' ?> me-1"></i>
                                            <?= ucfirst($tenant['txtSubscriptionPlan'] ?? 'Basic') ?>
                                        </span>
                                        <?php if ($tenant['txtSubscriptionStatus'] === 'active'): ?>
                                            <span class="badge bg-success ms-1">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Active Subscription
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-md-6"> <?php if ($tenant['txtSubscriptionStatus'] !== 'active'): ?>
                                        <label class="text-muted small mb-1">Trial Period</label>
                                        <p class="mb-2">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            <?php
                                                            $trialDate = strtotime($tenant['dtmTrialEndsAt']);
                                                            $plan = strtolower($tenant['txtSubscriptionPlan'] ?? 'free');

                                                            if ($plan === 'free' || !$trialDate || $trialDate <= strtotime('1980-01-01')) {
                                                                echo '<span class="text-muted">No trial period';
                                                                if ($plan === 'free') echo ' (Free Plan)';
                                                                echo '</span>';
                                                            } else {
                                                                if ($trialDate > time()) {
                                                                    $daysLeft = ceil(($trialDate - time()) / (60 * 60 * 24));
                                                                    echo '<span class="text-success">Active for ' . $daysLeft . ' more days';
                                                                    echo '<br><small class="text-muted">Until ' . date('d M Y', $trialDate) . '</small></span>';
                                                                } else {
                                                                    echo '<span class="text-danger">Trial ended on ' . date('d M Y', $trialDate) . '</span>';
                                                                }
                                                            }
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small mb-1">Theme</label>
                                    <p class="mb-2">
                                        <i class="bi bi-palette me-1"></i>
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

            <!-- Quick Stats Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="bi bi-graph-up me-1"></i> Quick Stats
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Total Services</div>
                                        <div class="h4 mb-0"><?= number_format(count($services)) ?></div>
                                    </div>
                                    <div class="text-primary">
                                        <i class="bi bi-box" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Active Services</div>
                                        <div class="h4 mb-0"><?= number_format(array_reduce($services, function ($carry, $service) {
                                                                    return $carry + ($service['bitActive'] ? 1 : 0);
                                                                }, 0)) ?></div>
                                    </div>
                                    <div class="text-success">
                                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Info Card -->
        <div class="col-xl-4">
            <!-- Subscription Status Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="bi bi-star me-1"></i> Subscription
                        </h6>
                        <?php
                        $subscription = [
                            'plan' => $tenant['txtSubscriptionPlan'] ?? 'free',
                            'status' => $tenant['txtSubscriptionStatus'] ?? 'inactive',
                            'ends_at' => $tenant['dtmSubscriptionEndDate'] ?? null
                        ];

                        $badgeClass = match ($subscription['status']) {
                            'active' => 'bg-success',
                            'pending_payment' => 'bg-warning',
                            'payment_failed' => 'bg-danger',
                            default => 'bg-secondary'
                        };

                        $icon = match ($subscription['status']) {
                            'active' => 'check-circle',
                            'pending_payment' => 'clock',
                            'payment_failed' => 'exclamation-circle',
                            default => 'dash-circle'
                        };
                        ?>
                        <span class="badge <?= $badgeClass ?> rounded-pill">
                            <i class="bi bi-<?= $icon ?> me-1"></i>
                            <?= ucfirst(str_replace('_', ' ', $subscription['status'])) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Current Plan Info -->
                    <div class="text-center">
                        <div class="plan-icon rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; background: var(--bs-primary-bg-subtle);">
                            <i class="bi <?= $subscription['plan'] === 'enterprise' ? 'bi-stars' : ($subscription['plan'] === 'premium' ? 'bi-star-fill' : ($subscription['plan'] === 'basic' ? 'bi-star-half' : 'bi-star')) ?> 
                               text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-2"><?= ucfirst($subscription['plan']) ?> Plan</h5>
                        <p class="text-muted small mb-3">
                            <?php if ($subscription['status'] === 'active'): ?>
                                Your subscription is active and running
                                <?php if ($subscription['ends_at']): ?>
                                    <br>
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Expires on <?= date('F d, Y', strtotime($subscription['ends_at'])) ?>
                                <?php endif; ?>
                            <?php elseif ($subscription['status'] === 'pending_payment'): ?>
                                Waiting for payment confirmation
                            <?php else: ?>
                                Your subscription is not active
                            <?php endif; ?>
                        </p> <?php if ($subscription['plan'] === 'free'): ?> <button type="button" class="btn btn-primary" onclick="showUpgradeOptions(<?= $tenant['intTenantID'] ?>, '<?= $tenant['txtSubscriptionPlan'] ?>')">
                                <i class="bi bi-arrow-up-circle me-1"></i>
                                Upgrade Plan
                            </button>
                        <?php elseif ($subscription['status'] !== 'active'): ?>
                            <a href="<?= rtrim(base_url(), '/') . '/tenants/activate-subscription/' . $tenant['intTenantID'] ?>"
                                class="btn btn-primary">
                                <i class="bi bi-credit-card me-1"></i>
                                Activate Subscription
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Subscription Timeline -->
                    <div class="subscription-timeline mt-4">
                        <h6 class="fw-bold mb-3 text-dark">
                            <i class="bi bi-clock-history me-1"></i> Timeline
                        </h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small mb-1">Trial Status</div>
                                        <?php
                                        $trialEndsAt = $tenant['dtmTrialEndsAt'] ?? null;
                                        $trialDate = !empty($trialEndsAt) ? strtotime($trialEndsAt) : null;
                                        $plan = strtolower($tenant['txtSubscriptionPlan'] ?? 'free');
                                        $isSubscriptionActive = $tenant['txtSubscriptionStatus'] === 'active';

                                        // Only show trial info if subscription is not active
                                        if ($isSubscriptionActive): ?>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-check-circle text-success me-2"></i>
                                                <strong>Active Subscription</strong>
                                            </div>
                                        <?php elseif ($plan === 'free' || !$trialDate || ($trialDate && $trialDate <= strtotime('1980-01-01'))): ?>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-x-circle text-secondary me-2"></i>
                                                <strong class="text-muted">
                                                    No trial period<?= $plan === 'free' ? ' (Free Plan)' : '' ?>
                                                </strong>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock <?= $trialDate > time() ? 'text-warning' : 'text-danger' ?> me-2"></i>
                                                <div>
                                                    <strong>
                                                        <?= date('F d, Y', $trialDate) ?>
                                                    </strong>
                                                    <?php if ($trialDate > time()): ?>
                                                        <div class="small text-success">
                                                            <?= ceil(($trialDate - time()) / (60 * 60 * 24)) ?> days remaining
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!$isSubscriptionActive && !($plan === 'free' || !$trialDate || $trialDate <= strtotime('1980-01-01'))): ?>
                                        <?php if ($trialDate > time()): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                Trial Active (<?= match ($plan) {
                                                                    'basic' => '14 Days',
                                                                    'premium' => '14 Days',
                                                                    'enterprise' => '30 Days',
                                                                    default => 'Custom'
                                                                } ?>)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-hourglass me-1"></i>
                                                Trial Ended
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="list-group-item px-0">
                                <div class="text-muted small mb-1">Created Date</div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-plus text-success me-2"></i>
                                    <strong><?= date('F d, Y', strtotime($tenant['dtmCreatedDate'])) ?></strong>
                                </div>
                            </div>
                            <?php if (isset($tenant['dtmUpdatedDate'])): ?>
                                <div class="list-group-item px-0">
                                    <div class="text-muted small mb-1">Last Updated</div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-check text-info me-2"></i>
                                        <strong><?= date('F d, Y', strtotime($tenant['dtmUpdatedDate'])) ?></strong>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Services and Bookings Section -->
    <div class="row">
        <!-- Services Table -->
        <div class="col-xl-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="bi bi-list-check me-1"></i> Available Services
                        </h6>
                        <a href="<?= base_url('services/create/' . $tenant['intTenantID']) ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Service
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($services)): ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-clipboard-x text-muted" style="font-size: 2.5rem;"></i>
                            </div>
                            <h6 class="fw-bold">No Services Available</h6>
                            <p class="text-muted small mb-0">This tenant hasn't added any services yet.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Actions</th>
                                        <th>Service</th>
                                        <th>Type</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service): ?>
                                        <tr>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= base_url('services/edit/' . $service['intServiceID']) ?>"
                                                        class="btn btn-outline-primary"
                                                        title="Edit Service">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-outline-danger toggle-service"
                                                        data-id="<?= $service['intServiceID'] ?>"
                                                        data-status="<?= $service['bitActive'] ?>"
                                                        title="<?= $service['bitActive'] ? 'Deactivate' : 'Activate' ?> Service">
                                                        <i class="bi bi-toggle-<?= $service['bitActive'] ? 'on' : 'off' ?>"></i>
                                                    </button>
                                                </div>
                                            </td>
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
                                                <span class="badge bg-info text-dark">
                                                    <i class="bi bi-tag me-1"></i>
                                                    <?= esc($service['service_type_name']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold">
                                                    <?= format_currency($service['decPrice']) ?>
                                                </div>
                                            </td>
                                            <td class="service-status">
                                                <span class="badge bg-<?= $service['bitActive'] ? 'success' : 'danger' ?>">
                                                    <i class="bi bi-<?= $service['bitActive'] ? 'check-circle' : 'x-circle' ?> me-1"></i>
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

        <!-- Recent Bookings -->
        <div class="col-xl-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="bi bi-calendar2-check me-1"></i> Recent Bookings
                        </h6>
                        <a href="<?= base_url('bookings/tenant/' . $tenant['intTenantID']) ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-list-ul me-1"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Booking Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Total Bookings</div>
                                        <div class="h4 mb-0"><?= number_format($bookingStats['total']) ?></div>
                                    </div>
                                    <div class="text-primary">
                                        <i class="bi bi-calendar2-event" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small">Pending Bookings</div>
                                        <div class="h4 mb-0"><?= number_format($bookingStats['pending']) ?></div>
                                    </div>
                                    <div class="text-warning">
                                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Bookings List -->
                    <?php if (empty($recentBookings)): ?>
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="bi bi-calendar2-x text-muted" style="font-size: 2.5rem;"></i>
                            </div>
                            <h6 class="fw-bold">No Recent Bookings</h6>
                            <p class="text-muted small mb-0">There are no bookings to display at this time.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentBookings as $booking): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <?= esc($booking['customer_name']) ?>
                                                <span class="badge bg-<?= get_booking_status_color($booking['txtStatus']) ?> ms-2">
                                                    <?= ucfirst($booking['txtStatus']) ?>
                                                </span>
                                            </h6>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-calendar2 me-1"></i>
                                                <?= date('d M Y, H:i', strtotime($booking['dtmBookingDate'])) ?>
                                            </p>
                                        </div>
                                        <a href="<?= base_url('bookings/view/' . $booking['intBookingID']) ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/tenant-upgrade.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Provide necessary variables for the external JS -->
<script>
    const baseUrl = '<?= base_url() ?>';
    const csrfToken = '<?= csrf_hash() ?>';
    const csrfName = '<?= csrf_token() ?>';
    const tenantId = '<?= $tenant['intTenantID'] ?>';
</script>

<!-- Load external JS files -->
<script src="<?= base_url('assets/js/pages/tenant-services.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/tenant-upgrade.js') ?>"></script>
<?= $this->endSection() ?>