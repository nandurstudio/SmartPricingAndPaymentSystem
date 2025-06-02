<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?= $pageTitle ?></h5>
                        <a href="<?= base_url('onboarding/setup-tenant') ?>" class="btn btn-primary btn-sm">
                            <i data-feather="plus"></i> Create New Tenant
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($tenants)) : ?>
                        <div class="text-center py-5">
                            <i data-feather="briefcase" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                            <h5>No tenants found</h5>
                            <p class="text-muted">You haven't created any business tenants yet.</p>
                            <a href="<?= base_url('onboarding/setup-tenant') ?>" class="btn btn-primary">
                                Create Your First Tenant
                            </a>
                        </div>
                    <?php else : ?>                        <div class="row">
                            <?php foreach ($tenants as $tenant) : ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar avatar-lg me-3">
                                                    <?php if (!empty($tenant['txtLogo'])) : ?>
                                                        <img src="<?= base_url('uploads/tenants/' . $tenant['txtLogo']) ?>" alt="<?= esc($tenant['txtTenantName']) ?>" class="avatar-img rounded">
                                                    <?php else : ?>
                                                        <div class="avatar-initial rounded bg-label-primary">
                                                            <?= strtoupper(substr($tenant['txtTenantName'], 0, 2)) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1"><?= esc($tenant['txtTenantName']) ?></h5>
                                                    <small class="text-muted"><?= esc($tenant['service_type_name'] ?? 'N/A') ?></small>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted">Status:</small>
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
                                                <span class="badge bg-<?= $statusClass ?> ms-1">
                                                    <?= ucfirst(str_replace('_', ' ', $tenant['txtStatus'])) ?>
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted">Subscription:</small>
                                                <span class="badge bg-primary ms-1">
                                                    <?= ucfirst($tenant['txtSubscriptionPlan']) ?>
                                                </span>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <a href="<?= base_url('tenant/manage/' . $tenant['intTenantID']) ?>" class="btn btn-primary btn-sm">
                                                    <i data-feather="settings" class="me-1"></i> Manage
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
