<?= $this->extend('layouts/tenant_website') ?>

<?= $this->section('content') ?>
<section class="dashboard-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Welcome to <?= esc($tenant['txtTenantName']) ?></h2>
            <p class="text-muted">Manage your business from one place</p>
        </div>

        <div class="row g-4">
            <!-- Services Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 text-primary mb-3">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <h5 class="card-title">Services</h5>
                        <p class="card-text text-muted">
                            <?= $stats['active_services'] ?> Active Services
                        </p>
                        <a href="<?= tenant_url('services') ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Bookings Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 text-success mb-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h5 class="card-title">Bookings</h5>
                        <p class="card-text text-muted">
                            <?= $stats['pending_bookings'] ?> Pending Bookings
                        </p>
                        <a href="<?= tenant_url('bookings') ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Schedules Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 text-warning mb-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="card-title">Schedules</h5>
                        <p class="card-text text-muted">Manage your availability</p>
                        <a href="<?= tenant_url('schedules') ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="display-4 text-info mb-3">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5 class="card-title">Tenant Settings</h5>
                        <p class="card-text text-muted">Configure your tenant</p>
                        <a href="<?= tenant_url('settings') ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Add this script to ensure proper URL handling in JavaScript
$tenantUrl = rtrim(tenant_url(), '/');
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Make sure all links stay within tenant subdomain
    document.querySelectorAll('a[href^="/"]').forEach(function(link) {
        if (!link.href.startsWith('<?= $tenantUrl ?>')) {
            link.href = '<?= $tenantUrl ?>' + link.getAttribute('href');
        }
    });
});
</script>
<?= $this->endSection() ?>
