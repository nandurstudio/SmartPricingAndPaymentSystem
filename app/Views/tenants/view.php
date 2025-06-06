<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('tenant') ?>">Tenants</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle me-1"></i>
                            Tenant Information
                        </div>
                        <a href="<?= base_url('tenant/edit/' . $tenant['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title"><?= esc($tenant['txtTenantName']) ?></h5>
                            <p class="mb-0">
                                <span class="fw-bold">Type:</span> <?= esc($tenant['service_type_name'] ?? 'N/A') ?>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Status:</span>
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
                                <span class="badge bg-<?= $statusClass ?>"><?= ucfirst(str_replace('_', ' ', $tenant['txtStatus'])) ?></span>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Tenant Code:</span>
                                <code><?= esc($tenant['txtTenantCode'] ?? 'N/A') ?></code>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Domain:</span>
                                <code><?= esc($tenant['txtDomain'] ?? 'N/A') ?></code>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">GUID:</span>
                                <code><?= esc($tenant['txtGUID'] ?? 'N/A') ?></code>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0">
                                <span class="fw-bold">Subscription Plan:</span>
                                <span class="badge bg-primary"><?= ucfirst($tenant['txtSubscriptionPlan']) ?></span>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Subscription Status:</span>
                                <span class="badge bg-<?= $tenant['txtSubscriptionStatus'] == 'active' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($tenant['txtSubscriptionStatus'] ?? 'N/A') ?>
                                </span>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Trial Ends:</span>
                                <?= isset($tenant['dtmTrialEndsAt']) ? date('F d, Y', strtotime($tenant['dtmTrialEndsAt'])) : 'N/A' ?>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Created:</span>
                                <?= date('F d, Y', strtotime($tenant['dtmCreatedDate'])) ?>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Last Updated:</span>
                                <?= isset($tenant['dtmUpdatedDate']) ? date('F d, Y', strtotime($tenant['dtmUpdatedDate'])) : 'N/A' ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php
                    // Parse the JSON settings to extract description and other settings
                    $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
                    $description = $settings['description'] ?? '';
                    ?>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <span class="fw-bold">Description:</span>
                                <p class="mt-2"><?= esc($description) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <span class="fw-bold">Owner:</span>
                                <p class="mt-2"><?= esc($tenant['owner_name'] ?? 'N/A') ?> (<?= esc($tenant['owner_email'] ?? 'N/A') ?>)</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <span class="fw-bold">Theme:</span>
                                <p class="mt-2"><?= ucfirst(esc($tenant['txtTheme'] ?? 'default')) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-concierge-bell me-1"></i>
                            Services
                        </div>
                        <a href="<?= base_url('service/create?tenant_id=' . $tenant['id']) ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus-circle"></i> Add Service
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($services) && !empty($services)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service) : ?>
                                        <tr>
                                            <td><?= esc($service['name']) ?></td>
                                            <td><?= number_format($service['price'], 2) ?></td>
                                            <td>
                                                <?php if ($service['is_active'] == 1) : ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else : ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('service/view/' . $service['id']) ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            No services found for this tenant. Add your first service to get started.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-calendar-alt me-1"></i>
                            Recent Bookings
                        </div>
                        <a href="<?= base_url('booking?tenant_id=' . $tenant['id']) ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-list"></i> All Bookings
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($bookings) && !empty($bookings)) : ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking Code</th>
                                        <th>Service</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking) : ?>
                                        <tr>
                                            <td><?= esc($booking['booking_code']) ?></td>
                                            <td><?= esc($booking['service_name']) ?></td>
                                            <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                            <td>
                                                <?php if ($booking['status'] == 'confirmed') : ?>
                                                    <span class="badge bg-success">Confirmed</span>
                                                <?php elseif ($booking['status'] == 'pending') : ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php elseif ($booking['status'] == 'cancelled') : ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php elseif ($booking['status'] == 'completed') : ?>
                                                    <span class="badge bg-info">Completed</span>
                                                <?php else : ?>
                                                    <span class="badge bg-secondary"><?= ucfirst($booking['status']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            No recent bookings found for this tenant.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
