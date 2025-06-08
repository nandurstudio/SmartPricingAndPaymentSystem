<?= $this->extend('layouts/tenant_website') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="hero py-5 <?= ($settings['headerStyle'] ?? '') === 'transparent' ? 'bg-transparent' : 'bg-light' ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-4 animate-on-scroll animate__fadeInLeft"><?= esc($tenant['txtTenantName']) ?></h1>
                <?php if (!empty($settings['description'])): ?>
                    <p class="lead mb-4 animate-on-scroll animate__fadeInLeft" data-animation="animate__fadeInLeft"><?= esc($settings['description']) ?></p>
                <?php endif; ?>
                <a href="#services" class="btn btn-primary btn-lg animate-on-scroll" data-animation="animate__fadeInUp">
                    View Our Services
                </a>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <?php if (!empty($tenant['txtLogo'])): ?>
                    <img src="<?= get_tenant_logo_url($tenant['txtLogo']) ?>" 
                         alt="<?= esc($tenant['txtTenantName']) ?>"
                         class="img-fluid rounded shadow-lg animate-on-scroll"
                         data-animation="animate__fadeInRight"
                         style="max-height: 300px;">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold animate-on-scroll" data-animation="animate__fadeInUp">Our Services</h2>
            <p class="text-muted animate-on-scroll" data-animation="animate__fadeInUp">Choose from our range of professional services</p>
        </div>

        <div class="row g-4">
            <?php if (isset($services) && !empty($services)) : ?>
                <?php foreach ($services as $index => $service) : ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 animate-on-scroll" data-animation="animate__fadeInUp" style="animation-delay: <?= $index * 0.2 ?>s">
                            <?php if (!empty($service['txtImage'])): ?>
                                <img src="<?= base_url('uploads/services/' . $service['txtImage']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= esc($service['txtName']) ?>"
                                     loading="lazy"
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="fas fa-spa text-secondary" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($service['txtName']) ?></h5>
                                <p class="card-text"><?= esc($service['txtDescription'] ?? '') ?></p>
                                <div class="d-flex justify-content-between align-items-end mt-3">
                                    <div>
                                        <h6 class="mb-0">Starting from</h6>
                                        <h4 class="mb-0 text-primary">Rp <?= number_format($service['decPrice'], 0, ',', '.') ?></h4>
                                    </div>
                                    <a href="<?= base_url("booking/create?service={$service['intServiceID']}") ?>" 
                                       class="btn btn-primary"
                                       data-bs-toggle="tooltip"
                                       title="Book this service now">
                                        <i class="fas fa-calendar-plus me-1"></i> Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <img src="<?= base_url('assets/images/empty-services.svg') ?>" 
                             alt="No services" 
                             class="img-fluid mb-3"
                             style="max-height: 200px;"
                         style="width: 200px;">
                    <h4 class="text-muted">No services available yet</h4>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
