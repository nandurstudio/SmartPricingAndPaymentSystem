<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-times-circle text-danger" style="font-size: 64px;"></i>
                    </div>
                    <h2 class="card-title"><?= $pageTitle ?></h2>
                    <p class="card-text"><?= $pageSubTitle ?></p>
                    <p class="text-danger mb-4"><?= $error_message ?? 'An error occurred during payment processing.' ?></p>
                    
                    <div class="alert alert-danger">
                        <h5>What can you do?</h5>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check me-2"></i> Try another payment method</li>
                            <li><i class="fas fa-check me-2"></i> Check your card/bank account balance</li>
                            <li><i class="fas fa-check me-2"></i> Contact your bank if the problem persists</li>
                            <li><i class="fas fa-check me-2"></i> Contact our support for assistance</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <a href="<?= rtrim(base_url(), '/') . '/tenants/activate-subscription/' . $tenant['intTenantID'] ?>" class="btn btn-primary me-2">
                            <i class="fas fa-redo me-2"></i> Try Again
                        </a>
                        <a href="<?= base_url('tenants/view/' . $tenant['intTenantID']) ?>" class="btn btn-secondary">
                            <i class="fas fa-home me-2"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">Need help? Contact our support team</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
