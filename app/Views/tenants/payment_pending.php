<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-clock text-warning" style="font-size: 64px;"></i>
                    </div>
                    <h2 class="card-title"><?= $pageTitle ?></h2>
                    <p class="card-text"><?= $pageSubTitle ?></p>
                    <p class="text-muted mb-4">Transaction ID: <?= $transaction_id ?? 'N/A' ?></p>
                    
                    <div class="alert alert-info">
                        <h5>What happens next?</h5>
                        <p class="mb-0">Once your payment is confirmed, your subscription will be automatically activated. You'll receive an email notification when this happens.</p>
                    </div>

                    <div class="mb-4">
                        <a href="<?= base_url('tenants/view/' . $tenant['intTenantID']) ?>" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> Back to Dashboard
                        </a>
                        <a href="<?= rtrim(base_url(), '/') . '/tenants/activate-subscription/' . $tenant['intTenantID'] ?>" class="btn btn-outline-primary">
                            <i class="fas fa-sync-alt me-2"></i> Try Another Payment Method
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">For assistance, please contact our support team</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
