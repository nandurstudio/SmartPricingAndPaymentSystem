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
                    <h2 class="card-title">Payment Pending</h2>
                    <p class="card-text">Your payment is being processed. Please complete the payment to activate your subscription.</p>
                    <p class="text-muted mb-4">Transaction ID: <?= $transaction_id ?? 'N/A' ?></p>
                    
                    <div class="alert alert-info">
                        <h5>What happens next?</h5>
                        <p class="mb-0">Once your payment is confirmed, your subscription will be automatically activated. You'll receive an email notification when this happens.</p>
                    </div>

                    <div class="mb-4">
                        <a href="<?= base_url('tenant') ?>" class="btn btn-secondary me-2">
                            Back to Dashboard
                        </a>
                        <button onclick="checkStatus()" class="btn btn-primary">
                            Check Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkStatus() {
    window.location.reload();
}
</script>
<?= $this->endSection() ?>
