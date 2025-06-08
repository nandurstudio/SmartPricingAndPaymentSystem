<?= $this->extend('layouts/tenant_website') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
        </div>
        <h1 class="mb-3">Welcome to <?= esc($tenant['txtTenantName']) ?>!</h1>
        <p class="lead mb-4">Your business website has been successfully set up.</p>
        <?php 
        $settings = json_decode($tenant['jsonSettings'] ?? '{}', true);
        $description = $settings['description'] ?? '';
        if (!empty($description)): ?>
            <div class="card border-0 shadow-sm mb-4 mx-auto" style="max-width: 600px;">
                <div class="card-body py-4">
                    <p class="mb-0"><?= esc($description) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-center gap-3">
            <a href="<?= base_url('tenants/edit/' . $tenant['intTenantID']) ?>" class="btn btn-primary">
                <i class="fas fa-cog me-1"></i> Manage Your Business
            </a>
            <a href="<?= base_url('services/create?tenant_id=' . $tenant['intTenantID']) ?>" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Add Services
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
