<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                    </div>
                    <h2 class="card-title"><?= $pageTitle ?></h2>
                    <p class="card-text"><?= $pageSubTitle ?></p>
                    <p class="text-muted mb-4">Transaction ID: <?= $transaction['transaction_id'] ?? 'N/A' ?></p>
                    
                    <div class="alert alert-success">
                        <h5>What happens next?</h5>
                        <p class="mb-0">Your subscription has been activated. You can now use all the features of your subscription plan.</p>
                    </div>

                    <div class="mb-4">
                        <a href="<?= base_url('tenants/view/' . $tenantId) ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i> Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
