<?= $this->extend('layouts/tenant_website') ?>

<?= $this->section('content') ?>
<section class="services-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Our Services</h2>
            <p class="text-muted">Choose from our range of professional services</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <?php if (!empty($service['txtImage'])): ?>
                                <img src="<?= base_url('uploads/services/' . $service['txtImage']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= esc($service['txtName']) ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="fas fa-spa text-secondary" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($service['txtName']) ?></h5>
                                <p class="text-muted mb-2"><?= esc($service['txtDuration']) ?> minutes</p>
                                <p class="card-text"><?= esc($service['txtDescription']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Rp <?= number_format($service['decPrice'], 0, ',', '.') ?></h4>
                                    <a href="#" class="btn btn-primary" onclick="bookService(<?= $service['intServiceID'] ?>)">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-spa text-muted mb-3" style="font-size: 3rem;"></i>
                        <h4 class="text-muted">No services available</h4>
                        <p class="text-muted">Please check back later.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
function bookService(serviceId) {
    // Add your booking logic here
    window.location.href = `<?= current_url() ?>/booking/create?service_id=${serviceId}`;
}
</script>
<?= $this->endSection() ?>
