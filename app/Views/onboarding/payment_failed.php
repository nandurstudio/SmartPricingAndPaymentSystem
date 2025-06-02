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
                    <h2 class="card-title">Payment Failed</h2>
                    <p class="card-text">Unfortunately, there was a problem processing your payment.</p>
                    <?php if (session()->has('error') || isset($error_message)) : ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?? $error_message ?>
                        </div>
                    <?php endif ?>
                    
                    <div class="alert alert-info">
                        <h5>What can you do?</h5>
                        <ul class="text-start">
                            <li>Try the payment again</li>
                            <li>Use a different payment method</li>
                            <li>Check your card/bank account balance</li>
                            <li>Contact your bank if the problem persists</li>
                            <li>Contact our support for assistance</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <a href="<?= base_url('onboarding/retry-payment/' . $tenantId) ?>" class="btn btn-primary me-2">
                            Try Again
                        </a>
                        <a href="<?= base_url('tenant') ?>" class="btn btn-secondary">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">
                        Need help? <a href="#" onclick="alert('Contact support@yourdomain.com')">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
