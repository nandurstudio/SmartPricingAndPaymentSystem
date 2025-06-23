<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">
        <i class="bi bi-eye text-primary me-2"></i>
        <?= $pageTitle ?>
    </h1>
    <ol class="breadcrumb mb-4" aria-label="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('services') ?>">Services</a></li>
        <li class="breadcrumb-item active" aria-current="page">View</li>
    </ol>
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-info-circle me-1"></i>
                        Service Information
                    </div>
                    <div>
                        <a href="<?= base_url('services/edit/' . $service['intServiceID']) ?>" class="btn btn-warning btn-sm me-1">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        <a href="<?= base_url('schedules?service_id=' . $service['intServiceID']) ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-calendar2-week me-1"></i> Schedules
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title mb-2 fw-bold">
                                <?= esc($service['txtName']) ?>
                                <?php if ($service['bitActive'] == 1) : ?>
                                    <span class="badge bg-success ms-2">Active</span>
                                <?php else : ?>
                                    <span class="badge bg-danger ms-2">Inactive</span>
                                <?php endif; ?>
                            </h5>
                            <div class="mb-2">
                                <span class="fw-bold"><i class="bi bi-tag me-1"></i>Service Type:</span> <?= esc($service['service_type_name'] ?? 'N/A') ?>
                            </div>
                            <div class="mb-2">
                                <span class="fw-bold"><i class="bi bi-building me-1"></i>Tenant:</span>
                                <a href="<?= base_url('tenants/view/' . $service['intTenantID']) ?>"><?= esc($service['tenant_name'] ?? 'N/A') ?></a>
                            </div>
                            <div class="mb-2">
                                <span class="fw-bold"><i class="bi bi-cash-coin me-1"></i>Price:</span> Rp <?= number_format($service['decPrice'], 2) ?>
                            </div>
                            <div class="mb-2">
                                <span class="fw-bold"><i class="bi bi-clock me-1"></i>Duration:</span> <?= $service['intDuration'] ?> minutes
                            </div>
                            <div class="mb-2">
                                <span class="fw-bold"><i class="bi bi-people me-1"></i>Capacity:</span> <?= $service['intCapacity'] ?? '1' ?> person(s)
                            </div>
                            <div class="mb-2">
                                <span class="fw-bold"><i class="bi bi-hash me-1"></i>GUID:</span> <code><?= esc($service['txtGUID']) ?></code>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if (!empty($service['txtImage'])) : ?>
                                <img src="<?= base_url('uploads/services/' . $service['txtImage']) ?>"
                                     alt="<?= esc($service['txtName']) ?>"
                                     class="img-fluid rounded shadow-sm border" style="max-height:180px;">
                            <?php else : ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center border" 
                                     style="height: 150px;">
                                    <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-2"><i class="bi bi-card-text me-1"></i>Description</h6>
                    <p class="mb-0 small text-muted"><?= nl2br(esc($service['txtDescription'])) ?></p>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <i class="bi bi-calendar2-week me-1"></i>
                    Recent Bookings
                </div>
                <div class="card-body">
                    <?php if (!empty($bookings)) : ?>
                        <div class="list-group">
                            <?php foreach ($bookings as $booking) : ?>
                                <a href="<?= base_url('bookings/view/' . $booking['intBookingID']) ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><i class="bi bi-person-circle me-1"></i><?= esc($booking['customer_name'] ?? 'Guest') ?></h6>
                                        <small>
                                            <?php if ($booking['txtStatus'] == 'confirmed') : ?>
                                                <span class="badge bg-success">Confirmed</span>
                                            <?php elseif ($booking['txtStatus'] == 'pending') : ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif ($booking['txtStatus'] == 'cancelled') : ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php elseif ($booking['txtStatus'] == 'completed') : ?>
                                                <span class="badge bg-info">Completed</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <small><i class="bi bi-calendar-event me-1"></i><?= date('M d, Y', strtotime($booking['dtmBookingDate'])) ?> at <?= date('H:i', strtotime($booking['dtmStartTime'])) ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('bookings?service_id=' . $service['intServiceID']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-list me-1"></i> View All Bookings</a>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-1"></i> No bookings found for this service.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <i class="bi bi-lightning me-1"></i>
                    Quick Actions
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?= base_url('schedule/special?service_id=' . $service['intServiceID']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Special Schedule Days</h6>
                                <i class="bi bi-calendar2-event"></i>
                            </div>
                            <small class="text-muted">Add holidays or special operating hours</small>
                        </a>
                        <a href="<?= base_url('bookings/create?service_id=' . $service['intServiceID']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Create Booking</h6>
                                <i class="bi bi-calendar-plus"></i>
                            </div>
                            <small class="text-muted">Add a new booking for this service</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
