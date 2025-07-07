<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Bookings for Tenant #<?= esc($tenantID) ?></h1>
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-list"></i> Booking List
        </div>
        <div class="card-body">
            <?php if (empty($bookings)): ?>
                <div class="alert alert-info">No bookings found for this tenant.</div>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= esc($booking['intBookingID'] ?? '-') ?></td>
                                <td><?= esc($booking['txtCustomerName'] ?? '-') ?></td>
                                <td><?= esc($booking['txtCustomerEmail'] ?? '-') ?></td>
                                <td><?= esc($booking['txtServiceName'] ?? '-') ?></td>
                                <td><?= esc($booking['txtStatus'] ?? '-') ?></td>
                                <td><?= esc($booking['dtmBookingDate'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
