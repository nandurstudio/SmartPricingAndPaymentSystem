<?= $this->extend('layouts/tenant_website') ?>

<?= $this->section('content') ?>
<section class="bookings-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">My Bookings</h2>
            <p class="text-muted">View and manage your bookings</p>
        </div>

        <?php if (!empty($bookings)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>
                                    <strong><?= $booking['txtBookingCode'] ?></strong><br>
                                    <small class="text-muted">Created: <?= date('M d, Y', strtotime($booking['dtmCreatedDate'])) ?></small>
                                </td>
                                <td><?= esc($booking['service_name']) ?></td>
                                <td>
                                    <?= date('M d, Y', strtotime($booking['dtmBookingDate'])) ?><br>
                                    <small class="text-muted"><?= date('h:i A', strtotime($booking['dtmBookingTime'])) ?></small>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = match($booking['txtStatus']) {
                                        'confirmed' => 'success',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger',
                                        'completed' => 'info',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $statusClass ?>">
                                        <?= ucfirst($booking['txtStatus']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-light" onclick="viewBooking(<?= $booking['intBookingID'] ?>)">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar text-muted mb-3" style="font-size: 3rem;"></i>
                <h4 class="text-muted">No bookings found</h4>
                <p class="text-muted mb-4">You haven't made any bookings yet.</p>
                <a href="<?= current_url() ?>/services" class="btn btn-primary">
                    Browse Services
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function viewBooking(bookingId) {
    window.location.href = `<?= current_url() ?>/booking/${bookingId}`;
}
</script>
<?= $this->endSection() ?>
