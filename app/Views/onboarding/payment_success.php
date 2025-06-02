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
                    <h2 class="card-title">Payment Successful!</h2>
                    <p class="card-text">Your subscription has been activated successfully.</p>
                    <p class="text-muted mb-4">Transaction ID: <?= $transaction->transaction_id ?? 'N/A' ?></p>
                    
                    <div class="mb-4">
                        <a href="<?= base_url('tenant/setup/' . $tenantId) ?>" class="btn btn-primary btn-lg">
                            Continue Setup <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
