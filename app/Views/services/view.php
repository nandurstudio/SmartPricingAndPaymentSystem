<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('services') ?>">Services</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-info-circle me-1"></i>
                            Service Information
                        </div>
                        <div>
                            <a href="<?= base_url('services/edit/' . $service['intServiceID']) ?>" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>                            <a href="<?= base_url('schedules?service_id=' . $service['intServiceID']) ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-calendar"></i> Schedules
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title"><?= esc($service['txtName']) ?></h5>
                            <p class="mb-0">
                                <span class="fw-bold">Service Type:</span> <?= esc($service['type_name'] ?? 'N/A') ?>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Tenant:</span> 
                                <a href="<?= base_url('tenants/view/' . $service['intTenantID']) ?>"><?= esc($service['tenant_name'] ?? 'N/A') ?></a>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Price:</span> Rp <?= number_format($service['decPrice'], 2) ?>
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Duration:</span> <?= $service['intDuration'] ?> minutes
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Capacity:</span> <?= $service['intCapacity'] ?? '1' ?> person(s)
                            </p>
                            <p class="mb-0">
                                <span class="fw-bold">Status:</span>
                                <?php if ($service['bitActive'] == 1) : ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else : ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if (!empty($service['txtImagePath'])) : ?>
                                <img src="<?= base_url('uploads/services/' . $service['txtImagePath']) ?>" 
                                     alt="<?= esc($service['txtName']) ?>" 
                                     class="img-fluid rounded">
                            <?php else : ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 150px;">
                                    <i class="fas fa-spa text-secondary" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold">Description</h6>
                    <p class="mb-0"><?= nl2br(esc($service['txtDescription'])) ?></p>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Recent Bookings
                </div>
                <div class="card-body">
                    <?php if (!empty($bookings)) : ?>
                        <div class="list-group">
                            <?php foreach ($bookings as $booking) : ?>
                                <a href="<?= base_url('bookings/view/' . $booking['intBookingID']) ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= esc($booking['customer_name'] ?? 'Guest') ?></h6>
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
                                    <small><?= date('M d, Y', strtotime($booking['dtmBookingDate'])) ?> at <?= date('h:i A', strtotime($booking['dtmStartTime'])) ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('bookings?service_id=' . $service['intServiceID']) ?>" class="btn btn-sm btn-outline-primary">View All Bookings</a>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            No bookings found for this service.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-tools me-1"></i>
                    Quick Actions
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="<?= base_url('schedule/special?service_id=' . $service['intServiceID']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Special Schedule Days</h6>
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <small class="text-muted">Add holidays or special operating hours</small>
                        </a>
                        <a href="<?= base_url('bookings/create?service_id=' . $service['intServiceID']) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Create Booking</h6>
                                <i class="fas fa-calendar-plus"></i>
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
