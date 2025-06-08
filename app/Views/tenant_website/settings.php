<?= $this->extend('layouts/tenant_website') ?>

<?= $this->section('content') ?>
<section class="settings-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Tenant Settings</h2>
            <p class="text-muted">View and manage your tenant information</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <?php if (!empty($tenant['txtLogo'])): ?>
                                <img src="<?= get_tenant_logo_url($tenant['txtLogo']) ?>" 
                                     alt="<?= esc($tenant['txtTenantName']) ?>" 
                                     class="img-fluid rounded-circle mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-building text-secondary" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <h3 class="mb-2"><?= esc($tenant['txtTenantName']) ?></h3>
                            <p class="text-muted mb-0">Tenant Code: <?= esc($tenant['txtTenantCode']) ?></p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Business Type</label>
                                    <p class="mb-2 fw-bold"><?= esc($tenant['service_type_name'] ?? 'N/A') ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Status</label>
                                    <?php
                                    $statusClass = match($tenant['txtStatus']) {
                                        'active' => 'success',
                                        'inactive' => 'danger',
                                        'suspended' => 'warning',
                                        'pending' => 'info',
                                        'pending_verification' => 'primary',
                                        default => 'secondary'
                                    };
                                    ?>
                                    <p class="mb-2">
                                        <span class="badge bg-<?= $statusClass ?> px-3 py-2">
                                            <?= ucfirst(str_replace('_', ' ', $tenant['txtStatus'])) ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Custom Domain</label>
                                    <p class="mb-2">
                                        <?php if (!empty($tenant['txtDomain'])): ?>
                                            <a href="<?= generate_tenant_url($tenant['txtDomain']) ?>" target="_blank" class="text-decoration-none">
                                                <?= $tenant['txtDomain'] ?>.<?= $baseDomain ?>
                                                <i class="fas fa-external-link-alt ms-1"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Not set</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Theme</label>
                                    <p class="mb-2">
                                        <i class="fas fa-palette me-1"></i>
                                        <?= ucfirst(esc($tenant['txtTheme'] ?? 'default')) ?>
                                    </p>
                                </div>
                            </div>
                            <?php if (!empty($description)): ?>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="text-muted small">Description</label>
                                    <p class="mb-2"><?= esc($description) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 d-grid gap-2">
                            <button type="button" class="btn btn-primary" onclick="location.href='<?= base_url('tenants/edit/' . $tenant['intTenantID']) ?>'">
                                <i class="fas fa-edit me-1"></i> Edit Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
