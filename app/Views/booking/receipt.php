<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $pageTitle ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('bookings') ?>">Bookings</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('bookings/view/' . $booking['id']) ?>"><?= $booking['booking_code'] ?></a></li>
        <li class="breadcrumb-item active">Receipt</li>
    </ol>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Receipt Header -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="mb-1"><?= esc($booking['tenant_name']) ?></h4>
                            <p class="mb-1"><?= esc($booking['tenant_email']) ?></p>
                            <p class="mb-0"><?= esc($booking['tenant_phone']) ?></p>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-1">Receipt</h5>
                            <p class="mb-1">#<?= esc($booking['booking_code']) ?></p>
                            <p class="mb-0">Date: <?= date('M d, Y', strtotime($booking['payment_date'])) ?></p>
                        </div>
                    </div>
                    
                    <!-- Customer Details -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Customer Details</h6>
                        <p class="mb-1"><strong>Name:</strong> <?= esc($booking['customer_name']) ?></p>
                        <?php if (!empty($booking['customer_email'])) : ?>
                            <p class="mb-1"><strong>Email:</strong> <?= esc($booking['customer_email']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($booking['customer_phone'])) : ?>
                            <p class="mb-0"><strong>Phone:</strong> <?= esc($booking['customer_phone']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Booking Details -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Booking Details</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong><?= esc($booking['service_name']) ?></strong><br>
                                            <small class="text-muted">
                                                <?= date('l, F j, Y', strtotime($booking['booking_date'])) ?><br>
                                                <?= date('h:i A', strtotime($booking['start_time'])) ?> - <?= date('h:i A', strtotime($booking['end_time'])) ?>
                                            </small>
                                        </td>
                                        <td class="text-end">Rp <?= number_format($booking['price'], 2) ?></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th class="text-end">Rp <?= number_format($booking['price'], 2) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Payment Details -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Payment Details</h6>
                        <p class="mb-1"><strong>Reference:</strong> <?= esc($booking['payment_reference']) ?></p>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Paid</span></p>
                        <p class="mb-0"><strong>Date:</strong> <?= date('F j, Y H:i', strtotime($booking['payment_date'])) ?></p>
                    </div>
                    
                    <!-- Footer -->
                    <div class="border-top pt-4 mt-4">
                        <div class="text-center">
                            <p class="mb-1">Thank you for your business!</p>
                            <a href="<?= base_url('bookings/view/' . $booking['id']) ?>" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Booking
                            </a>
                            <button type="button" class="btn btn-primary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Print Receipt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .breadcrumb, .navbar, .sidenav {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .container-fluid {
        padding: 0 !important;
    }
}
</style>

<?= $this->endSection() ?>
